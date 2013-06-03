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

class TopicController extends AbstractController
{

    public function indexAction ()
    {
        $subjectService = $this->getSubjectService();
        
        $topic = $subjectService->getTopic(explode('/', $this->getParam('path')));
        $entities = $topic->getEntities();
        
        $view = new ViewModel(array(
            'topic' => $topic,
            'subject' => $subjectService
        ));
        
        $taxonomy = array();
        foreach($topic->getChildren() as $child){
            $view->addChild($child->render(), 'taxonomy', true);
        }
        
        $entityView = new ViewModel(array(
            'taxonomy' => $topic,
            'subject' => $subjectService,
            'acceptsEntities' => $topic->linkAllowed('entities'),
        ));
        $entityView->setTemplate($this->getViewPath() . 'topic/entities');
        
        $exerciseView = new ViewModel(array(
            'exercises' => '',
        ));
        $exerciseView->setTemplate($this->getViewPath() . 'topic/exercises');
        
        if (is_array($entities)) {
            foreach ($entities as $entity) {
                if ($entity instanceof ExerciseInterface)
                    $exerciseView->addChild($entity->toViewModel(), 'exercises', true);
            }
        }
        
        $entityView->addChild($exerciseView, 'entities');
        $view->addChild($entityView, 'entities');
        
        $view->setTemplate($this->getViewPath() . 'topic/show');
        return $view;
    }
}