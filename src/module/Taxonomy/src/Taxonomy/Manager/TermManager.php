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
namespace Taxonomy\Manager;

use Doctrine\Common\Collections\Criteria;
use Taxonomy\Entity\TermTaxonomyEntityInterface;
use Taxonomy\Exception\NotFoundException;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use Doctrine\Common\Collections\ArrayCollection;
use Taxonomy\Service\TermServiceInterface;
use Taxonomy\Collection\TermCollection;

class TermManager extends AbstractManager implements TermManagerInterface
{
    use \Common\Traits\ObjectManagerAwareTrait, \Common\Traits\EntityDelegatorTrait,\Uuid\Manager\UuidManagerAwareTrait,\Taxonomy\Manager\SharedTaxonomyManagerAwareTrait,\Term\Manager\TermManagerAwareTrait;

    protected $options = array();

    public function __construct($options = array())
    {
        $this->options = array_merge_recursive($this->options, $options);
    }

    public function getId()
    {
        return $this->getEntity()->getId();
    }

    public function getType()
    {
        return $this->getEntity()->getType();
    }

    public function setType($type)
    {
        return $this->getEntity()->setType($type);
    }

    public function getTerms()
    {
        return $this->getEntity()->getTerms();
    }

    public function setTerms($terms)
    {
        return $this->getEntity()->setTerms($terms);
    }
    
    /*
     * (non-PHPdoc) @see \Term\Manager\TermManagerAwareInterface::getTermManager()
     */
    public function getManager()
    {
        return $this->getSharedTaxonomyManager();
    }

    public function get($term)
    {
        if (is_numeric($term)) {
            $entity = $this->getObjectManager()->find($this->resolveClassName('Taxonomy\Entity\TermEntityInterface'), (int) $term);
        } elseif (is_string($term)){
            $term = $this->getTermManager()->get($term);
            $criteria = Criteria::create()->where(Criteria::expr()->eq("term", $term->getId()))
                ->setMaxResults(1);
            $entity = $this->getTerms()
                ->matching($criteria)
                ->first();
        } elseif (is_array($term)) {
            $entity = $this->getEntityByPath($term);
        } elseif ($term instanceof \Term\Entity\TermEntityInterface || $term instanceof \Term\Service\TermServiceInterface) {
            $criteria = Criteria::create()->where(Criteria::expr()->eq("term", $term->getId()))
                ->setMaxResults(1);
            $entity = $this->getTerms()
                ->matching($criteria)
                ->first();
        } elseif ($term instanceof TermTaxonomyEntityInterface) {
            $entity = $this->createInstanceFromEntity($term);
        } elseif ($term instanceof \Taxonomy\Service\TermServiceInterface) {
            $entity = $term;
        /*} elseif ($term instanceof Collection) {
            $return = array();
            foreach ($term as $entity) {
                $return[] = $this->get($entity);
            }
            return $return;*/
        } else {
            throw new \InvalidArgumentException();
        }
        
        if(!is_object($entity))
            throw new NotFoundException();//sprintf('Term %s not found', $term));
        
        $id = $this->add($this->createInstanceFromEntity($entity));
        return $this->getInstance($id);
    }

    protected function getEntityByPath(array $path){
        if (!count($path))
            throw new \InvalidArgumentException('Path requires at least one element');
        $terms = $this->getRootTermEntities();
        foreach($path as $element){
            foreach($terms as $term){
                $found = false;
                if(strtolower($term->getSlug()) == strtolower($element)){
                    $terms = $term->getChildren();
                    $found = $term;
                    break;
                }
            }
        }
        if(!is_object($found))
            throw new \Exception("Not found");
        
        return $found;//->getEntity();
    }

    public function create(array $data)
    {
        $entity = $this->resolve('Term\Entity\TermTaxonomyEntityInterface');
        
        $term = $this->getTermManager()->get($data['term']['name']);
        
        $entity->setTerm($term->getEntity());
        unset($data['term']);
        
        $hydrator = new DoctrineObject($this->getObjectManager(), $this->resolveClassName('Term\Entity\TermTaxonomyEntityInterface'));
        
        // don't change this
        $entity = $hydrator->hydrate($data, $entity);
        $this->getUuidManager()->inject($entity);
        // hydrate sets uuid to NULL !
        
        $this->getObjectManager()->persist($entity);
        $this->getObjectManager()->flush();
        
        $instance = $this->createInstanceFromEntity($entity);
        return $instance;
    }

    public function delete($term)
    {
        $id = $term->getId();
        $this->getObjectManager()->remove($term->getEntity());
        $this->getObjectManager()->flush();
        $this->removeInstance($id);
        unset($term);
        return $this;
    }

    public function add(\Taxonomy\Service\TermServiceInterface $termService)
    {
        $this->addInstance($termService->getId(), $termService);
        return $termService->getId();
    }
    
    protected function getRootTermEntities() {
        $collection = new ArrayCollection();
        foreach ($this->getEntity()->getTerms() as $entity) {
            if (! $entity->hasParent() || $entity->getParent()->getTaxonomy() !== $this->getEntity()) {
                $collection->add($entity);
            }
        }
        return $collection;
    }

    public function getRootTerms()
    {
        //return new TermCollection($this->getRootTermEntities(), $this->getManager());
        $collection = new ArrayCollection();
        foreach ($this->getEntity()->getTerms() as $entity) {
            if (! $entity->hasParent() || $entity->getParent()->getTaxonomy() !== $this->getEntity()) {
                $collection->add($this->createInstanceFromEntity($entity));
            }
        }
        return $collection;
    }

    public function createInstanceFromEntity(TermTaxonomyEntityInterface $entity)
    {
        $instance = $this->createInstance('Taxonomy\Service\TermServiceInterface');
        $instance->setEntity($entity);
        $instance->setManager($this);
        $instance->setOptions($this->options);
        return $instance;
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function setOptions($options)
    {
        $this->options = $options;
        return $this;
        return $this;
    }
}