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
namespace Subject\Core\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Subject\Core\SubjectManagerAwareInterface;
use Entity\EntityManagerAwareInterface;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use Entity\EntityManagerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Subject\Core\SubjectManagerInterface;
use Taxonomy\SharedTaxonomyManagerAwareInterface;
use Taxonomy\SharedTaxonomyManagerInterface;

abstract class AbstractSubjectController extends AbstractActionController implements SubjectManagerAwareInterface, EntityManagerAwareInterface, ObjectManagerAwareInterface, SharedTaxonomyManagerAwareInterface
{
    
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;
    
    /**
     * @var SubjectManagerInterface
     */
    protected $subjectManager;
    
    /**
     * @var ObjectManager
     */
    protected $objectManager;
    
    /**
     * @var SharedTaxonomyManagerInterfacenterface
     */
    protected $sharedTaxonomyManager;
    
    /**
     * (non-PHPdoc)
     * @see \Taxonomy\SharedTaxonomyManagerAwareInterface::getSharedTaxonomyManager()
     */
    public function getSharedTaxonomyManager ()
    {
        return $this->sharedTaxonomyManager;
    }
    
    /**
     * (non-PHPdoc)
     * @see \Taxonomy\SharedTaxonomyManagerAwareInterface::setSharedTaxonomyManager()
     */
    public function setSharedTaxonomyManager (SharedTaxonomyManagerInterface $sharedTaxonomyManager)
    {
        $this->sharedTaxonomyManager = $sharedTaxonomyManager;
        return $this;
    }

	/**
	 * (non-PHPdoc)
	 * @see \Entity\EntityManagerAwareInterface::getEntityManager()
	 */
    public function getEntityManager ()
    {
        return $this->entityManager;
    }

    /**
     * (non-PHPdoc)
     * @see \Subject\SubjectManagerAwareInterface::getSubjectManager()
     */
    public function getSubjectManager ()
    {
        return $this->subjectManager;
    }

	/**
	 * (non-PHPdoc)
	 * @see \DoctrineModule\Persistence\ObjectManagerAwareInterface::getObjectManager()
	 */
    public function getObjectManager ()
    {
        return $this->objectManager;
    }

	/**
	 * (non-PHPdoc)
	 * @see \Entity\EntityManagerAwareInterface::setEntityManager()
	 */
    public function setEntityManager (EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        return $this;
    }

    /**
     * (non-PHPdoc)
     * @see \Subject\SubjectManagerAwareInterface::setSubjectManager()
     */
    public function setSubjectManager (SubjectManagerInterface $subjectManager)
    {
        $this->subjectManager = $subjectManager;
        return $this;
    }

	/**
	 * (non-PHPdoc)
	 * @see \DoctrineModule\Persistence\ObjectManagerAwareInterface::setObjectManager()
	 */
    public function setObjectManager (ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
        return $this;
    }
}