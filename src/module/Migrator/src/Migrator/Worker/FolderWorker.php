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
use Language\Manager\LanguageManagerInterface;
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
        LanguageManagerInterface $languageManager,
        UuidManagerInterface $uuidManager,
        UserManagerInterface $userManagerInterface,
        PreConverterChain $converterChain,
        FlagManagerInterface $flagManager
    ) {
        $this->objectManager   = $objectManager;
        $this->taxonomyManager = $taxonomyManager;
        $this->languageManager = $languageManager;
        $this->uuidManager     = $uuidManager;
        $this->userManager     = $userManagerInterface;
        $this->converterChain  = $converterChain;
        $this->flagManager     = $flagManager;
    }

    public function migrate()
    {
        $results       = ['folder'];
        $language      = $this->languageManager->getLanguageFromRequest();
        $defaultParent = $this->taxonomyManager->getTerm(9);

        /* @var $folders \Migrator\Entity\Folder[] */
        $folders = $this->objectManager->getRepository('Migrator\Entity\Folder')->findAll();
        foreach ($folders as $folder) {
            $parent = $defaultParent;
            if ($folder->getParent() !== null) {
                if (isset($results['folder'][$folder->getParent()->getId()])) {
                    $parent = $results['folder'][$folder->getParent()->getId()];
                }
            }

            $term = $this->taxonomyManager->createTerm([
                'taxonomy' => 'topic',
                'parent'   => $parent,
                'term'     => [
                    'name' => $folder->getName()
                ]
            ], $language);

            $results['folder'][$folder->getArticleId()] = $term;
        }

        $this->taxonomyManager->flush();

        return $results;
    }
}
 