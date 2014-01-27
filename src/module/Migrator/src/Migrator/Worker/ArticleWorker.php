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
use Flag\Manager\FlagManagerInterface;
use Language\Manager\LanguageManagerInterface;
use Migrator\Converter\ConverterChain;
use Migrator\Converter\PreConverterChain;
use Taxonomy\Manager\TaxonomyManagerInterface;
use User\Manager\UserManagerInterface;
use Uuid\Manager\UuidManagerInterface;
use Versioning\RepositoryManagerInterface;

class ArticleWorker implements Worker
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
     * @var RepositoryManagerInterface
     */
    protected $repositoryManager;

    protected $workload = [];

    public function __construct(
        EntityManager $objectManager,
        LRManager $entityManager,
        TaxonomyManagerInterface $taxonomyManager,
        LanguageManagerInterface $languageManager,
        UuidManagerInterface $uuidManager,
        UserManagerInterface $userManagerInterface,
        PreConverterChain $converterChain,
        FlagManagerInterface $flagManager,
        RepositoryManagerInterface $repositoryManager
    ) {
        $this->objectManager     = $objectManager;
        $this->entityManager     = $entityManager;
        $this->taxonomyManager   = $taxonomyManager;
        $this->languageManager   = $languageManager;
        $this->uuidManager       = $uuidManager;
        $this->userManager       = $userManagerInterface;
        $this->converterChain    = $converterChain;
        $this->flagManager       = $flagManager;
        $this->repositoryManager = $repositoryManager;
    }

    public function migrate(array & $results, array &$workload)
    {
        $i = 0;

        $user     = $this->userManager->getUserFromAuthenticator();
        $language = $this->languageManager->getLanguage(1);
        /** @var $articles \Migrator\Entity\ArticleTranslation[] */
        $articles = $this->objectManager->getRepository('Migrator\Entity\ArticleTranslation')->findAll();

        $total = count($articles);
        $i = 0;
        foreach ($articles as $article) {

            $i++;
            echo (($i / $total) * 100) . " ($i of $total)\n";

            $revision = $article->getCurrentRevision();
            if (is_object($revision)) {
                $content = $this->converterChain->convert(
                    utf8_encode($revision->getSummary() . $revision->getContent())
                );
                $title   = utf8_encode($revision->getTitle());

                $entity = $this->entityManager->createEntity('article', [], $language);

                $this->taxonomyManager->associateWith(8, 'entities', $entity);

                $repository = $this->repositoryManager->getRepository($entity);
                $revision = $repository->commitRevision(
                    ['title' => $title, 'content' => $content],
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

                $this->objectManager->persist($entity);

                $results['article'][$article->getArticleId()] = $entity;
            }
        }

        $this->objectManager->flush();

        return $results;
    }

    public function getWorkload()
    {
        return $this->workload;
    }
}
 