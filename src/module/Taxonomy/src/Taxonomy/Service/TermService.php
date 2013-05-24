<?php
/**
 *
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @copyright 2013 by www.serlo.org
 * @license LGPL
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */
namespace Taxonomy\Service;

use Core\Entity\EntityInterface;;
use Taxonomy\Factory\FactoryInterface;
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
        return $this->getTermManager()->get($this->getEntity()
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

    protected function linkAllowedWithException ($targetField)
    {
        if (! $this->linkAllowed($targetField))
            throw new LinkNotAllowedException();
    }

    public function getAllowedLinks()
    {
        return $this->allowedLinks;
    }
    
    /*
     * (non-PHPdoc) @see \Taxonomy\TaxonomyManagerInterface::enableLink()
     */
    public function enableLink($targetField,\Closure $callback)
    {
        $this->allowedLinks[$targetField] = $callback;
        return $this;
    }
    
    /*
     * (non-PHPdoc) @see \Taxonomy\TaxonomyManagerInterface::linkingAllowed()
     */
    public function linkAllowed($targetField)
    {
        return isset($this->allowedLinks[$targetField]);
    }

    public function build()
    {
        // read factory class from db
        $factoryClassName = $this->getEntity()->getFactory();
        
        if (! $factoryClassName)
            throw new \Exception('Factory not set');
        
        $factoryClassName = $factoryClassName->get('className');
        if (substr($factoryClassName, 0, 1) != '\\') {
            $factoryClassName = '\\Taxonomy\\Factory\\' . $factoryClassName;
        }
        
        if (! class_exists($factoryClassName))
            throw new \Exception('Something somewhere went terribly wrong.');
        
        $factory = new $factoryClassName();
        if (! $factory instanceof FactoryInterface)
            throw new \Exception('Something somewhere went terribly wrong.');
        
        $factory->build($this);
        $this->setFactory($factory);
        
        return $factory;
    }
}