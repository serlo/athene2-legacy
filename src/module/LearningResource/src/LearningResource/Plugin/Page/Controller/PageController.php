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
namespace LearningResource\Plugin\Page\Controller;

use Entity\Plugin\Controller\AbstractController;
use Versioning\Exception\RevisionNotFoundException;
use Entity\Exception\EntityNotFoundException;
use Zend\Mvc\Router\RouteInterface;
use Zend\Mvc\Router\Http\RouteMatch;

class PageController extends AbstractController
{
    use\Language\Manager\LanguageManagerAwareTrait,\Alias\AliasManagerAwareTrait, \User\Manager\UserManagerAwareTrait;

    
    public function indexAction()
    {
        try {
            $page = $plugin = $this->getPlugin();
            $entity = $this->getEntityService();
        } catch (EntityNotFoundException $e) {
            $this->getResponse()->setStatusCode(404);
            return;
        }
        
        if (! $this->params('forwarded')) {
            $alias = $this->getAliasManager()->findAliasByUuid($entity->getEntity()
                ->getUuidEntity());
            $this->redirect()->toUrl('/alias/' . $alias->getAlias());
        }
        $routeMatch = new RouteMatch(array('subject' => $entity->provider()->getSubjectSlug()));
        $routeMatch->setMatchedRouteName('subject');
        $this->getServiceLocator()->get('Application')->getMvcEvent()->setRouteMatch($routeMatch);
        
        try {
            $model = new \Zend\View\Model\ViewModel(array(
                'entity' => $entity,
                'plugin' => $page,
                'user' => $this->getUserManager()->getUserFromAuthenticator(),
                'discussion' => $entity->plugin('discussion')
            ));
            $page->hydrate($model);
            $model->setTemplate($page->getTemplate());
            return $model;
        } catch (RevisionNotFoundException $e) {
            $this->getResponse()->setStatusCode(404);
            return;
        }
    }
}