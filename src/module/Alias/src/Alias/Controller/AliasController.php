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
namespace Alias\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Alias;
use Zend\Http\Request;
use Zend\Stdlib\ArrayUtils;

class AliasController extends AbstractActionController
{
    use Alias\AliasManagerAwareTrait,\Language\Manager\LanguageManagerAwareTrait;

    /**
     *
     * @var unknown
     */
    protected $router;

    public function forwardAction()
    {
        $source = $this->getAliasManager()->findSourceByAlias($this->params('alias'), $this->getLanguageManager()
            ->getLanguageFromRequest());
        
        $request = new Request();
        $request->setMethod(Request::METHOD_GET);
        $request->setUri($source);
        
        $routeMatch = $this->getServiceLocator()
            ->get('Router')
            ->match($request);
        
        if ($routeMatch === NULL)
            throw new Alias\Exception\RuntimeException(sprintf('Could not match a route for `%s`', $source));
        
        $params = $routeMatch->getParams();
        
        $controller = $params['controller'];
        
        return $this->forward()->dispatch($controller, ArrayUtils::merge($params, array(
            'forwarded' => true
        )));
    }
}