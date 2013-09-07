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
namespace Subject\Plugin\Topic\Controller;

use Subject\Plugin\Controller\AbstractController;
use Zend\View\Model\ViewModel;

class TopicController extends AbstractController
{

    public function indexAction()
    {
        $subjectService = $this->getSubject();
        $topic = false;
        
        if($this->params('path', NULL)){
            $topic = $subjectService->topic()->get(explode('/', $this->params('path', NULL)));
        }
        
        $entities = array();
        if ($topic && $topic->isLinkAllowed('entities')) {
            foreach ($topic->getLinks('entities')->asService() as $entity) {
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
            'terms' => $topic ? $topic->getChildren() : $this->getPlugin()->getRootFolders($this->params('subject', NULL)),
            'subject' => $subjectService,
            'plugin' => $this->getPlugin(),
            'pluginName' => $pluginName
        ));
        
        $taxonomyView->setTemplate('subject/plugin/topic/partial');
        $view->addChild($taxonomyView, 'taxonomy');
        
        $entityView = new ViewModel(array(
            'taxonomy' => $topic,
            'subject' => $subjectService,
            'acceptsEntities' => $topic ? $topic->isLinkAllowed('entities') : false,
            'plugin' => $this->getPlugin(),
            'entities' => $entities
        ));
        $entityView->setTemplate('subject/plugin/topic/entities');
        $view->addChild($entityView, 'entities');
        
        $view->setTemplate('subject/plugin/topic/show');
        return $view;
    }
}