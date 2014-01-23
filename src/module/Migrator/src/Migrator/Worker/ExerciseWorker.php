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
use Entity\Manager\EntityManager as LRManager;
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
        $this->moduleOptions = $moduleOptions;
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

            if ($exercise->getExercise()->isGroup()) {
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
            $this->uuidManager->flush();

            $lrExercise->setCurrentRevision($revision);
            $this->objectManager->persist($revision);
            $this->objectManager->persist($lrExercise);

            if (is_object($exercise->getSolution())) {
                $solution   = $exercise->getSolution();
                $lrSolution = $this->entityManager->createEntity('text-solution', [], $language);
            }

            if ($exercise->getExercise()->getParents()->count() > 0) {

                /* DO ME */
                $from    = $this->getEntityManager()->getEntity($this->params('from'));
                $to      = $this->getEntityManager()->getEntity($data['to']);
                $options = $this->getModuleOptions()->getType(
                    $from->getType()->getName()
                )->getComponent($type);

                $this->getLinkService()->dissociate($from, $entity, $options);
                $options = $this->getModuleOptions()->getType(
                    $to->getType()->getName()
                )->getComponent($type);
                $this->getLinkService()->associate($to, $entity, $options);
                /* DO ME */

            } else {
                $folders = $exercise->getExercise()->getFolders();
                foreach ($folders as $folder) {
                    $term = $results['folder'][$folder->getId()];
                    $this->taxonomyManager->associateWith($term->getId(), 'entities', $lrExercise);
                }
            }

            $results['exercise'][$exercise->getExercise()->getId()] = $lrExercise;

        }

        $this->objectManager->flush();

        return $results;
    }
}
 