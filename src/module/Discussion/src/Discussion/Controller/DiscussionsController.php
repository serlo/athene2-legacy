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
    use \Taxonomy\Manager\TaxonomyManagerAwareTrait,\Language\Manager\LanguageManagerAwareTrait,\User\Manager\UserManagerAwareTrait;

    public function indexAction()
    {
        $discussions = array();
        $forums = $this->getTaxonomyManager()->getChildren();
        
        $forum = $this->getTermService();
        
        if (is_object($forum)) {
            $discussions = $forum->getAssociatedRecursive('comments');
            $forums = $forum->getChildren();
        }
        
        $view = new ViewModel(array(
            'filters' => array(),
            'forums' => $forums,
            'forum' => $forum,
            'user' => $this->getUserManager()->getUserFromAuthenticator()
        ));
        
        $view->setTemplate('discussion/discussions/index');
        return $view;
    }

    protected function getTaxonomyManager()
    {
        return $this->getTaxonomyManager()->findTaxonomyByName('forum-category', $this->getLanguageManager()
            ->getLanguageFromRequest());
    }

    protected function getTermService($id = NULL)
    {
        $id = $this->params('id', $id);
        if ($id === NULL) {
            return NULL;
        }
        
        return $this->getTaxonomyManager()->getTerm($id);
    }
}