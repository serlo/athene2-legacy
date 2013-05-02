<?php
/**
 *
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @copyright 2013 by www.serlo.org
 * @license LGPL
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */
namespace Taxonomy;

use Taxonomy\Service\TermServiceInterface;
use Taxonomy\Exception\BadTypeException;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\Collections\Criteria;
use Core\Entity\AbstractEntityAdapter;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Doctrine\Common\Collections\Collection;
use Core\Entity\EntityInterface;
use Taxonomy\Factory\FactoryInterface;

class TaxonomyManager extends AbstractEntityAdapter implements TaxonomyManagerInterface, ServiceLocatorAwareInterface
{

    /**
     *
     * @var EntityManager
     */
    protected $_entityManager;

    /**
     *
     * @var ServiceLocatorInterface
     */
    protected $_serviceLocator;

    protected $_terms = array();

    protected $_template;

    protected $_termTemplate;

    protected $_allowedLinks = array();

    /**
     *
     * @var FactoryInterface
     */
    protected $_factory;

    /**
     *
     * @var LanguageService
     */
    protected $_languageService;

    /**
     *
     * @return FactoryInterface
     */
    public function getFactory ()
    {
        return $this->_factory;
    }

    /**
     *
     * @param FactoryInterface $_factory            
     * @return $this
     */
    public function setFactory (FactoryInterface $_factory)
    {
        $this->_factory = $_factory;
        return $this;
    }

    public function __construct ($adaptee = NULL)
    {
        // echo "creating";
        $this->setAdaptee($adaptee);
    }
    
    /*
     * (non-PHPdoc) @see \Zend\ServiceManager\ServiceLocatorAwareInterface::setServiceLocator()
     */
    public function setServiceLocator (ServiceLocatorInterface $serviceLocator)
    {
        $this->_serviceLocator = $serviceLocator;
        return $this;
    }
    
    /*
     * (non-PHPdoc) @see \Zend\ServiceManager\ServiceLocatorAwareInterface::getServiceLocator()
     */
    public function getServiceLocator ()
    {
        return $this->_serviceLocator;
    }

    /**
     *
     * @return EntityManager $_entityManager
     */
    public function getEntityManager ()
    {
        return $this->_entityManager;
    }

    /**
     *
     * @param EntityManager $_entityManager            
     * @return $this
     */
    public function setEntityManager (EntityManager $_entityManager)
    {
        $this->_entityManager = $_entityManager;
        return $this;
    }
    
    /*
     * (non-PHPdoc) @see \Taxonomy\TaxonomyManagerInterface::addTerm()
     */
    public function addTerm (TermServiceInterface $ts)
    {
        $this->_terms[$ts->getId()] = $ts;
        return $this;
    }
    
    /*
     * (non-PHPdoc) @see \Taxonomy\TaxonomyManagerInterface::createTerm()
     */
    public function createTerm ()
    {
        // TODO Auto-generated method stub
        $ts = '';
        $this->addTerm($ts);
        return $this;
    }
    
    /*
     * (non-PHPdoc) @see \Taxonomy\TaxonomyManagerInterface::hasTerm()
     */
    public function hasTerm ($val)
    {
        // TODO do me
    }
    
    /*
     * (non-PHPdoc) @see \Taxonomy\TaxonomyManagerInterface::getTerm()
     */
    public function getTerm ($val)
    {
        if (is_numeric($val)) {
            $return = $this->_getTermById($val);
        } else 
            if (is_array($val)) {
                $return = $this->_getTermByPath($val);
            } else 
                if ($val instanceof EntityInterface) {
                    $return = $this->_getTermByEntity($val);
                } else {
                    throw new BadTypeException();
                }
        return $return;
    }

    public function getTermByLink ($targetField, EntityInterface $target)
    {
        // TODO
        // if(!$this->linkingAllowed($targetField))
        // throw new \Exception();
        
        // TODO check if linking multiple entities is allowed
        
        // TODO security stuff
        $query = $this->getEntityManager()->createQuery("
				SELECT taxonomy, terms FROM 
					" . get_class($this->getEntity()) . " taxonomy
					JOIN taxonomy.terms terms
					JOIN terms." . $targetField . " associations
				WHERE
					taxonomy.id = " . $this->getId() . "
				AND
					associations.id = " . $target->getId());
        
        // $query->setParameter(1, $this->getId());
        // $query->setParameter(2, $target->getId());
        
        return $this->_getTermByEntity(current($query->getResult())->get('terms')
            ->current());
    }

    protected function _getTermByEntity (EntityInterface $entity)
    {
        $id = $entity->getId();
        if (isset($this->_terms[$id])) {
            return $this->_terms[$id];
        }
        $service = $this->_entityToService($entity);
        $this->addTerm($service);
        return $service;
    }

    protected function _getTermById ($id)
    {
        if (isset($this->_terms[$id])) {
            return $this->_terms[$id];
        }
        $service = $this->_entityToService($this->get('terms')
            ->get($id));
        $this->addTerm($service);
        return $service;
    }

    protected function _getTermByPath (array $path)
    {}

    protected function _entitiesToServices (Collection $entities)
    {
        $return = array();
        foreach ($entities->toArray() as $entity) {
            $return[] = $this->_entityToService($entity);
        }
        return $return;
    }

    protected function _entityToService ($entity)
    {
        // TODO REMOVE
        $this->getServiceLocator()->setShared('Taxonomy\Service\TermService', false);
        
        $ts = $this->getServiceLocator()->get('Taxonomy\Service\TermService');
        $ts->setEntity($entity);
        return $ts;
    }

    public function build ()
    {
        // read factory class from db
        $factoryClassName = $this->getEntity()
            ->get('factory')
            ->get('className');
        if (substr($factoryClassName, 0, 1) != '\\') {
            $factoryClassName = '\\Taxonomy\\Factory\\' . $factoryClassName;
        }
        $factory = new $factoryClassName();
        if (! $factory instanceof FactoryInterface)
            throw new \Exception('Something somewhere went terribly wrong.');
        
        $factory->build($this);
        $this->setFactory($factory);
        
        return $this;
    }
    
    /*
     * (non-PHPdoc) @see \Taxonomy\TaxonomyManagerInterface::getTerms()
     */
    public function getTerms (Criteria $filter = NULL)
    {
        return $this->get('terms')->matching($filter);
    }
    
    /*
     * (non-PHPdoc) @see \Taxonomy\TaxonomyManagerInterface::toArray()
     */
    public function toArray ()
    {
        $this->getEntity()->toArray();
    }
    
    /*
     * (non-PHPdoc) @see \Taxonomy\TaxonomyManagerInterface::setTemplate()
     */
    public function setTemplate ($template)
    {
        $this->_template = $template;
        return $this;
    }
    
    /*
     * (non-PHPdoc) @see \Taxonomy\TaxonomyManagerInterface::setTermTemplate()
     */
    public function setTermTemplate ($template)
    {
        $this->_termTemplate = $template;
        return $this;
    }
    
    /*
     * (non-PHPdoc) @see \Taxonomy\TaxonomyManagerInterface::enableLink()
     */
    public function enableLink ($targetField,\Closure $callback)
    {
        $this->_allowedLinks[$targetField] = $callback;
        return $this;
    }
    
    /*
     * (non-PHPdoc) @see \Taxonomy\TaxonomyManagerInterface::linkingAllowed()
     */
    public function linkingAllowed ($targetField)
    {
        return isset($this->_allowedLinks[$targetField]);
    }
}