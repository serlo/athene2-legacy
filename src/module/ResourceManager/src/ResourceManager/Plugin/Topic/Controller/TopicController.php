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
namespace ResourceManager\Plugin\Topic\Controller;

use Subject\Controller\AbstractController;
use Zend\View\Model\ViewModel;

class TopicController extends AbstractController
{

    public function indexAction ()
    {
        $subjectService = $this->getSubject();
        
        $topic = $subjectService->topic()->get(explode('/', $this->getParam('path')));
        
        $entities = array();
        if ($topic->linkAllowed('entities')) {
            foreach ($topic->getEntities() as $entity) {
                if (! $entity->isTrashed()) {
                    $entities[] = $entity;
                }
            }
        }
        
        $view = new ViewModel(array(
            'topic' => $topic,
            'subject' => $subjectService
        ));
        
        $taxonomyView = new ViewModel(array(
            'term' => $topic
        ));
        $taxonomy = array();
        $taxonomyView->setTemplate('subject/plugin/topic/topic');
        foreach ($topic->getChildren() as $child) {
            $taxView = new ViewModel(array(
                'term' => $child,
                'subject' => $subjectService
            ));
            $taxonomyView->addChild($taxView->setTemplate('subject/plugin/topic/partial'), 'taxonomy', true);
        }
        $view->addChild($taxonomyView, 'taxonomy');
        
        $entityView = new ViewModel(array(
            'taxonomy' => $topic,
            'subject' => $subjectService,
            'acceptsEntities' => $topic->linkAllowed('entities'),
            'exercises' => $entities
        ));
        $entityView->setTemplate('subject/plugin/topic/entities');
        $view->addChild($entityView, 'entities');
        
        $view->setTemplate('subject/plugin/topic/show');
        
        return $view;
    }
}