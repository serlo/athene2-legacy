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
namespace Subject\Plugin\Curriculum\Controller;

use Subject\Plugin\Controller\AbstractController;
use Zend\View\Model\ViewModel;

class CurriculumController extends AbstractController
{

    public function indexAction ()
    {
        $plugin = $this->getPlugin();
        $subjectService = $plugin->getSubjectService();
        
        $view = new ViewModel(array(
            'schoolRootFolders' => $plugin->getSchoolTypeRootFolders(),
            'subject' => $subjectService
        ));
        $view->setTemplate('subject/plugin/curriculum/show');
        return $view;
    }

    public function topicAction ()
    {
        $plugin = $this->getPlugin();
        $subjectService = $plugin->getSubjectService();
        
        $curriculum = $this->getCurriculum();
        
        $terms = $plugin->getRootTopics();
        $topic = $this->getTopic();
        if($topic){
            $terms = $topic->getChildren();
        }
        
        $view = new ViewModel(array(
            'schoolRootFolders' => $plugin->getSchoolTypeRootFolders(),
            'subject' => $subjectService
        ));
        $view->setTemplate('subject/plugin/curriculum/show');
        
        $topicView = new ViewModel(array(
            'plugin' => $plugin,
            'subject' => $subjectService,
            'terms' => $terms,
            'curriculum' => $curriculum,
        ));
        $topicView->setTemplate('subject/plugin/curriculum/topic');
        
        $view->addChild($topicView, 'topics');

        if($topic){
            if($topic->countLinks('entities')){
                $entities = $this->getPlugin()->filterEntities($topic->getLinks('entities')->asService(), $topic);
                if($entities->count()){
                    $entityView = new ViewModel(array(
                        'entities' => $entities,
                        'acceptsEntities' => $topic->isLinkAllowed('entities'),
                        'plugin' => $plugin,
                        'subject' => $subjectService
                    ));
                    $entityView->setTemplate('subject/plugin/curriculum/entities');
                    $view->addChild($entityView, 'entities');
                }
            }
        }
        
        return $view;
    }
    
    protected function getTopic(){
        if(!$this->params('path', false)){
            return null;
        } else {
            return $this->getPlugin()->getSharedTaxonomyManager()->get('topic')->get(explode('/',$this->params('path')));
        }
    }

    protected function getCurriculum ()
    {
        return $this->getPlugin()->getCurriculum($this->getParam('curriculum'));
    }
}