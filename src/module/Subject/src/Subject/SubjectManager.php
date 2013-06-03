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
namespace Subject;

use Subject\Service\SubjectServiceInterface;
use Zend\ServiceManager\FactoryInterface;
use Core\AbstractManager;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;

class SubjectManager extends AbstractManager implements SubjectManagerInterface, ObjectManagerAwareInterface
{    
    /**
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    protected $objectManager;
    
    protected $options = array(
        'instances' => array(
            'manages' => 'Subject\Service\SubjectService',
            'SubjectEntityInterface' => 'Subject\Entity\Subject'
        ),
    );
    
    public function __construct(){
        parent::__construct($this->options);
    }
    
    /* (non-PHPdoc)
     * @see \DoctrineModule\Persistence\ObjectManagerAwareInterface::getObjectManager()
     */
    public function getObjectManager ()
    {
        return $this->objectManager;
    }

	/* (non-PHPdoc)
     * @see \DoctrineModule\Persistence\ObjectManagerAwareInterface::setObjectManager()
     */
    public function setObjectManager (\Doctrine\Common\Persistence\ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
        return $this;
    }

	public function add (SubjectServiceInterface $service)
    {
        $this->addInstance($service->getName(), $service);
        return $service->getName();
    }
    
    public function get ($subject)
    {
        $array = $this->getInstances();
        if(empty($array)){
            $this->injectInstances();
        }
        return $this->getInstance($subject);
    }
    
    public function getAllSubjects(){
        if(empty($array)){
            $this->injectInstances();
        }
        return $this->getInstances();
    }

	public function has($subject){
        return $this->hasInstance($subject);
    }
    
    private function injectInstances(){
        $em = $this->getObjectManager();
        $entities = $em->getRepository($this->resolve('SubjectEntityInterface'))->findAll();
        foreach($entities as $entity){
            $this->add($this->createInstance($entity));
        }
        return $this;
    }
    
    public function getSubjectFromRequest(){
        return $this->get('math');
    }
    
    protected function createInstance($entity){
        $instance = parent::createInstance();
        $instance->setEntity($entity);
        $instance = $instance->build();
        return $instance;
    }
}