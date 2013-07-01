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
namespace Taxonomy;

use Core\AbstractManagerAndEntityDecorator;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Collection;
use Taxonomy\Factory\FactoryInterface;
use Taxonomy\Entity\TermTaxonomyEntityInterface;
use Taxonomy\Exception\NotFoundException;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use Uuid\Manager\UuidManagerAware;

class TermManager extends AbstractManagerAndEntityDecorator implements \Term\Manager\TermManagerAwareInterface, TermManagerInterface, UuidManagerAware
{
    
    protected $uuidManager;
    
	/* (non-PHPdoc)
     * @see \Uuid\Manager\UuidManagerAware::getUuidManager()
     */
    public function getUuidManager ()
    {
        return $this->uuidManager;
    }

	/* (non-PHPdoc)
     * @see \Uuid\Manager\UuidManagerAware::setUuidManager()
     */
    public function setUuidManager (\Uuid\Manager\UuidManagerInterface $manager)
    {
        $this->uuidManager = $manager;
        return $this;
    }
    
    /**
     *
     * @var \Term\Manager\TermManagerInterface
     */
    protected $termManager;

    protected $allowedLinks = array();

    /**
     *
     * @var FactoryInterface
     */
    protected $factory;

    protected $options = array(
        'instances' => array(
            'manages' => 'Taxonomy\Service\TermService',
            'TermEntityInterface' => 'Taxonomy\Entity\TermTaxonomy'
        )
    );

    public function __construct ()
    {
        parent::__construct($this->options);
    }
    
    /*
     * (non-PHPdoc) @see \Term\Manager\TermManagerAwareInterface::getTermManager()
     */
    public function getTermManager ()
    {
        return $this->termManager;
    }
    
    /*
     * (non-PHPdoc) @see \Term\Manager\TermManagerAwareInterface::setTermManager()
     */
    public function setTermManager (\Term\Manager\TermManagerInterface $termManager)
    {
        $this->termManager = $termManager;
        return $this;
    }

    public function get ($term)
    {
        if (is_numeric($term)) {
            $entity = $this->getObjectManager()->find($this->resolve('TermEntityInterface'), (int) $term);
            $id = $this->add($this->createInstanceFromEntity($entity));
        } elseif (is_array($term)) {
            $id = $this->add($this->createInstanceFromEntity($this->getEntityByPath($term)));
        } elseif ($term instanceof \Term\Entity\TermEntityInterface || $term instanceof \Term\Service\TermServiceInterface) {
            $criteria = Criteria::create()->where(Criteria::expr()->eq("term", $term->getId()))
                ->setMaxResults(1);
            $entity = $this->getTerms()
                ->matching($criteria)
                ->first();
            $id = $this->add($this->createInstanceFromEntity($entity));
        } elseif ($term instanceof TermTaxonomyEntityInterface) {
            $id = $this->add($this->createInstanceFromEntity($term));
        } elseif ($term instanceof \Taxonomy\Service\TermServiceInterface) {
            $id = $this->add($term);
        } elseif ($term instanceof Collection) {
            $return = array();
            foreach ($term as $entity) {
                $return[] = $this->get($entity);
            }
            return $return;
        } else {
            throw new \InvalidArgumentException();
        }
        return $this->getInstance($id);
    }

    protected function getEntityByPath (array $path)
    {
        if (! isset($path[0]))
            throw new \InvalidArgumentException('Path requires at least one element');
        
        $i = 0;
        $join = "";
        $where = "";
        $select = array();
        $root = $path[0];
        unset($path[0]);
        foreach ($path as $element) {
            $i ++;
            $y = $i - 1;
            $select[] = "termTaxonomy{$i}";
            $join .= " JOIN termTaxonomy{$y}.children termTaxonomy{$i}
                      JOIN termTaxonomy{$i}.term term{$i}\n";
            $where .= " AND term{$i}.slug = '" . $element . "'
                      AND termTaxonomy{$i}.parent = termTaxonomy{$y}.id";
        }
        if (count($path)) {
            $select = array_reverse($select);
            $select = ", " . implode(", ", $select);
        } else {
            $select = '';
        }
        $query = "
				SELECT taxonomy, termTaxonomy0, term0{$select} FROM 
					" . get_class($this->getEntity()) . " taxonomy
					JOIN taxonomy.terms termTaxonomy0
					JOIN termTaxonomy0.term term0
                    " . $join . "
				WHERE
					taxonomy.id = " . $this->getId() . "
				AND term0.slug = '" . $root . "'
					" . $where . "";
        $query = $this->getObjectManager()
            ->createQuery($query)
            ->setMaxResults(1);
        
        $result = current($query->getResult());
        
        if (! is_object($result))
            throw new NotFoundException();
        
        $result = $result->getTerms()->first();
        for ($x = 1; $x <= $i; $x ++) {
            $result = $result->getChildren()->first();
        }
        return $result;
    }

    public function create (array $data)
    {
        $entity = $this->resolve('TermEntityInterface', true);
        
        $term = $this->getTermManager()->get($data['term']['name']);
        
        $entity->setTerm($term->getEntity());
        unset($data['term']);
        
        $hydrator = new DoctrineObject(
            $this->getObjectManager(),
            $this->resolve('TermEntityInterface')
        );
        
        // don't change this
        $entity = $hydrator->hydrate($data, $entity);
        $this->getUuidManager()->inject($entity);
        // hydrate sets uuid to NULL !
        
        $this->getObjectManager()->persist($entity);
        $this->getObjectManager()->flush();
        
        $instance = $this->createInstanceFromEntity($entity);
        return $instance;
    }
    
    public function delete($term){
        $id = $term->getId();
        $this->getObjectManager()->remove($term->getEntity());
        $this->getObjectManager()->flush();
        $this->removeInstance($id);
        unset($term);
        return $this;
    }

    public function add (\Taxonomy\Service\TermServiceInterface $termService)
    {
        $this->addInstance($termService->getId(), $termService);
        return $termService->getId();
    }
    
    public function getRootTerms(){
        $return = array();
        foreach($this->getEntity()->getTerms() as $entity){
            if(!$entity->hasParent())
                $return[] = $this->createInstanceFromEntity($entity);
        }
        return $return;
    }
    
    public function createInstanceFromEntity (TermTaxonomyEntityInterface $entity)
    {
        $instance = $this->createInstance();
        $instance->setEntity($entity);
        $instance->setManager($this);
        return $instance->build();
    }
}