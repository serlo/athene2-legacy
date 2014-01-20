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
use Language\Manager\LanguageManagerInterface;
use Migrator\Converter\ConverterChain;
use Taxonomy\Manager\TaxonomyManagerInterface;
use User\Manager\UserManagerInterface;
use Uuid\Manager\UuidManagerInterface;

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

    public function __construct(
        EntityManager $objectManager,
        LRManager $entityManager,
        TaxonomyManagerInterface $taxonomyManager,
        LanguageManagerInterface $languageManager,
        UuidManagerInterface $uuidManager,
        UserManagerInterface $userManagerInterface,
        ConverterChain $converterChain
    ) {
        $this->objectManager   = $objectManager;
        $this->entityManager   = $entityManager;
        $this->taxonomyManager = $taxonomyManager;
        $this->languageManager = $languageManager;
        $this->uuidManager     = $uuidManager;
        $this->userManager     = $userManagerInterface;
        $this->converterChain  = $converterChain;
    }

    public function migrate()
    {
        $results = [];

        $language = $this->languageManager->getLanguage(1);
        /** @var $articles \Migrator\Entity\ArticleTranslation[] */
        $articles = $this->objectManager->getRepository('Migrator\Entity\ArticleTranslation')->findAll();
        foreach ($articles as $article) {
            $revision = $article->getCurrentRevision();
            if (is_object($revision)) {
                $content = $this->converterChain->convert($revision->getSummary() . $revision->getContent());
                $title   = $revision->getTitle();

                $entity = $this->entityManager->createEntity('article', [], $language);
                /* @var $entity \Entity\Entity\EntityInterface */
                $revision = $entity->createRevision();
                $revision->set('title', $title);
                $revision->set('content', $content);

                $revision->setAuthor($this->userManager->getUserFromAuthenticator());

                $this->uuidManager->injectUuid($revision);
                $this->uuidManager->flush();

                $entity->setCurrentRevision($revision);
                $this->objectManager->persist($revision);
                $this->objectManager->persist($entity);

                $this->taxonomyManager->associateWith(10, 'entities', $entity);

                $results['article' . $article->getArticleId()] = $entity->getId();
            }
            break;
        }

        $this->objectManager->flush();

        return $results;
    }
}
 