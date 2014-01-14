<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author    Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license   LGPL-3.0
 * @license   http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link      https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Discussion\Controller;

use Language\Manager\LanguageManagerAwareTrait;
use Taxonomy\Manager\TaxonomyManagerAwareTrait;
use User\Manager\UserManagerAwareTrait;
use Zend\View\Model\ViewModel;

class DiscussionsController extends AbstractController
{
    use TaxonomyManagerAwareTrait, LanguageManagerAwareTrait, UserManagerAwareTrait;

    public function indexAction()
    {
        $forums = $this->getTaxonomy()->getChildren();
        $forum = $this->getTermService();

        if (is_object($forum)) {
            $forums = $forum->getChildren();
        }

        $view = new ViewModel([
            'forums' => $forums,
            'forum' => $forum,
            'user' => $this->getUserManager()->getUserFromAuthenticator()
        ]);

        $view->setTemplate('discussion/discussions/index');
        return $view;
    }

    protected function getTaxonomy()
    {
        $language = $this->getLanguageManager()
            ->getLanguageFromRequest();
        return $this->getTaxonomyManager()->findTaxonomyByName('forum-category', $language);
    }

    protected function getTermService($id = null)
    {
        $id = $this->params('id', $id);
        return $id ? $this->getTaxonomyManager()->getTerm($id) : null;
    }
}
