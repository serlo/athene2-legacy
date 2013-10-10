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
namespace LearningResource\Plugin\Taxonomy\Controller;

use Zend\View\Model\ViewModel;

class TopicFolderController extends AbstractController
{
    public function topicDialogAction(){
        $plugin = $this->getPlugin();
        $view = new ViewModel(array(
            'plugin' => $plugin,
            'terms' => $plugin->getRoots()
        ));
        $view->setTemplate('learning-resource/plugin/taxonomy/topic-dialog');
        return $view;
    }
    
    public function setTopicAction(){
        $plugin = $this->getPlugin();
        
        $topicId = $this->getParam('term');
        $plugin->setTopic($topicId);
        
        $this->redirect()->toUrl($this->getRequest()
            ->getHeader('Referer')
            ->getUri());
    }
}