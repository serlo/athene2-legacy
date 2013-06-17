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
namespace Application\Subject\DefaultSubject\Controller;

use Zend\View\Model\ViewModel;
use Application\LearningObject\Exercise\ExerciseInterface;

class TopicController extends SideNavigationController
{

    public function indexAction ()
    {
        $subjectService = $this->getSubjectService();
        
        $topic = $subjectService->getTopic(explode('/', $this->getParam('path')));
        
        $entities = array();
        if($topic->linkAllowed('entities')){
            foreach($topic->getEntities() as $entity){
                if(!$entity->isTrashed()){
                    $entities[] = $entity;
                }
            }
        }
        
        $view = new ViewModel(array(
            'topic' => $topic,
            'subject' => $subjectService
        ));
        
        $taxonomyView = new ViewModel(array(
            'term' => $topic,
        ));
        $taxonomy = array();
        $taxonomyView->setTemplate('subject/math/taxonomy/topic');
        foreach($topic->getChildren() as $child){
            $taxView = new ViewModel(array('term' => $child));
            $taxonomyView->addChild($taxView->setTemplate('subject/math/taxonomy/term/topic/partial'), 'taxonomy', true);
            $this->addItem(false, $this->url()->fromRoute('subject/math/topic', array('path' => implode('/', $child->getPath()))), $child->getName());
        }
        $view->addChild($taxonomyView, 'taxonomy');
        
        
        $entityView = new ViewModel(array(
            'taxonomy' => $topic,
            'subject' => $subjectService,
            'acceptsEntities' => $topic->linkAllowed('entities'),
        ));
        $entityView->setTemplate($this->getViewPath() . 'topic/entities');
        
        $exercises = array();
        if (is_array($entities)) {
            foreach ($entities as $exercise) {
                if ($exercise instanceof ExerciseInterface){
                    $exercises[] = $exercise;
                }
            }
        }
        $exerciseView = new ViewModel(array(
            'exercises' => $exercises,
        ));
        $exerciseView->setTemplate($this->getViewPath() . 'topic/exercises');
        
        $entityView->addChild($exerciseView, 'entities');
        $view->addChild($entityView, 'entities');
        
        $view->setTemplate($this->getViewPath() . 'topic/show');
        
        // TODO: USE EVENTS
        return $this->inject($view);
    }
}