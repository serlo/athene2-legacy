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

use Blog\Entity\PostInterface;
use Blog\Exception;
use Blog\Hydrator\PostHydrator;
use User\Model\UserModelInterface;
use DateTime;

class PostManager implements PostManagerInterface
{
    use \Taxonomy\Service\TermServiceAwareTrait,\Common\Traits\ObjectManagerAwareTrait, BlogManagerAwareTrait,\Uuid\Manager\UuidManagerAwareTrait,\Common\Traits\InstanceManagerTrait;

    protected $interfaces = array(
        'service' => 'Blog\Service\PostServiceInterface',
        'entity' => 'Blog\Entity\PostInterface'
    );

    protected function getInterface($name)
    {
        return $this->interfaces[$name];
    }

    public function getSlug()
    {
        return $this->getTermService()->getSlug();
    }

    public function getName()
    {
        return $this->getTermService()->getName();
    }

    public function getId()
    {
        return $this->getTermService()->getId();
    }

    public function findPublishedPosts()
    {
        return $this->findAllPosts()->filter(function (PostInterface $e)
        {
            return $e->isPublished() && ! $e->isTrashed();
        });
    }

    public function findAllPosts()
    {
        return $this->getTermService()->getAssociated('blogPosts');
    }

    public function getPost($id)
    {
        if (! is_numeric($id)) {
            throw new Exception\InvalidArgumentException(sprintf('Expected int but got `%s`.', gettype($id)));
        }
        
        if (! $this->hasInstance($id)) {
            $className = $this->getClassResolver()->resolveClassName($this->getInterface('entity'));
            $post = $this->getObjectManager()->find($className, $id);
            
            if (! is_object($post)) {
                throw new Exception\PostNotFoundException(sprintf('Could not find a blog post by the id `%d`', $id));
            }
            
            $service = $this->createService($post);
            $this->addInstance($id, $service);
        }
        
        return $this->getInstance($id);
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
        
        $post->persist();
        return $this;
    }

    public function createPost(UserModelInterface $author, $title, $content, DateTime $publish = NULL)
    {
        if ($publish === NULL) {
            $publish = new \DateTime("now");
        }

        /* @var $post PostInterface */
        $post = $this->getClassResolver()->resolve($this->getInterface('entity'));
        $this->getUuidManager()->injectUuid($post);
        
        $hydrator = new PostHydrator();
        $hydrator->hydrate([
            'author' => $author->getEntity(),
            'title' => $title,
            'content' => $content,
            'publish' => $publish
        ], $post);
        
        $this->getTermService()->associateObject('blogPosts', $post);
        
        $this->getObjectManager()->persist($post);
        
        return $this->createService($post);
    }

    protected function createService(PostInterface $post)
    {
        /* @var $service PostInterface */
        $service = $this->createInstance($this->getInterface('service'));
        $service->setEntity($post);
        return $service;
    }
}
