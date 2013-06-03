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
namespace Application\LearningObject\Exercise;

use Zend\View\Model\ViewModel;
use Entity\Service\EntityServiceInterface;
use Application\LearningObject\Exercise\Form\TextExerciseForm;
use Entity\Factory\AbstractEntity;

class TextExercise extends AbstractEntity implements EntityServiceInterface, TextExerciseInterface, ExerciseInterface
{        
    protected $template = 'learning-object/exercise/text/display';
    
    public function toViewModel()
    {
        if(!$this->viewModel){
            $this->viewModel = new ViewModel(array('entity' => $this));
        }
        $this->viewModel->setTemplate($this->getTemplate());
        //if($this->getSolution()){
        //    $this->_viewModel->addChild($this->getSolution()->toViewModel(), 'solution');
        //}
        return $this->viewModel;
    }

    
    public function getContent ()
    {
        return $this->getRepository()->getCurrentRevision()->get('content');
    }
    
    public function getSolution ()
    {
        return $this->findChild('Solution\TextSolution');
    }
    
    public function getFormObject(){
        return new TextExerciseForm();
    }
    
    public function getData(){
        return array(
            'id' => $this->getId(),
            //'subject' => $this->getSubject(),
            //'topic' => $this->getTopic(),
            //'content' => $this->getContent(),
        );
    }
    
    public function getFormData(){
        try{
            return array(
                'id' => $this->getId(),
                'revision' => array(
                    'content' => $this->getContent(),
                )
            );            
        } catch (\Versioning\Exception\RevisionNotFoundException $e) {
            return array(
                'id' => $this->getId(),
                'revision' => array(
                    'content' => '',
                )
            );
        }
            
    }
}