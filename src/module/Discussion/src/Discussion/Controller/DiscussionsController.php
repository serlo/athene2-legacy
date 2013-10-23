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
namespace Discussion\Controller;

use Zend\View\Model\ViewModel;
class DiscussionsController extends AbstractController
{
    use \Discussion\Filter\DiscussionFilterChainAwareTrait;
    
    public function indexAction()
    {
        $this->getDiscussionFilterChain()->attach('taxonomy', array(
            'type' => 'subject',
            'slug' => 'mathe'
        ));
        
        $discussions = $this->getDiscussionFilterChain()->filter();
        
        $view = new ViewModel(array(
            'filters' => array(),
            'discussions' => $discussions
        ));
        
        $view->setTemplate('discussion/discussions/index');
        return $view;
    }
}