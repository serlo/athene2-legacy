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
namespace Blog\Service;

use Blog\Entity\PostInterface;

class PostService implements PostServiceInterface
{
    use\Common\Traits\ObjectManagerAwareTrait;

    /**
     *
     * @var PostInterface
     */
    protected $entity;

    public function getEntity()
    {
        return $this->entity;
    }

    public function setEntity(PostInterface $entity)
    {
        $this->entity = $entity;
        return $this;
    }

    public function getContent()
    {
        return $this->getEntity()->getContent();
    }

    public function getId()
    {
        return $this->getEntity()->getId();
    }

    public function getTitle()
    {
        return $this->getEntity()->getTitle();
    }

    public function getTimestamp()
    {
        return $this->getEntity()->getTimestamp();
    }

    public function getAuthor()
    {
        return $this->getEntity()->getAuthor();
    }

    public function isPublished(){
        return $this->getEntity()->isPublished();
    }
    
    public function setTrashed($trashed)
    {
        $this->getEntity()->setTrashed($trashed);
        return $this->persist();
    }

    protected function persist()
    {
        $this->getObjectManager()->persist($this->getEntity());
        return $this;
    }
}