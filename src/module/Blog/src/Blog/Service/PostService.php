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
use DateTime;

class PostService implements PostServiceInterface
{
    use \Common\Traits\ObjectManagerAwareTrait;

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

    public function isPublished()
    {
        return $this->getEntity()->isPublished();
    }

    public function persist()
    {
        $this->getObjectManager()->persist($this->getEntity());
        return $this;
    }

    public function flush()
    {
        $this->getObjectManager()->flush($this->getEntity());
        return $this;
    }

    public function getPublish()
    {
        return $this->getEntity()->getPublish();
    }

    public function getCategory()
    {
        return $this->getEntity()->getCategory();
    }

    public function setTitle($title)
    {
        $this->getEntity()->setTitle($title);
        return $this;
    }

    public function getUuid()
    {
        return $this->getEntity()->getUuid();
    }

    public function getHolderName()
    {
        return $this->getEntity()->getHolderName();
    }

    public function getUuidEntity()
    {
        return $this->getEntity()->getUuidEntity();
    }

    public function getTrashed()
    {
        return $this->getEntity()->getTrashed();
    }

    public function getTaxonomyTerms()
    {
        return $this->getEntity()->getTaxonomyTerms();
    }

    public function setCategory(\Taxonomy\Model\TaxonomyTermModelInterface $category)
    {
        $this->getEntity()->setCategory($category->getEntity());
        return $this;
    }

    public function setTimestamp(Datetime $date)
    {
        $this->getEntity()->setTimestamp($date);
        return $this;
    }

    public function setContent($content)
    {
        $this->getEntity()->setContent($content);
        return $this;
    }

    public function setAuthor(\User\Model\UserModelInterface $author)
    {
        $this->getEntity()->setAuthor($author->getEntity());
        return $this;
    }

    public function setPublish(Datetime $publish = NULL)
    {
        $this->getEntity()->setPublish($publish);
        return $this;
    }

    public function setUuid(\Uuid\Entity\UuidInterface $uuid)
    {
        $this->getEntity()->setUuid($uuid);
        return $this;
    }

    public function addTaxonomyTerm(\Taxonomy\Model\TaxonomyTermModelInterface $taxonomyTerm, \Taxonomy\Model\TaxonomyTermNodeModelInterface $node = NULL)
    {
        $this->getEntity()->addTaxonomyTerm($taxonomyTerm->getEntity(), $node);
        return $this;
    }

    public function removeTaxonomyTerm(\Taxonomy\Model\TaxonomyTermModelInterface $taxonomyTerm, \Taxonomy\Model\TaxonomyTermNodeModelInterface $node = NULL)
    {
        $this->getEntity()->addTaxonomyTerm($taxonomyTerm->getEntity(), $node);
        return $this;
    }

    public function setTrashed($trashed)
    {
        $this->getEntity()->setTrashed($trashed);
    }
}