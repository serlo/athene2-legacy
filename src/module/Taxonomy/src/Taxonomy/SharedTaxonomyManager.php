<?php
/**
 *
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @copyright 2013 by www.serlo.org
 * @license LGPL
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */
namespace Taxonomy;

use Doctrine\ORM\EntityManager;
use Core\Service\LanguageService;
use Taxonomy\Exception\NotFoundException;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Taxonomy\Factory\EntityTaxonomy;
use Taxonomy\Entity\Taxonomy;

class SharedTaxonomyManager implements ServiceLocatorAwareInterface, SharedTaxonomyManagerInterface
{

    protected $_instances = array();

    /**
     *
     * @var EntityManager
     */
    protected $_entityManager;

    protected $_serviceLocator;

    /**
     *
     * @var LanguageService
     */
    protected $_languageService;
    
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
     * @return LanguageService $_languageService
     */
    public function getLanguageService ()
    {
        return $this->_languageService;
    }

    /**
     *
     * @param LanguageService $_languageService            
     */
    public function setLanguageService (LanguageService $_languageService)
    {
        $this->_languageService = $_languageService;
        return $this;
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
     */
    public function setEntityManager (EntityManager $_entityManager)
    {
        $this->_entityManager = $_entityManager;
        return $this;
    }
    
    /*
     * (non-PHPdoc) @see \Taxonomy\SharedTaxonomyManagerInterface::add()
     */
    public function add ($name, TaxonomyManagerInterface $manager)
    {
        $this->_instances[$name] = $manager;
        return $this;
    }
    
    /*
     * (non-PHPdoc) @see \Taxonomy\SharedTaxonomyManagerInterface::get()
     */
    public function get ($name, $languageService = NULL)
    {
        if(is_string($name)){
            if (! isset($this->_instances[$name])) {
                $this->add($name, $this->_find($name, $languageService));
            }
            return $this->_instances[$name];
        } else if (is_object($name)) {
            if($name instanceof Taxonomy){
                $entity = $name;
                $name = $entity->getName();
                if (! isset($this->_instances[$name])) {
                    $this->add($name, $this->toService($entity));
                }
                return $this->_instances[$name];
            }
        }
        throw new \InvalidArgumentException();
    }
    
    private function toService(Taxonomy $entity){
            // TODO REMOVE ONCE FIXED BY ZF
        $this->getServiceLocator()->setShared('Taxonomy\TaxonomyManager', false);
        $tm = $this->getServiceLocator()->get('Taxonomy\TaxonomyManager');
        $tm->setEntity($entity);
        $tm->build();
        return $tm;
        
    }

    private function _find ($name, $languageService = NULL)
    {
        if ($languageService === NULL)
            $languageService = $this->getLanguageService();
        
        $entity = $this->getEntityManager()
            ->getRepository('Taxonomy\Entity\Taxonomy')
            ->findOneBy(array(
            'name' => $name,
            'language' => $languageService->getId()
        ));
            
        if ($entity == NULL)
            throw new NotFoundException('Taxonomy not found. Using name `' . $name . '` and language `' . $languageService->getId() . '`');
            
        return $this->toService($entity);
    }
}