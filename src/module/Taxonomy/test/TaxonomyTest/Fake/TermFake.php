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

use Taxonomy\Entity\TermTaxonomyInterface;
use Taxonomy\Entity\TermTaxonomyAware;

class TermFake implements TermTaxonomyInterface
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
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     *
     * @param field_type $slug            
     * @return $this
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
     * @return $this
     */
    public function setChildren($children)
    {
        $this->children = $children;
        return $this;
    }
    /*
     * (non-PHPdoc) @see \Taxonomy\Entity\TermTaxonomyInterface::getDescription()
     */
    public function getDescription()
    {
        // TODO Auto-generated method stub
    }
    
    /*
     * (non-PHPdoc) @see \Taxonomy\Entity\TermTaxonomyInterface::hasParent()
     */
    public function hasParent()
    {
        // TODO Auto-generated method stub
    }
    
    /*
     * (non-PHPdoc) @see \Taxonomy\Entity\TermTaxonomyInterface::hasChildren()
     */
    public function hasChildren()
    {
        // TODO Auto-generated method stub
    }
    
    /*
     * (non-PHPdoc) @see \Taxonomy\Entity\TermTaxonomyInterface::setDescription()
     */
    public function setDescription($description)
    {
        // TODO Auto-generated method stub
    }
    
    /*
     * (non-PHPdoc) @see \Taxonomy\Entity\TermTaxonomyInterface::getFactory()
     */
    public function getFactory()
    {
        // TODO Auto-generated method stub
    }
    
    /**
     * @return field_type $taxonomy
     */
    public function getTaxonomy ()
    {
        return $this->taxonomy;
    }

	/**
     * @param field_type $taxonomy
     * @return $this
     */
    public function setTaxonomy ($taxonomy)
    {
        $this->taxonomy = $taxonomy;
        return $this;
    }

	/*
     * (non-PHPdoc) @see \Taxonomy\Entity\TermTaxonomyInterface::getParent()
     */
    public function getParent()
    {
        // TODO Auto-generated method stub
    }
    
    /*
     * (non-PHPdoc) @see \Taxonomy\Entity\TermTaxonomyInterface::getName()
     */
    public function getName()
    {
        // TODO Auto-generated method stub
    }
    
    /*
     * (non-PHPdoc) @see \Taxonomy\Entity\TermTaxonomyInterface::setParent()
     */
    public function setParent($parent)
    {
        // TODO Auto-generated method stub
    }
    
    /*
     * (non-PHPdoc) @see \Taxonomy\Entity\TermTaxonomyInterface::getWeight()
     */
    public function getWeight()
    {
        // TODO Auto-generated method stub
    }
    
    /*
     * (non-PHPdoc) @see \Taxonomy\Entity\TermTaxonomyInterface::setWeight()
     */
    public function setWeight($weight)
    {
        // TODO Auto-generated method stub
    }
    
    /*
     * (non-PHPdoc) @see \Taxonomy\Entity\TermTaxonomyInterface::setName()
     */
    public function setName($name)
    {
        // TODO Auto-generated method stub
    }
    
    /*
     * (non-PHPdoc) @see \Taxonomy\Entity\TermTaxonomyInterface::getTerm()
     */
    public function getTerm()
    {
        // TODO Auto-generated method stub
    }
    
    /*
     * (non-PHPdoc) @see \Taxonomy\Entity\TermTaxonomyInterface::setTerm()
     */
    public function setTerm($term)
    {
        // TODO Auto-generated method stub
    }
    
    /*
     * (non-PHPdoc) @see \Taxonomy\Entity\TermTaxonomyInterface::getArrayCopy()
     */
    public function getArrayCopy()
    {
        // TODO Auto-generated method stub
    }
    
    /*
     * (non-PHPdoc) @see \Taxonomy\Entity\TermTaxonomyInterface::getAssociated()
     */
    public function getAssociated($field)
    {
        // TODO Auto-generated method stub
    }
    
    /*
     * (non-PHPdoc) @see \Taxonomy\Entity\TermTaxonomyInterface::countAssociated()
     */
    public function countAssociated($field)
    {
        // TODO Auto-generated method stub
    }
    
    /*
     * (non-PHPdoc) @see \Taxonomy\Entity\TermTaxonomyInterface::addAssociation()
     */
    public function addAssociation($field, TermTaxonomyAware $entity)
    {
        // TODO Auto-generated method stub
    }
    
    /*
     * (non-PHPdoc) @see \Taxonomy\Entity\TermTaxonomyInterface::removeAssociation()
     */
    public function removeAssociation($field, TermTaxonomyAware $entity)
    {
        // TODO Auto-generated method stub
    }
    
    /*
     * (non-PHPdoc) @see \Taxonomy\Entity\TermTaxonomyInterface::getLanguage()
     */
    public function getLanguage()
    {
        // TODO Auto-generated method stub
    }
}