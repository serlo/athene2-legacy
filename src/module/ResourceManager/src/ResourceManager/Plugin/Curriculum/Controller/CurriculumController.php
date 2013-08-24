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
namespace ResourceManager\Plugin\Curriculum\Controller;

use Subject\Plugin\Controller\AbstractController;
use Zend\View\Model\ViewModel;

class CurriculumController extends AbstractController
{

    public function indexAction()
    {
        $plugin = $this->getPlugin();
        $subjectService = $plugin->getSubjectService();
        $curriculum = $plugin->getCurriculum($this->getParam('curriculum'));
        $topic = $plugin->getTopic(explode('/', $this->getParam('path')));
        
        $entities = array();
        if ($topic->linkAllowed('entities')) {
            foreach ($topic->getEntities()->asService() as $entity) {
                if (! $entity->isTrashed()) {
                    $entities[] = $entity;
                }
            }
        }
        
        $view = new ViewModel(array(
            'topic' => $topic,
            'curriculum' => $curriculum,
            'subject' => $subjectService,
            'plugin' => $this->getPlugin()
        ));
        
        $taxonomyView = new ViewModel(array(
            'terms' => $topic->getChildren(),
            'subject' => $subjectService,
            'curriculum' => $curriculum,
            'plugin' => $this->getPlugin()
        ));
        
        $taxonomyView->setTemplate('resource-manager/plugin/curriculum/partial');
        $view->addChild($taxonomyView, 'taxonomy');
        
        $entityView = new ViewModel(array(
            'topic' => $topic,
            'curriculum' => $curriculum,
            'subject' => $subjectService,
            'acceptsEntities' => $topic->linkAllowed('entities'),
            'plugin' => $this->getPlugin(),
            'entities' => $entities
        ));
        
        $entityView->setTemplate('resource-manager/plugin/curriculum/entities');
        $view->addChild($entityView, 'entities');
        
        $view->setTemplate('resource-manager/plugin/curriculum/show');
        
        return $view;
    }
}