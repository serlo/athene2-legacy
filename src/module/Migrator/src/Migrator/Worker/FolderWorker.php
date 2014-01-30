<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author    Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license   LGPL-3.0
 * @license   http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link      https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Migrator\Worker;

use Doctrine\ORM\EntityManager;
use Flag\Manager\FlagManagerInterface;
use Instance\Manager\InstanceManagerInterface;
use Migrator\Converter\ConverterChain;
use Migrator\Converter\PreConverterChain;
use Taxonomy\Manager\TaxonomyManagerInterface;
use User\Manager\UserManagerInterface;
use Uuid\Manager\UuidManagerInterface;

class FolderWorker implements Worker
{

    /**
     * @var EntityManager
     */
    protected $objectManager;

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

    public function __construct(
        EntityManager $objectManager,
        TaxonomyManagerInterface $taxonomyManager,
        InstanceManagerInterface $instanceManager,
        UuidManagerInterface $uuidManager,
        UserManagerInterface $userManagerInterface,
        PreConverterChain $converterChain,
        FlagManagerInterface $flagManager
    ) {
        $this->objectManager   = $objectManager;
        $this->taxonomyManager = $taxonomyManager;
        $this->instanceManager = $instanceManager;
        $this->uuidManager     = $uuidManager;
        $this->userManager     = $userManagerInterface;
        $this->converterChain  = $converterChain;
        $this->flagManager     = $flagManager;
    }

    public function migrate(array & $results, array &$workload)
    {
        $instance      = $this->instanceManager->getInstanceFromRequest();
        $defaultParent = $this->taxonomyManager->getTerm(7);

        /* @var $folders \Migrator\Entity\Folder[] */
        $folders = $this->objectManager->getRepository('Migrator\Entity\Folder')->findAll();
        foreach ($folders as $folder) {
            $parent = $defaultParent;
            $name = utf8_encode($folder->getName());

            if(!$name){
                $name = 'empty name';
            }

            $term = $this->taxonomyManager->createTerm([
                'taxonomy' => $folder->getExercises()->count() ? 'topic' : 'abstract-topic' ,
                'parent'   => $parent,
                'term'     => [
                    'name' => $name
                ]
            ], $instance);

            $results['folder'][$folder->getId()] = $term;
        }

        foreach($folders as $folder){
            $parent = $defaultParent;
            if ($folder->getParent() !== null) {
                if (isset($results['folder'][$folder->getParent()->getId()])) {
                    $parent = $results['folder'][$folder->getParent()->getId()];
                }
            }
            $term = $results['folder'][$folder->getId()];
            $term->setParent($parent);
            $this->objectManager->persist($term);
        }

        $this->taxonomyManager->flush();

        return $results;
    }

    public function getWorkload(){
        return [];
    }
}
 