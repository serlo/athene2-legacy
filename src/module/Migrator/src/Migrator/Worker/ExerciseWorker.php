<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author    Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license   LGPL-3.0
 * @license   http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link      https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013-2014 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Migrator\Worker;

use Doctrine\ORM\EntityManager;
use Entity\Entity\EntityInterface;
use Entity\Manager\EntityManagerInterface as LRManager;
use Entity\Options\ModuleOptions;
use Flag\Manager\FlagManagerInterface;
use Language\Manager\LanguageManagerInterface;
use License\Manager\LicenseManagerInterface;
use Link\Service\LinkServiceInterface;
use Migrator\Converter\ConverterChain;
use Migrator\Converter\PreConverterChain;
use Taxonomy\Manager\TaxonomyManagerInterface;
use User\Manager\UserManagerInterface;
use Uuid\Manager\UuidManagerInterface;
use Versioning\RepositoryManagerInterface;

class ExerciseWorker implements Worker
{
    protected $licenses = [
        64 => 2,
        66 => 3,
        70 => 4,
        71 => 5,
        72 => 6,
        73 => 7,
        89 => 8
    ];

    /**
     * @var EntityManager
     */
    protected $objectManager;

    /**
     * @var LRManager
     */
    protected $entityManager;

    /**
     * @var TaxonomyManager
     */
    protected $taxonomyManager;

    /**
     * @var UuidManagerInterface
     */
    protected $uuidManager;

    /**
     * @var ConverterChain
     */
    protected $converterChain;

    /**
     * @var \User\Manager\UserManagerInterface
     */
    protected $userManager;

    /**
     * @var FlagManagerInterface
     */
    protected $flagManager;

    /**
     * @var LinkServiceInterface
     */
    protected $linkService;

    /**
     * @var ModuleOptions
     */
    protected $moduleOptions;

    protected $linkWorkload = [];

    protected $workload = [];

    /**
     * @var RepositoryManagerInterface
     */
    protected $repositoryManager;

    /**
     * @var LicenseManagerInterface
     */
    protected $licenseManager;

    public function __construct(
        EntityManager $objectManager,
        LRManager $entityManager,
        TaxonomyManagerInterface $taxonomyManager,
        LanguageManagerInterface $languageManager,
        UuidManagerInterface $uuidManager,
        UserManagerInterface $userManagerInterface,
        PreConverterChain $converterChain,
        FlagManagerInterface $flagManager,
        LinkServiceInterface $linkService,
        ModuleOptions $moduleOptions,
        RepositoryManagerInterface $repositoryManager,
        LicenseManagerInterface $licenseManager
    ) {
        $this->objectManager     = $objectManager;
        $this->entityManager     = $entityManager;
        $this->taxonomyManager   = $taxonomyManager;
        $this->languageManager   = $languageManager;
        $this->uuidManager       = $uuidManager;
        $this->userManager       = $userManagerInterface;
        $this->converterChain    = $converterChain;
        $this->flagManager       = $flagManager;
        $this->linkService       = $linkService;
        $this->moduleOptions     = $moduleOptions;
        $this->repositoryManager = $repositoryManager;
        $this->licenseManager    = $licenseManager;
    }

    public function migrate(array & $results, array &$workload)
    {
        $user     = $this->userManager->getUserFromAuthenticator();
        $language = $this->languageManager->getLanguage(1);

        /** @var $exercises \Migrator\Entity\ExerciseTranslation[] */
        $exercises = $this->objectManager->getRepository('Migrator\Entity\ExerciseTranslation')->findAll();
        $total = count($exercises);
        $i = 0;

        foreach ($exercises as $exercise) {
            $i++;
            echo (($i / $total) * 100) . " ($i of $total)\n";

            $content = $this->converterChain->convert(
                utf8_encode($exercise->getContent())
            );

            if ($exercise->getExercise()->getChildren()->count() > 0) {
                $lrExercise = $this->entityManager->createEntity('text-exercise-group', [], $language);
            } elseif ($exercise->getExercise()->getParents()->count() > 0) {
                $lrExercise = $this->entityManager->createEntity('grouped-text-exercise', [], $language);
            } else {
                $lrExercise = $this->entityManager->createEntity('text-exercise', [], $language);
            }

            if ($exercise->getExercise()->getLicense() !== null) {
                $license = $this->licenses[$exercise->getExercise()->getLicense()];
                $license = $this->licenseManager->getLicense($license);
                $this->licenseManager->injectLicense($lrExercise, $license);
            }

            if ($exercise->getExercise()->getChildren()->count() > 0) {
                foreach ($exercise->getExercise()->getChildren() as $child) {
                    $this->linkWorkload[] = [
                        $exercise->getExercise(),
                        $child
                    ];
                }
            }
            if ($exercise->getExercise()->getParents()->count() == 0) {
                $folders = $exercise->getExercise()->getFolders();
                foreach ($folders as $folder) {
                    $folder = $folder->getFolder();
                    $term   = $results['folder'][$folder->getId()];
                    $this->taxonomyManager->associateWith($term->getId(), 'entities', $lrExercise);
                }
            }

            $repository = $this->repositoryManager->getRepository($lrExercise);
            $revision   = $repository->commitRevision(
                ['content' => $content],
                $user
            );
            $repository->checkoutRevision($revision->getId());

            $workload[] = [
                'entity' => $revision,
                'work'   => [
                    [
                        'name'  => 'content',
                        'value' => $content
                    ],
                ]
            ];

            $solution = $exercise->getSolution();

            if (is_object($solution) && (strlen(trim($solution->getContent())) || strlen(
                        trim($solution->getHint())
                    )) && $exercise->getExercise()->getChildren()->count() == 0
            ) {

                $lrSolution = $this->entityManager->createEntity('text-solution', [], $language);

                $content = $this->converterChain->convert(
                    utf8_encode($solution->getContent())
                );
                $hint    = $this->converterChain->convert(
                    utf8_encode($solution->getHint())
                );

                $repository = $this->repositoryManager->getRepository($lrSolution);
                $revision   = $repository->commitRevision(
                    ['content' => $content, 'hint' => $hint],
                    $user
                );
                $repository->checkoutRevision($revision->getId());

                $workload[] = [
                    'entity' => $revision,
                    'work'   => [
                        [
                            'name'  => 'content',
                            'value' => $content
                        ],
                        [
                            'name'  => 'hint',
                            'value' => $hint
                        ]
                    ]
                ];

                $this->objectManager->persist($lrExercise);
                $this->objectManager->persist($lrSolution);

                $this->doLink($lrExercise, $lrSolution);
            }

            $results['exercise'][$exercise->getExercise()->getId()] = $lrExercise;

        }

        $this->linkStuff($results);

        $this->objectManager->flush();

        return $results;
    }

    public function getWorkload()
    {
        return $this->workload;
    }

    protected function doLink(EntityInterface $from, EntityInterface $to)
    {
        //var_dump($to->getType()->getName());
        $options = $this->moduleOptions->getType(
            $from->getType()->getName()
        )->getComponent('link');
        $this->linkService->associate($from, $to, $options);
    }

    protected function linkStuff(array $results)
    {
        foreach ($this->linkWorkload as $work) {
            $from = $results['exercise'][$work[0]->getId()];
            $to   = $results['exercise'][$work[1]->getId()];

            $this->doLink($from, $to);
        }
    }
}
