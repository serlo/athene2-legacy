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
use Link\Service\LinkServiceInterface;
use Migrator\Converter\ConverterChain;
use Migrator\Converter\PreConverterChain;
use Taxonomy\Manager\TaxonomyManagerInterface;
use User\Manager\UserManagerInterface;
use Uuid\Manager\UuidManagerInterface;

class ExerciseWorker implements Worker
{
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
        ModuleOptions $moduleOptions
    ) {
        $this->objectManager   = $objectManager;
        $this->entityManager   = $entityManager;
        $this->taxonomyManager = $taxonomyManager;
        $this->languageManager = $languageManager;
        $this->uuidManager     = $uuidManager;
        $this->userManager     = $userManagerInterface;
        $this->converterChain  = $converterChain;
        $this->flagManager     = $flagManager;
        $this->linkService     = $linkService;
        $this->moduleOptions   = $moduleOptions;
    }

    public function migrate(array $results)
    {
        $user     = $this->userManager->getUserFromAuthenticator();
        $language = $this->languageManager->getLanguage(1);

        /** @var $exercises \Migrator\Entity\ExerciseTranslation[] */
        $exercises = $this->objectManager->getRepository('Migrator\Entity\ExerciseTranslation')->findAll();

        foreach ($exercises as $exercise) {

            $content = $this->converterChain->convert(
                utf8_encode($exercise->getContent())
            );

            if ($this->converterChain->needsFlagging()) {
                $this->flagManager->addFlag(24, 'Flagged by migrator', $lrExercise->getId(), $user);
            }

            if ($exercise->getExercise()->getChildren()->count() > 0) {
                $lrExercise = $this->entityManager->createEntity('text-exercise-group', [], $language);
            } elseif ($exercise->getExercise()->getParents()->count() > 0) {
                $lrExercise = $this->entityManager->createEntity('grouped-text-exercise', [], $language);
            } else {
                $lrExercise = $this->entityManager->createEntity('text-exercise', [], $language);
            }

            $revision = $lrExercise->createRevision();
            $revision->set('content', $content);
            $revision->setAuthor($this->userManager->getUserFromAuthenticator());

            $this->uuidManager->injectUuid($revision);

            $lrExercise->setCurrentRevision($revision);
            $this->objectManager->persist($revision);
            $this->objectManager->persist($lrExercise);

            if (is_object($exercise->getSolution()) && $exercise->getExercise()->getChildren()->count() == 0) {

                $solution   = $exercise->getSolution();
                $lrSolution = $this->entityManager->createEntity('text-solution', [], $language);

                $content = $this->converterChain->convert(
                    utf8_encode($solution->getContent())
                );
                $hint    = $this->converterChain->convert(
                    utf8_encode($solution->getHint())
                );

                $revision = $lrSolution->createRevision();
                $revision->set('hint', $hint);
                $revision->set('content', $content);
                $revision->setAuthor($this->userManager->getUserFromAuthenticator());

                $this->uuidManager->injectUuid($revision);

                $lrSolution->setCurrentRevision($revision);
                $this->objectManager->persist($revision);
                $this->objectManager->persist($lrSolution);

                $this->doLink($lrExercise, $lrSolution);
            }

            if ($exercise->getExercise()->getChildren()->count() > 0) {
                foreach($exercise->getExercise()->getChildren() as $child){
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
                    $term = $results['folder'][$folder->getId()];
                    $this->taxonomyManager->associateWith($term->getId(), 'entities', $lrExercise);
                }
            }

            $results['exercise'][$exercise->getExercise()->getId()] = $lrExercise;

        }

        $this->linkStuff($results);

        $this->objectManager->flush();

        return $results;
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
        foreach($this->linkWorkload as $work){
            $from = $results['exercise'][$work[0]->getId()];
            $to = $results['exercise'][$work[1]->getId()];

            $this->doLink($from, $to);
        }
    }
}
