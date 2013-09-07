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
use Taxonomy\Exception\ErrorException;

class TermManager extends AbstractManager implements TermManagerInterface
{
    use \Common\Traits\ObjectManagerAwareTrait, \Common\Traits\EntityDelegatorTrait,\Uuid\Manager\UuidManagerAwareTrait,\Taxonomy\Manager\SharedTaxonomyManagerAwareTrait,\Term\Manager\TermManagerAwareTrait;

    protected $config = array(
        'options' => array(
            'templates' => array('update' => 'taxonomy/taxonomy/update'),
            'allowed_parents' => array(),
            'allowed_links' => array(),
            'radix_enabled' => true
        )
    );

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
        return new TermCollection($this->getEntity()->getTerms(), $this->getSharedTaxonomyManager());
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
            $name = $term;
            $entity = $this->getObjectManager()->find($this->resolveClassName('Taxonomy\Entity\TermTaxonomyEntityInterface'), (int) $term);
        } elseif (is_string($term)){
            $name = $term;
            $term = $this->getTermManager()->get($term);   
            $entity = $this->getObjectManager()->getRepository($this->resolveClassName('Taxonomy\Entity\TermTaxonomyEntityInterface'))->findOneBy(array('term' => $term->getId(), 'taxonomy' => $this->getEntity()->getId()));
        } elseif (is_array($term)) {
            $name = implode(', ',  $term);
            $entity = $this->getEntityByPath($term);
        } elseif ($term instanceof \Term\Entity\TermEntityInterface || $term instanceof \Term\Service\TermServiceInterface) {
            $entity = $this->getObjectManager()->getRepository($this->resolveClassName('Taxonomy\Entity\TermTaxonomyEntityInterface'))->findOneBy(array('term' => $term->getId(), 'taxonomy' => $this->getEntity()->getId()));
        } elseif ($term instanceof TermTaxonomyEntityInterface) {
            $name = explode(', ', $term->getName());
            $entity = $term;
        } elseif ($term instanceof \Taxonomy\Service\TermServiceInterface) {
            $name = $term->getName();
            $entity = $term;
            $id = $this->add($entity);
            return $this->getInstance($id);
        } else {
            throw new \InvalidArgumentException();
        }
        
        if(!$entity instanceof TermTaxonomyEntityInterface){
            throw new NotFoundException(sprintf("Term not found, `%s` does not implement TermTaxonomyEntityInterface. Additional information: Taxonomy (%s), Name (%s)",get_class($entity), $this->getEntity()->getName(), $name));//sprintf('Term %s not found', $term));
        }
            
        if(!$this->hasInstance($entity->getId())){
            $id = $this->add($this->createInstanceFromEntity($entity));
        }
        
        return $this->getInstance($entity->getId());
    }

    protected function getEntityByPath(array $path){
        if (!count($path))
            throw new \InvalidArgumentException('Path requires at least one element');
        $terms = $this->getRootTermEntities();
        foreach($path as $element){
            if(is_string($element) && strlen($element) > 0){
                foreach($terms as $term){
                    $found = false;
                    if(strtolower($term->getSlug()) == strtolower($element)){
                        $terms = $term->getChildren();
                        $found = $term;
                        break;
                    }
                }
            }
        }
        if(!is_object($found))
            throw new \Exception("Not found");
        
        return $found;
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
    
    protected function getRootTermEntities($type = NULL) {
        //return $this->getEntity()->getTerms()->matching(Criteria::create(Criteria::expr()->orX(Criteria::expr()->isNull('parent'), Criteria::expr()->andX(Criteria::expr()->neq('parent', NULL), Criteria::expr()->neq('parent', $this->getEntity->)))));
        if($type)
            $type = $this->getSharedTaxonomyManager()->get($type);
        
        $collection = new ArrayCollection();
        $terms = $this->getEntity()->getTerms();
        foreach ($terms as $entity) {
            if ((!$type || ($type && $entity->getTaxonomy() === $type->getEntity())) && (! $entity->hasParent() || ($entity->hasParent() && $entity->getParent()->getTaxonomy() !== $this->getEntity()) )){
                $collection->add($entity);
            }
        }
        return $collection;
    }

    public function getRootTerms($type = NULL)
    {
        //return new TermCollection($this->getRootTermEntities(), $this->getManager());
        /*$collection = new ArrayCollection();
        foreach ( as $entity) {
            if (! $entity->hasParent() || ($entity->hasParent() && $entity->getParent()->getTaxonomy() !== $this->getEntity()) ){
                $collection->add($this->createInstanceFromEntity($entity));
            }
        }*/
        $collection = $this->getRootTermEntities($type);
        return new TermCollection($collection, $this->getSharedTaxonomyManager());
    }

    public function createInstanceFromEntity(TermTaxonomyEntityInterface $entity)
    {
        $instance = $this->createInstance('Taxonomy\Service\TermServiceInterface');
        $instance->setEntity($entity);
        if($entity->getTaxonomy() !== $this->getEntity()){
            $instance->setManager($this->getSharedTaxonomyManager()->get($entity->getTaxonomy()->getName()));
        } else {
            $instance->setManager($this);            
        }
        return $instance;
    }

    public function getConfig()
    {
        return $this->config;
    }

    public function setConfig(array $config)
    {
        $this->config = array_merge_recursive($this->config, $config);
        return $this;
    }
    
    public function allowsParentType($type){
        return in_array($type, $this->getOptions()['allowed_parents']); // disabled: || $type == $this->getEntity()->getName();
    }
    
    public function getOptions ()
    {
        $config = $this->getConfig();
        if (! array_key_exists('options', $config))
            throw new ErrorException('No options set');
        
        return $config['options'];
    }
}