<?php
namespace Entity\LearningObjects\Exercise;

use Entity\Factory\AbstractEntityBuilder;
use Entity\Factory\Components\SubjectComponentInterface;
use Entity\Factory\Components\SubjectComponent;
use Entity\Factory\Components\TopicComponent;
use Entity\Factory\Components\TopicComponentInterface;
use Entity\Factory\Components\LinkComponent;
use Entity\Factory\Components\RepositoryComponent;

abstract class AbstractExercise extends AbstractEntityBuilder implements SubjectComponentInterface, TopicComponentInterface
{
	/**
	 * @var SubjectComponent
	 */
	protected $_subject;

	/**
	 * @var TopicComponent
	 */
	protected $_topic;
	
	/**
	 * @var LinkComponent
	 */
	protected $_link;
	
	
    protected function _loadComponents(){
        //$this->addRepositoryComponent()
        //->addRenderComponent('some/file/torender')
        //->addSubjectComponent();
        
    	//$subject = new SubjectComponent($this->getSource());
    	//$this->_subject = $subject->build();
    	
    	//$topic = new TopicComponent($this->getSource());
    	//$this->_topic = $topic->build();
    	
    	$link = new LinkComponent($this->getSource());
    	$this->_link = $link->build();
    }
        
    public function render(){
        //$this->getComponent('render')->render();
    }
    
    public function getSubject(){
    	return $this->_subject->getSubject();
    }
    
    public function getTopic(){
    	return $this->_topic->getTopic();
    }
}