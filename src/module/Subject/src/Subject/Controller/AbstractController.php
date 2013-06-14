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
namespace Subject\Controller;


use Entity\EntityManagerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Taxonomy\SharedTaxonomyManagerInterface;
use Subject\Service\SubjectServiceInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Subject\SubjectManagerInterface;

class AbstractController extends AbstractActionController
{
    protected $viewPath;
    
    public function getViewPath(){
        return $this->viewPath;
    }
    
    /**
     * @var SubjectManagerInterface
     */
    protected $subjectManager;
    
    public function setSubjectManager(SubjectManagerInterface $subjectManager){
        $this->subjectManager = $subjectManager;
        return $this;
    }
    
    public function __construct(){
    }
    
    /**
     * @var SubjectServiceInterface
     */
    protected $subjectService;
    
    /**
     * @return SubjectServiceInterface $subjectService
     */
    public function getSubjectService ()
    {
        if(!$this->subjectService){
            $subject = $this->params()->fromRoute('subject');
            $this->setSubjectService($this->subjectManager->get($subject));
        }
        
        return $this->subjectService;
    }
    
    /**
     * @param SubjectServiceInterface $subjectService
     * @return $this
     */
    public function setSubjectService (SubjectServiceInterface $subjectService)
    {
        $this->subjectService = $subjectService;
        return $this;
    }
    
    /**
     * @return SharedTaxonomyManagerInterface
     */
    public function getSharedTaxonomyManager ()
    {
        return $this->getSubjectService()->getSharedTaxonomyManager();
    }
    
    /**
     * @return EntityManagerInterface
     */
    public function getEntityManager ()
    {
        return $this->getSubjectService()->getEntityManager();
    }
    
    /**
     * @return ObjectManager
     */
    public function getObjectManager ()
    {
        return $this->getSubjectService()->getObjectManager();
    }
}