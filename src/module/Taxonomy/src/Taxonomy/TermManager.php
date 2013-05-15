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

use Core\AbstractManager;
use Core\AbstractManagerAndEntityDecorator;
use Term\Manager\TermManagerAwareInterface;
use Core\Entity\EntityInterface;
use Taxonomy\Entity\TaxonomyEntityInterface;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Collection;
use Taxonomy\Factory\FactoryInterface;

class TermManager extends AbstractManagerAndEntityDecorator implements \Term\Manager\TermManagerAwareInterface, TermManagerInterface
{

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
            'manages' => 'Term\Service\TermServiceInterface',
            'TermEntityInterface' => 'Term\Entity\TermTaxonomy'
        )
    );
    

    public function __construct(){
        parent::__construct($this->options);
    }
    
    /*
     * (non-PHPdoc) @see \Term\Manager\TermManagerAwareInterface::getTermManager()
     */
    public function getTermManager()
    {
        return $this->termManager;
    }
    
    /*
     * (non-PHPdoc) @see \Term\Manager\TermManagerAwareInterface::setTermManager()
     */
    public function setTermManager(\Term\Manager\TermManagerInterface $termManager)
    {
        $this->termManager = $termManager;
        return $this;
    }

    public function get($term)
    {
        if (is_numeric($term)) {
            $criteria = Criteria::create()->where(Criteria::expr()->eq("id", $term))
                ->setMaxResults(1);
            $entity = $this->getTerms()
                ->matching($criteria)
                ->first();
            $id = $this->add($this->createInstance($entity));
        } elseif (is_array($term)) {
            $id = $this->add($this->createInstance($this->getEntityByPath($term)));
        } elseif ($term instanceof \Term\Entity\TermEntityInterface || $term instanceof \Term\Service\TermServiceInterface) {
            $criteria = Criteria::create()->where(Criteria::expr()->eq("term", $term->getId()))
                ->setMaxResults(1);
            $entity = $this->getTerms()
                ->matching($criteria)
                ->first();
            $id = $this->add($this->createInstance($entity));
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

    protected function getTermByPath(array $path)
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
            $join .= "JOIN termTaxonomy{$y}.children termTaxonomy{$i}
                      JOIN termTaxonomy{$i}.term term{i}\n";
            $where .= "AND term{$i}.slug = '" . $element . "'
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
        $query = $this->getEntityManager()
            ->createQuery($query)
            ->setMaxResults(1);
        
        echo $query->getSQL();
        die(TermManager);
        
        $result = current($query->getResult());
        
        if (! is_object($result))
            throw new NotFoundException();
        
        $result = $result->getTerms()->first();
        for ($x = 1; $x <= $i; $x ++) {
            $result = $result->getChildren()->first();
        }
        return $result;
    }

    public function add(\Taxonomy\Service\TermServiceInterface $termService)
    {
        $this->addInstance($termService->getId(), $termService);
        return $termService->getId();
    }

    public function createInstance(TaxonomyEntityInterface $entity)
    {
        $instance = parent::construct();
        $instance->setENtity($entity);
        return $instance;
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
        
        return $this;
    }
}