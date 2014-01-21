<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author    Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license   LGPL-3.0
 * @license   http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link      https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013-2014 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Normalizer\Controller;

use Normalizer\NormalizerAwareTrait;
use Uuid\Manager\UuidManagerAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;

class SignpostController extends AbstractActionController
{
    use NormalizerAwareTrait, UuidManagerAwareTrait;

    public function indexAction()
    {
        $object      = $this->getUuidManager()->getUuid($this->params('object'))->getHolder();
        $normalized  = $this->getNormalizer()->normalize($object);
        $routeName   = $normalized->getRouteName();
        $routeParams = $normalized->getRouteParams();
        $type        = $normalized->getType();
        $url         = $this->url()->fromRoute($routeName, $routeParams);

        $this->redirect()->toRoute($routeName, $routeParams, ['query' => ['type' => $type]]);

        return false;

        /** TODO REMOVE */
        /*
        $router  = $this->getServiceLocator()->get('Router');
        $request = new Request();

        $request->setMethod(Request::METHOD_GET);
        $request->setUri($url);

        $routeMatch = $router->match($request);

        if (!$routeMatch) {
            throw new RuntimeException(sprintf(
                'Could not match a route for `%s`',
                $url
            ));
        }

        $params     = $routeMatch->getParams();
        $controller = $params['controller'];
        $content    = $this->forward()->dispatch($controller, $params);

        $renderer = $this->getServiceLocator()->get('ViewRenderer');

        $content = $renderer->render($content);

        $return = new JsonModel([
            'id'      => $object->getId(),
            'type'    => $type,
            'content' => $content
        ]);
        */
    }
}
