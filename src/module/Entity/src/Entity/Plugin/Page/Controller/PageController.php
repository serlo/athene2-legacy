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
namespace Entity\Plugin\Page\Controller;

use Entity\Plugin\Controller\AbstractController;
use Versioning\Exception\RevisionNotFoundException;
use Entity\Exception\EntityNotFoundException;
use Zend\Mvc\Router\Http\RouteMatch;
use Alias\Exception\AliasNotFoundException;

class PageController extends AbstractController
{
    use \Language\Manager\LanguageManagerAwareTrait,\Alias\AliasManagerAwareTrait,\User\Manager\UserManagerAwareTrait;

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
            try {
                $alias = $this->getAliasManager()->findAliasByObject($entity->getEntity()
                    ->getUuidEntity());
                $this->redirect()->toUrl('/alias/' . $alias->getAlias());
            } catch (AliasNotFoundException $e) {}
        }
        
        try {
            $model = new \Zend\View\Model\ViewModel(array(
                'entity' => $entity,
                'plugin' => $page,
                'user' => $this->getUserManager()->getUserFromAuthenticator(),
            ));
            $this->layout($plugin->getLayout());
            $model->setTemplate($page->getTemplate());
            return $model;
        } catch (RevisionNotFoundException $e) {
            $this->getResponse()->setStatusCode(404);
            return;
        }
    }
}