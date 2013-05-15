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
namespace Subject\Math\Controller;

use Zend\View\Model\ViewModel;
use Subject\Core\Controller\AbstractSubjectController;

class TopicController extends AbstractSubjectController {
    public function indexAction(){
        $tm = $this->getSharedTaxonomyManager()->get('math:topic');
        $path = explode('/', $this->getParam('path'));
        $term = $tm->getTerm($path);
        
        
        $view = new ViewModel(array(
            'topics' => $term->getChildren(),
            'topic' => $term,
        ));

        if($term->linkingAllowed('entities')){
            $view->setVariable('entities', $term->getLinks('entities'));
        }
        
        $view->setTemplate('subject/math/topic/show');
        return $view;
    }
}