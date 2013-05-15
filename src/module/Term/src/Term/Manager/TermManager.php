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
namespace Term\Manager;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Taxonomy\Service\TermServiceInterface;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use Core\AbstractManager;
use Term\Exception\TermNotFoundException;

class TermManager extends AbstractManager implements ServiceLocatorAwareInterface, ObjectManagerAwareInterface, TermManagerInterface
{

    protected $defaultOptions = array(
        'instance' => array(
            'TermEntityInterface' => 'Term\Entity\Term',
            'manages' => 'Term\Service\TermServiceInterface'
        )
    );

    /**
     *
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    protected $objectManager;
    
    /*
     * (non-PHPdoc) @see \DoctrineModule\Persistence\ObjectManagerAwareInterface::getObjectManager()
     */
    public function getObjectManager()
    {
        return $this->objectManager;
    }
    
    /*
     * (non-PHPdoc) @see \DoctrineModule\Persistence\ObjectManagerAwareInterface::setObjectManager()
     */
    public function setObjectManager(\Doctrine\Common\Persistence\ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
        return $this;
    }

    /**
     *
     * @var \Zend\ServiceManager\ServiceLocatorInterface
     */
    protected $serviceLocator;
    
    /*
     * (non-PHPdoc) @see \Zend\ServiceManager\ServiceLocatorAwareInterface::getServiceLocator()
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }
    
    /*
     * (non-PHPdoc) @see \Zend\ServiceManager\ServiceLocatorAwareInterface::setServiceLocator()
     */
    public function setServiceLocator(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        return $this;
    }

    /**
     * 
     * @param TermServiceInterface $termService
     */
    public function add(TermServiceInterface $termService)
    {
        $this->addInstance($termService->getName(), $termService);
    }
    
    public function get($term)
    {
        if (is_numeric($term)) {
            $return = $this->getById($term);
        } else 
            if ($term instanceof TermServiceInterface) {
                $return = $this->getByService($term);
            } else 
                if (is_string($term)) {
                    $return = $this->getByString($term);
                } else {
                    throw new \InvalidArgumentException();
                }
        
        return $return;
    }

    protected function getById($id)
    {
        $entity = $this->getObjectManager()->find($this->resolve('TermEntityInterface'), $id);
        if (! is_object($entity))
            throw new TermNotFoundException($name);
        
        if(!$this->hasInstance($term->getName())){
            $this->add($this->createInstance($term));
        }
        
        return $this->getInstance($term->getName());
    }

    protected function getByService(TermServiceInterface $term)
    {
        if (! $this->hasInstance($term->getName())) {
            $this->add($term);
        }
        return $this->getInstance($name);
    }

    protected function getByString($name)
    {
        if (! $this->hasInstance($name)) {
            $entity = $this->getObjectManager()
                ->getRepository($this->resolve('TermServiceInterface'))
                ->findOneBy(array(
                'name' => $name
            ));
            if (! is_object($entity)) {
                $entity = $this->getObjectManager()
                    ->getRepository($this->resolve('TermServiceInterface'))
                    ->findOneBy(array(
                    'slug' => $name
                ));
            }
            if (! is_object($entity))
                throw new TermNotFoundException($name);
            $this->add($this->createInstance($entity));
        }
        return $this->getInstance($name);
    }

    protected function createInstance($entity)
    {
        $instance = parent::createInstance();
        $instance->setEntity($entity);
        return $instance;
    }
}