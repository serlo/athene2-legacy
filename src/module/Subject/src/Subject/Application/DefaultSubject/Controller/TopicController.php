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
namespace Subject\Application\DefaultSubject\Controller;

use Zend\View\Model\ViewModel;
use Subject\Controller\AbstractController;

class TopicController extends AbstractController {
    public function indexAction(){
        
        $subjectService = $this->getSubjectService();
        //$view = new ViewModel;
        
        //$subjectService->getTopics();
        //;
        
/*        $tm = $this->getSharedTaxonomyManager()->get('topic');
        $path = explode('/', $this->getParam('path'));
        $term = $tm->getTerm($path);
        */
        $view = new ViewModel(array(
            'topics' =>  $subjectService->getTopics(),
            'topic' => $subjectService->getTopic(explode('/', $this->getParam('path'))),
        ));

        /*if($term->linkingAllowed('entities')){
            $view->setVariable('entities', $term->getLinks('entities'));
        }*/
        
        $view->setTemplate('subject/math/topic/show');
        return $view;
    }
}