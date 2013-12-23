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
namespace TaxonomyTest\Fake;

use Taxonomy\Model\TaxonomyTermModelInterface;

/**
 * @codeCoverageIgnore
 */
class TermFake implements TaxonomyTermModelInterface
{

    protected $id, $slug, $children = array(), $taxonomy;

    /**
     *
     * @return field_type $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *
     * @return field_type $slug
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     *
     * @param field_type $id            
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     *
     * @param field_type $slug            
     * @return self
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
        return $this;
    }

    /**
     *
     * @return field_type $children
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     *
     * @param field_type $children            
     * @return self
     */
    public function setChildren($children)
    {
        $this->children = $children;
        return $this;
    }
	/* (non-PHPdoc)
     * @see \Taxonomy\Model\TaxonomyTermModelInterface::getDescription()
     */
    public function getDescription ()
    {
        // TODO Auto-generated method stub
        
    }

	/* (non-PHPdoc)
     * @see \Taxonomy\Model\TaxonomyTermModelInterface::hasParent()
     */
    public function hasParent ()
    {
        // TODO Auto-generated method stub
        
    }

	/* (non-PHPdoc)
     * @see \Taxonomy\Model\TaxonomyTermModelInterface::getEntity()
     */
    public function getEntity ()
    {
        // TODO Auto-generated method stub
        
    }

	/* (non-PHPdoc)
     * @see \Taxonomy\Model\TaxonomyTermModelInterface::hasChildren()
     */
    public function hasChildren ()
    {
        // TODO Auto-generated method stub
        
    }

	/* (non-PHPdoc)
     * @see \Taxonomy\Model\TaxonomyTermModelInterface::getTaxonomy()
     */
    public function getTaxonomy ()
    {
        // TODO Auto-generated method stub
        
    }

	/* (non-PHPdoc)
     * @see \Taxonomy\Model\TaxonomyTermModelInterface::getType()
     */
    public function getType ()
    {
        // TODO Auto-generated method stub
        
    }

	/* (non-PHPdoc)
     * @see \Taxonomy\Model\TaxonomyTermModelInterface::getParent()
     */
    public function getParent ()
    {
        // TODO Auto-generated method stub
        
    }

	/* (non-PHPdoc)
     * @see \Taxonomy\Model\TaxonomyTermModelInterface::getName()
     */
    public function getName ()
    {
        // TODO Auto-generated method stub
        
    }

	/* (non-PHPdoc)
     * @see \Taxonomy\Model\TaxonomyTermModelInterface::getPosition()
     */
    public function getPosition ()
    {
        // TODO Auto-generated method stub
        
    }

	/* (non-PHPdoc)
     * @see \Taxonomy\Model\TaxonomyTermModelInterface::getLanguage()
     */
    public function getLanguage ()
    {
        // TODO Auto-generated method stub
        
    }

	/* (non-PHPdoc)
     * @see \Taxonomy\Model\TaxonomyTermModelInterface::getAssociated()
     */
    public function getAssociated ($association)
    {
        // TODO Auto-generated method stub
        
    }

	/* (non-PHPdoc)
     * @see \Taxonomy\Model\TaxonomyTermModelInterface::countAssociations()
     */
    public function countAssociations ($association)
    {
        // TODO Auto-generated method stub
        
    }

	/* (non-PHPdoc)
     * @see \Taxonomy\Model\TaxonomyTermModelInterface::isAssociated()
     */
    public function isAssociated ($association,\Taxonomy\Model\TaxonomyTermEntityAwareInterface $object)
    {
        // TODO Auto-generated method stub
        
    }

	/* (non-PHPdoc)
     * @see \Taxonomy\Model\TaxonomyTermModelInterface::associateObject()
     */
    public function associateObject ($association,\Taxonomy\Model\TaxonomyTermEntityAwareInterface $object)
    {
        // TODO Auto-generated method stub
        
    }

	/* (non-PHPdoc)
     * @see \Taxonomy\Model\TaxonomyTermModelInterface::positionAssociatedObject()
     */
    public function positionAssociatedObject ($association, $objectId, $position)
    {
        // TODO Auto-generated method stub
        
    }

	/* (non-PHPdoc)
     * @see \Taxonomy\Model\TaxonomyTermModelInterface::removeAssociation()
     */
    public function removeAssociation ($field,\Taxonomy\Model\TaxonomyTermEntityAwareInterface $object)
    {
        // TODO Auto-generated method stub
        
    }

	/* (non-PHPdoc)
     * @see \Taxonomy\Model\TaxonomyTermModelInterface::setTaxonomy()
     */
    public function setTaxonomy (\Taxonomy\Entity\TaxonomyInterface $taxonomy)
    {
        // TODO Auto-generated method stub
        
    }

	/* (non-PHPdoc)
     * @see \Taxonomy\Model\TaxonomyTermModelInterface::setDescription()
     */
    public function setDescription ($description)
    {
        // TODO Auto-generated method stub
        
    }

	/* (non-PHPdoc)
     * @see \Taxonomy\Model\TaxonomyTermModelInterface::setParent()
     */
    public function setParent (\Taxonomy\Model\TaxonomyTermModelInterface $parent)
    {
        // TODO Auto-generated method stub
        
    }

	/* (non-PHPdoc)
     * @see \Taxonomy\Model\TaxonomyTermModelInterface::setPosition()
     */
    public function setPosition ($position)
    {
        // TODO Auto-generated method stub
        
    }

	/* (non-PHPdoc)
     * @see \Taxonomy\Model\TaxonomyTermModelInterface::findAncestorByTypeName()
     */
    public function findAncestorByTypeName ($name)
    {
        // TODO Auto-generated method stub
        
    }

	/* (non-PHPdoc)
     * @see \Taxonomy\Model\TaxonomyTermModelInterface::knowsAncestor()
     */
    public function knowsAncestor (\Taxonomy\Model\TaxonomyTermModelInterface $ancestor)
    {
        // TODO Auto-generated method stub
        
    }

	/* (non-PHPdoc)
     * @see \Uuid\Entity\UuidHolder::getUuid()
     */
    public function getUuid ()
    {
        // TODO Auto-generated method stub
        
    }

	/* (non-PHPdoc)
     * @see \Uuid\Entity\UuidHolder::getHolderName()
     */
    public function getHolderName ()
    {
        // TODO Auto-generated method stub
        
    }

	/* (non-PHPdoc)
     * @see \Uuid\Entity\UuidHolder::getUuidEntity()
     */
    public function getUuidEntity ()
    {
        // TODO Auto-generated method stub
        
    }

	/* (non-PHPdoc)
     * @see \Uuid\Entity\UuidHolder::getTrashed()
     */
    public function getTrashed ()
    {
        // TODO Auto-generated method stub
        
    }

	/* (non-PHPdoc)
     * @see \Uuid\Entity\UuidHolder::setTrashed()
     */
    public function setTrashed ($trashed)
    {
        // TODO Auto-generated method stub
        
    }

	/* (non-PHPdoc)
     * @see \Uuid\Entity\UuidHolder::setUuid()
     */
    public function setUuid (\Uuid\Entity\UuidInterface $uuid)
    {
        // TODO Auto-generated method stub
        
    }

	/* (non-PHPdoc)
     * @see \Term\Entity\TermEntityAwareInterface::setTerm()
     */
    public function setTerm (\Term\Model\TermModelInterface $term)
    {
        // TODO Auto-generated method stub
        
    }

	/* (non-PHPdoc)
     * @see \Term\Entity\TermEntityAwareInterface::getTerm()
     */
    public function getTerm ()
    {
        // TODO Auto-generated method stub
        
    }

}