<?php
/**
 *
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @copyright 2013 by www.serlo.org
 * @license LGPL
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */
namespace Taxonomy\Service;

use Core\Entity\EntityInterface;
use Taxonomy\Exception\LinkNotAllowedException;
use Core\Service\AbstractEntityDecorator;
use Taxonomy\TermManagerAwareInterface;

class TermService extends AbstractEntityDecorator implements TermServiceInterface, TermManagerAwareInterface
{
    /**
     * 
     * @var \Taxonomy\TermManagerInterface
     */
    protected $termManager;
    
    /* (non-PHPdoc)
     * @see \Taxonomy\TermManagerAwareInterface::getTermManager()
     */
    public function getTermManager ()
    {
        return $this->termManager;
    }

	/* (non-PHPdoc)
     * @see \Taxonomy\TermManagerAwareInterface::setTermManager()
     */
    public function setTermManager (\Taxonomy\TermManagerInterface $termManager)
    {
        $this->termManager = $termManager;
        return $this;
    }

	/*
     * (non-PHPdoc) @see \Taxonomy\Service\TermServiceInterface::getParent()
     */
    public function getParent ()
    {
        return $this->getTermManager()->get($this->getEntity()->etParent());
    }
    
    /*
     * (non-PHPdoc) @see \Taxonomy\Service\TermServiceInterface::getChildren()
     */
    public function getChildren ()
    {
        return $this->getTaxonomyManager()->get($this->getEntity()
            ->get('children'));
    }
    
    /*
     * (non-PHPdoc) @see \Taxonomy\Service\TermServiceInterface::getAllLinks()
     */
    public function getAllLinks ()
    {
        $return = array();
        foreach ($this->getAllowedLinks() as $targetField => $callback) {
            $return[$targetField] = $this->getLinks($targetField);
        }
        return $return;
    }
    
    /*
     * (non-PHPdoc) @see \Taxonomy\Service\TermServiceInterface::getLinks()
     */
    public function getLinks ($targetField)
    {
        $this->linkAllowedWithException($targetField);
        $links = $this->getAllowedLinks();
        $services = array();
        foreach ($this->get($targetField)->toArray() as $entity) {
            $service[] = $links[$targetField]($entity);
        }
        return $service;
    }
    
    /*
     * (non-PHPdoc) @see \Taxonomy\Service\TermServiceInterface::addLink()
     */
    public function addLink ($targetField, EntityInterface $target)
    {
        $this->linkAllowedWithException($targetField);
        $entity = $this->getEntity();
        $entity->get($targetField)->add($target);
        $this->persist();
        return $this;
    }
    
    /*
     * (non-PHPdoc) @see \Taxonomy\Service\TermServiceInterface::removeLink()
     */
    public function removeLink ($targetField, EntityInterface $target)
    {
        $this->linkAllowedWithException($targetField);
        $entity = $this->getEntity();
        $entity->get($targetField)->remove($target);
        $this->persist();
        return $this;
    }

    public function hasLink ($targetField, EntityInterface $target)
    {
        $this->linkAllowedWithException($targetField);
        $entity = $this->getEntity();
        return $entity->get($targetField)->containsKey($target->getId());
    }

    public function linkAllowed ($targetField)
    {
        return $this->getTermManager()->linkAllowed($targetField);
    }

    protected function linkAllowedWithException ($targetField)
    {
        if (! $this->linkAllowed($targetField))
            throw new LinkNotAllowedException();
    }
}