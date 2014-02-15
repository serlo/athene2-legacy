<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright   Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Alias\Controller;

use Alias\Exception\CanonicalUrlNotFoundException;
use Alias;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Stdlib\ArrayUtils;

class AliasController extends AbstractActionController
{
    use Alias\AliasManagerAwareTrait, \Instance\Manager\InstanceManagerAwareTrait;

    /**
     * @var unknown
     */
    protected $router;

    public function forwardAction()
    {
        try {
            $canonical = $this->getAliasManager()->findCanonicalAlias(
                $this->params('alias'),
                $this->getInstanceManager()->getInstanceFromRequest()
            );
            $this->redirect()->toUrl($canonical);
        } catch (CanonicalUrlNotFoundException $e) {
        }

        try {
            $source = $this->getAliasManager()->findSourceByAlias(
                $this->params('alias'),
                $this->getInstanceManager()->getInstanceFromRequest()
            );
        } catch (Alias\Exception\AliasNotFoundException $e) {
            return $this->getResponse()->setStatusCode(404);
        }

        $router = $this->getServiceLocator()->get('Router');

        $request = new Request();
        $request->setMethod(Request::METHOD_GET);
        $request->setUri($source);

        $routeMatch = $router->match($request);

        if ($routeMatch === null) {
            throw new Alias\Exception\RuntimeException(sprintf(
                'Could not match a route for `%s`',
                $source
            ));
        }

        $params = $routeMatch->getParams();

        $controller = $params['controller'];

        $return = $this->forward()->dispatch(
            $controller,
            ArrayUtils::merge(
                $params,
                array(
                    'forwarded' => true
                )
            )
        );

        return $return;
    }
}