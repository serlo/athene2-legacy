<?php
/**
 * 
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author	Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license	LGPL-3.0
 * @license	http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link		https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Blog\Manager;

use Language\Entity\LanguageInterface;
use Blog\Hydrator\PostHydrator;
use User\Model\UserModelInterface;
use DateTime;
use Taxonomy\Entity\TaxonomyTermInterface;
use Blog\Exception;

class BlogManager implements BlogManagerInterface
{
    use\Taxonomy\Manager\TaxonomyManagerAwareTrait,\Common\Traits\ObjectManagerAwareTrait,\ClassResolver\ClassResolverAwareTrait,\Uuid\Manager\UuidManagerAwareTrait;

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
        $post = $this->getObjectManager()->find($className, $id);
        
        if (! is_object($post)) {
            throw new Exception\PostNotFoundException(sprintf('Could not find post "%d"', $id));
        }
        
        return $post;
    }

    public function trashPost($id)
    {
        $post = $this->getPost($id);
        $post->setTrashed(true);
        $this->getObjectManager()->persist($post);
        return $this;
    }

    public function updatePost($id, $title, $content, DateTime $publish = NULL)
    {
        $post = $this->getPost($id);
        
        $hydrator = new PostHydrator();
        $hydrator->hydrate([
            'title' => $title,
            'content' => $content,
            'publish' => $publish
        ], $post);
        
        $this->getObjectManager()->persist($post);
        return $this;
    }

    public function createPost(TaxonomyTermInterface $taxonomy, UserModelInterface $author, $title, $content, DateTime $publish = NULL)
    {
        if ($publish === NULL) {
            $publish = new \DateTime("now");
        }
        
        /* @var $post PostInterface */
        $post = $this->getClassResolver()->resolve('Blog\Entity\PostInterface');
        $this->getUuidManager()->injectUuid($post);
        
        $hydrator = new PostHydrator();
        $hydrator->hydrate([
            'author' => $author->getEntity(),
            'title' => $title,
            'content' => $content,
            'publish' => $publish
        ], $post);
        
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