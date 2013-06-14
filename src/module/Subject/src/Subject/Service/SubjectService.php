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
namespace Subject\Service;

use Core\Service\AbstractEntityDecorator;
use Taxonomy\SharedTaxonomyManagerAwareInterface;
use Subject\SubjectManagerAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use Entity\EntityManagerAwareInterface;
use Doctrine\Common\Collections\Criteria;
use Subject\Entity\SubjectEntityInterface;

class SubjectService extends AbstractEntityDecorator implements SubjectServiceInterface, SharedTaxonomyManagerAwareInterface, SubjectManagerAwareInterface, ServiceLocatorAwareInterface, ObjectManagerAwareInterface, EntityManagerAwareInterface
{
    protected $subjectManager;
    
    protected $sharedTaxonomyManager;
    
    protected $serviceLocator;
    
    protected $entityManager;
    
	/* (non-PHPdoc)
     * @see \Subject\SubjectManagerAwareInterface::getSubjectManager()
     */
    public function getSubjectManager ()
    {
        return $this->subjectManager;
    }
    
    /**
     * (non-PHPdoc)
     * @see \Core\Service\AbstractEntityDecorator::getEntity()
     * @return SubjectEntityInterface
     */
    public function getEntity(){
        return parent::getEntity();
    }

	/* (non-PHPdoc)
     * @see \Subject\SubjectManagerAwareInterface::setSubjectManager()
     */
    public function setSubjectManager (\Subject\SubjectManagerInterface $subject)
    {
        $this->subjectManager = $subject;
        return $this;
    }

	/* (non-PHPdoc)
     * @see \Taxonomy\SharedTaxonomyManagerAwareInterface::getSharedTaxonomyManager()
     */
    public function getSharedTaxonomyManager ()
    {
        return $this->sharedTaxonomyManager;
    }

	/* (non-PHPdoc)
     * @see \Taxonomy\SharedTaxonomyManagerAwareInterface::setSharedTaxonomyManager()
     */
    public function setSharedTaxonomyManager (\Taxonomy\SharedTaxonomyManagerInterface $sharedTaxonomyManager)
    {
        $this->sharedTaxonomyManager = $sharedTaxonomyManager;
    }

	/* (non-PHPdoc)
     * @see \Zend\ServiceManager\ServiceLocatorAwareInterface::getServiceLocator()
     */
    public function getServiceLocator ()
    {
        return $this->serviceLocator;
    }
    
	/* (non-PHPdoc)
     * @see \Zend\ServiceManager\ServiceLocatorAwareInterface::setServiceLocator()
     */
    public function setServiceLocator (\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        return $this;
    }

	/* (non-PHPdoc)
     * @see \Entity\EntityManagerAwareInterface::getEntityManager()
     */
    public function getEntityManager ()
    {
        return $this->entityManager;
    }

	/* (non-PHPdoc)
     * @see \Entity\EntityManagerAwareInterface::setEntityManager()
     */
    public function setEntityManager (\Entity\EntityManagerInterface $manager)
    {
        $this->entityManager = $manager;
        return $this;
    }
    
    public function getTaxonomy($name){ 
        $criteria = Criteria::create()
            ->where(Criteria::expr()->eq("name", $name))
            ->setMaxResults(1);
        $taxonomy = $this->getEntity()->getTaxonomies()->matching($criteria)->current();
        return $this->getSharedTaxonomyManager()->get($taxonomy);
    }
    
    private $decorator;
    
    public function build(){
        if(is_object($this->decorator)) 
            throw new \Exception('This Service already has been build.');       
        
        $className = $this->getEntity()->getFactory()->getName();
        if(!class_exists($className))
            throw new \Exception('Class `'.$className.'` not found');
        
        $instance = new $className();
        return $instance->build($this);
    }
}