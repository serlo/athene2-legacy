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
namespace Subject\Plugin\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class AbstractController extends AbstractActionController
{   
    use \Subject\Manager\SubjectManagerAwareTrait, \Language\Manager\LanguageManagerAwareTrait;
    
    /**
     * 
     * @param string $identifier
     * @return \Subject\Service\SubjectServiceInterface
     */
    public function getSubject($id = NULL){
        if($id === NULL){
            $subject = $this->params()->fromRoute('subject');
            return $this->getSubjectManager()->findSubjectByString($subject, $this->getLanguageManager()->getLanguageFromRequest());
        } else {
            return $this->getSubjectManager()->getSubject($id);
        }        	
    }

    protected function getPlugin ($id = NULL)
    {        
        $subjectService = $this->getSubject($id);
        
        if (! $subjectService->isPluginWhitelisted($this->getParam('plugin')))
            throw new \Exception(sprintf('Plugin %s not supported.', $this->getParam('plugin')));
        
        $scope = $this->getParam('plugin');
        
        $plugin = $subjectService->$scope();

        return $plugin;
    }
}