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

use Subject\Plugin\Controller\AbstractController;
use Zend\View\Model\ViewModel;

class TopicController extends AbstractController
{

    public function indexAction()
    {
        $subjectService = $this->getSubject();
        
        $topic = $subjectService->topic()->get(explode('/', $this->getParam('path')));
        
        $entities = array();
        if ($topic->isLinkAllowed('entities')) {
            foreach ($topic->getEntities()->asService() as $entity) {
                if (! $entity->isTrashed()) {
                    $entities[] = $entity;
                }
            }
        }
        
        $view = new ViewModel(array(
            'topic' => $topic,
            'subject' => $subjectService,
            'plugin' => $this->getPlugin()
        ));
        
        $taxonomyView = new ViewModel(array(
            'terms' => $topic->getChildren(),
            'subject' => $subjectService,
            'plugin' => $this->getPlugin()
        ));
        $taxonomyView->setTemplate('resource-manager/plugin/topic/partial');
        $view->addChild($taxonomyView, 'taxonomy');
        
        $entityView = new ViewModel(array(
            'taxonomy' => $topic,
            'subject' => $subjectService,
            'acceptsEntities' => $topic->isLinkAllowed('entities'),
            'plugin' => $this->getPlugin(),
            'entities' => $entities
        ));
        $entityView->setTemplate('resource-manager/plugin/topic/entities');
        $view->addChild($entityView, 'entities');
        
        $view->setTemplate('resource-manager/plugin/topic/show');
        
        return $view;
    }
}