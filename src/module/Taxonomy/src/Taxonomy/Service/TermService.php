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
use Taxonomy\Factory\FactoryInterface;
use Taxonomy\Exception\LinkNotAllowedException;
use Core\Service\AbstractEntityDecorator;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Core\Structure\DecoratorInterface;
use Taxonomy\Exception\InvalidArgumentException;
use Term\Manager\TermManagerInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Taxonomy\Entity\TermTaxonomyEntityInterface;
use Uuid\Manager\UuidManagerAware;

class TermService extends AbstractEntityDecorator implements TermServiceInterface, ServiceLocatorAwareInterface
{

    /**
     * @var TermManagerInterface
     */
    protected $termManager;
    
    /**
     *
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     *
     * @var \Taxonomy\TermManagerInterface
     */
    protected $manager;
    
	/**
     * @return \Term\Manager\TermManagerInterface $termManager
     */
    public function getTermManager ()
    {
        return $this->termManager;
    }

	/**
     * @param \Term\Manager\TermManagerInterface $termManager
     * @return $this
     */
    public function setTermManager (TermManagerInterface $termManager)
    {
        $this->termManager = $termManager;
        return $this;
    }

	/*
     * (non-PHPdoc) @see \Taxonomy\TermManagerAwareInterface::getTermManager()
     */
    public function getManager ()
    {
        return $this->manager;
    }
    
    /*
     * (non-PHPdoc) @see \Taxonomy\TermManagerAwareInterface::setTermManager()
     */
    public function setManager (\Taxonomy\TermManagerInterface $termManager)
    {
        $this->manager = $termManager;
        return $this;
    }
    
    /*
     * (non-PHPdoc) @see \Taxonomy\Service\TermServiceInterface::getParent()
     */
    public function getParent ()
    {
        return $this->getManager()->get($this->getEntity()
            ->getParent());
    }
    
    /*
     * (non-PHPdoc) @see \Taxonomy\Service\TermServiceInterface::getChildren()
     */
    public function getChildren ()
    {
        return $this->getManager()->get($this->getEntity()
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
        $services = new ArrayCollection();
        $closure = $links[$targetField];
        foreach ($this->get($targetField) as $entity) {
            $get = $closure($entity);
            if ($get !== NULL){
                $services->add($get);
            }
        }
        return $services;
    }
    
    /*
     * (non-PHPdoc) @see \Taxonomy\Service\TermServiceInterface::addLink()
     */
    public function addLink ($targetField, $target)
    {
        $target = $this->findEntity($target);
        $this->linkAllowedWithException($targetField);
        $entity = $this->getEntity();
        $entity->get($targetField)->add($target);
        $this->persist();
        return $this;
    }
    
    /*
     * (non-PHPdoc) @see \Taxonomy\Service\TermServiceInterface::removeLink()
     */
    public function removeLink ($targetField, $target)
    {
        $target = $this->findEntity($target);
        $this->linkAllowedWithException($targetField);
        $entity = $this->getEntity();
        $entity->get($targetField)->remove($target);
        $this->persist();
        return $this;
    }

    private function findEntity ($target)
    {
        if ($target instanceof EntityInterface) {
            return $target;
        } elseif ($target instanceof DecoratorInterface) {
            if ($target->providesMethod('getEntity')) {
                return $target->getEntity();
            }
        }
        throw new InvalidArgumentException();
    }

    public function hasLink ($targetField, $target)
    {
        $target = $this->findEntity($target);
        $this->linkAllowedWithException($targetField);
        $entity = $this->getEntity();
        return $entity->get($targetField)->containsKey($target->getId());
    }

    protected function linkAllowedWithException ($targetField)
    {
        if (! $this->linkAllowed($targetField))
            throw new LinkNotAllowedException();
    }

    public function getAllowedLinks ()
    {
        return $this->allowedLinks;
    }
    
    /*
     * (non-PHPdoc) @see \Taxonomy\TaxonomyManagerInterface::enableLink()
     */
    public function enableLink ($targetField,\Closure $callback)
    {
        $this->allowedLinks[$targetField] = $callback;
        return $this;
    }
    
    /*
     * (non-PHPdoc) @see \Taxonomy\TaxonomyManagerInterface::linkingAllowed()
     */
    public function linkAllowed ($targetField)
    {
        return isset($this->allowedLinks[$targetField]);
    }

    public function build ()
    {
        // read factory class from db
        $factoryClassName = $this->getEntity()->getFactory();
        
        if (! $factoryClassName)
            throw new \Exception('Factory not set');
        
        $factoryClassName = $factoryClassName->getName();
        
        if (! class_exists($factoryClassName))
            throw new \Exception("Clas `{$factoryClassName}` not found");
        
        $factory = new $factoryClassName();
        if (! $factory instanceof FactoryInterface)
            throw new \Exception('Something somewhere went terribly wrong.');
        
        return $factory->build($this);
    }
    
    /*
     * (non-PHPdoc) @see \Zend\ServiceManager\ServiceLocatorAwareInterface::setServiceLocator()
     */
    public function setServiceLocator (\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        return $this;
    }
    
    /*
     * (non-PHPdoc) @see \Zend\ServiceManager\ServiceLocatorAwareInterface::getServiceLocator()
     */
    public function getServiceLocator ()
    {
        return $this->serviceLocator;
    }

    public function update (array $data)
    {
        $merged = array_merge(array(
            'term' => array(
                'name' => $this->getName()
            ),
            'parent' => $this->getParent(),
            'taxonomy' => $this->getTaxonomy()
        ), $data);
        
        $this->setName($data['term']['name']);
        unset($data['term']);
        try {
            $this->populate($data);
        } catch (\Core\Exception\UnknownPropertyException $e) {}
        $this->persistAndFlush();
        return $this;
    }

    public function setName ($name)
    {
        $term = $this->getTermManager()->get($name);
        $this->getEntity()->set('term', $term->getEntity());
        return $this;
    }
    
    protected $allowedParentFactories = array();
    protected $allowedParentTaxonomy = array();
    protected $radix = false;
    
    public function parentNodeAllowed(TermTaxonomyEntityInterface $term){
        return array_key_exists($term->getTaxonomy()->getId(), $this->allowedParentTaxonomy) ||
               array_key_exists($term->getTaxonomy()->getFactory()->getId(), $this->allowedParentFactories);
    }
    
    public function allowParentNodesByFactory($id){
        $this->allowedParentFactories[$id] = true;
        return $this;
    }
    
    public function allowParentNodesByTaxonomy($id){
        $this->allowedParentTaxonomy[$id] = true;
        return $this;
    }
    
    public function radixEnabled(){
        return $this->radix;
    }
    
    public function enableRadix(){
        $this->radix = true;
        return $this;
    }
    
    public function disableRadix(){
        $this->radix = false;
        return $this;
    }
    
    public function setParent ($parent)
    {
        $entity = $this->getEntity();
        if($parent == NULL){
            if($this->radixEnabled()){
                $entity->setParent($parent);
            } else {
                throw new InvalidArgumentException('Radix not allowed.');
            }
        } else {
            if($this->parentNodeAllowed($parent)){
                $entity->setParent($parent);                
            } else {
                throw new InvalidArgumentException('Parent `'.$parent->getId().'` not allowed for `'.$entity->getId().'`.');
            }
        }
    }

}