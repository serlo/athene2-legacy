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
    /**
     * @var SubjectManagerInterface
     */
    protected $subjectManager;
    
    /**
     * 
     * @param SubjectManagerInterface $subjectManager
     * @return \Subject\Controller\AbstractController
     */
    public function setSubjectManager(SubjectManagerInterface $subjectManager){
        $this->subjectManager = $subjectManager;
        return $this;
    }
    
    /**
     * 
     * @return \Subject\SubjectManagerInterface
     */
    public function getSubjectManager(){
    	return $this->subjectManager;
    }
    
    /**
     * 
     * @param string $identifier
     * @return \Subject\Service\SubjectServiceInterface
     */
    public function getSubject($identifier = NULL){
        if($identifier === NULL){
            $subject = $this->params()->fromRoute('subject');
            return $this->getSubjectManager()->get($subject);
        } else {
            return $this->getSubjectManager()->get($identifier);
        }        	
    }
    
    /*public function getSharedTaxonomyManager ()
    {
        return $this->getSubjectService()->getSharedTaxonomyManager();
    }
    
    public function getEntityManager ()
    {
        return $this->getSubjectService()->getEntityManager();
    }
    
    public function getObjectManager ()
    {
        return $this->getSubjectService()->getObjectManager();
    }*/
}