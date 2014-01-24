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

    public function __construct(
        EntityManager $objectManager,
        LRManager $entityManager,
        TaxonomyManagerInterface $taxonomyManager,
        LanguageManagerInterface $languageManager,
        UuidManagerInterface $uuidManager,
        UserManagerInterface $userManagerInterface,
        PreConverterChain $converterChain,
        FlagManagerInterface $flagManager
    ) {
        $this->objectManager   = $objectManager;
        $this->entityManager   = $entityManager;
        $this->taxonomyManager = $taxonomyManager;
        $this->languageManager = $languageManager;
        $this->uuidManager     = $uuidManager;
        $this->userManager     = $userManagerInterface;
        $this->converterChain  = $converterChain;
        $this->flagManager     = $flagManager;
    }

    public function migrate(array $results)
    {
        $i        = 0;

        $user     = $this->userManager->getUserFromAuthenticator();
        $language = $this->languageManager->getLanguage(1);
        /** @var $articles \Migrator\Entity\ArticleTranslation[] */
        $articles = $this->objectManager->getRepository('Migrator\Entity\ArticleTranslation')->findAll();
        foreach ($articles as $article) {
            $revision = $article->getCurrentRevision();
            if (is_object($revision)) {
                $content = $this->converterChain->convert(
                    utf8_encode($revision->getSummary() . $revision->getContent())
                );
                $title   = utf8_encode($revision->getTitle());

                $entity = $this->entityManager->createEntity('article', [], $language);

                if ($this->converterChain->needsFlagging()) {
                    $this->flagManager->addFlag(24, 'Flagged by migrator', $entity->getId(), $user);
                }

                /* @var $entity \Entity\Entity\EntityInterface */
                $revision = $entity->createRevision();
                $revision->set('title', $title);
                $revision->set('content', $content);

                $revision->setAuthor($this->userManager->getUserFromAuthenticator());

                $this->uuidManager->injectUuid($revision);

                $entity->setCurrentRevision($revision);
                $this->objectManager->persist($revision);
                $this->objectManager->persist($entity);

                $this->taxonomyManager->associateWith(8, 'entities', $entity);

                $results['article'][$article->getArticleId()] = $entity;
            }
        }

        $this->objectManager->flush();

        return $results;
    }
}
 