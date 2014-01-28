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
namespace Blog\Manager;

use Authorization\Service\AuthorizationAssertionTrait;
use Blog\Exception;
use Blog\Hydrator\PostHydrator;
use ClassResolver\ClassResolverAwareTrait;
use ClassResolver\ClassResolverInterface;
use Common\Traits\ObjectManagerAwareTrait;
use DateTime;
use Doctrine\Common\Persistence\ObjectManager;
use Language\Entity\LanguageInterface;
use Language\Manager\LanguageManagerAwareTrait;
use Language\Manager\LanguageManagerInterface;
use Taxonomy\Entity\TaxonomyTermInterface;
use Taxonomy\Manager\TaxonomyManagerAwareTrait;
use Taxonomy\Manager\TaxonomyManagerInterface;
use User\Entity\UserInterface;
use Uuid\Manager\UuidManagerAwareTrait;
use ZfcRbac\Service\AuthorizationService;

class BlogManager implements BlogManagerInterface
{
    use TaxonomyManagerAwareTrait, ObjectManagerAwareTrait;
    use ClassResolverAwareTrait, UuidManagerAwareTrait;
    use LanguageManagerAwareTrait, AuthorizationAssertionTrait;

    public function __construct(
        ClassResolverInterface $classResolver,
        TaxonomyManagerInterface $taxonomyManager,
        ObjectManager $objectManager,
        LanguageManagerInterface $languageManager,
        AuthorizationService $authorizationService
    ) {
        $this->classResolver   = $classResolver;
        $this->taxonomyManager = $taxonomyManager;
        $this->languageManager = $languageManager;
        $this->objectManager   = $objectManager;
        $this->setAuthorizationService($authorizationService);
    }

    public function getBlog($id)
    {
        return $this->getTaxonomyManager()->getTerm($id);
    }

    public function findAllBlogs(LanguageInterface $languageService)
    {
        $taxonomy = $this->getTaxonomyManager()->findTaxonomyByName('blog', $languageService);

        return $taxonomy->getChildren();
    }

    public function getPost($id)
    {
        $className = $this->getClassResolver()->resolveClassName('Blog\Entity\PostInterface');
        $post      = $this->getObjectManager()->find($className, $id);

        if (!is_object($post)) {
            throw new Exception\PostNotFoundException(sprintf('Could not find post "%d"', $id));
        }

        return $post;
    }

    public function trashPost($id)
    {
        $post = $this->getPost($id);

        $this->assertGranted('blog.post.trash', $post);

        $post->setTrashed(true);
        $this->getObjectManager()->persist($post);

        return $this;
    }

    public function updatePost($id, $title, $content, DateTime $publish = null)
    {
        $post = $this->getPost($id);

        $this->assertGranted('blog.post.update', $post);

        $hydrator = new PostHydrator();
        $hydrator->hydrate(
            [
                'title'   => $title,
                'content' => $content,
                'publish' => $publish
            ],
            $post
        );

        $this->getObjectManager()->persist($post);

        return $this;
    }

    public function createPost(
        TaxonomyTermInterface $taxonomy,
        UserInterface $author,
        $title,
        $content,
        DateTime $publish = null
    ) {
        $language = $this->getLanguageManager()->getLanguageFromRequest();
        $this->assertGranted('blog.post.create', $language);

        /* @var $post PostInterface */
        $post = $this->getClassResolver()->resolve('Blog\Entity\PostInterface');
        $this->getUuidManager()->injectUuid($post);

        $hydrator = new PostHydrator();
        $hydrator->hydrate(
            [
                'author'   => $author,
                'title'    => $title,
                'content'  => $content,
                'publish'  => $publish ? $publish : new DateTime(),
                'language' => $language
            ],
            $post
        );

        $this->getTaxonomyManager()->associateWith($taxonomy->getId(), 'blogPosts', $post);

        $this->getObjectManager()->persist($post);
        $this->getObjectManager()->persist($taxonomy);

        return $post;
    }

    public function flush()
    {
        $this->getObjectManager()->flush();

        return $this;
    }
}
