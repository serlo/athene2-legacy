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

namespace Navigation\Controller;


use Taxonomy\Controller\AbstractController;
use Zend\View\Model\ViewModel;

class RenderController extends AbstractController
{
    /**
     * @var array
     */
    protected $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function listAction()
    {
        $navigation = $this->params('navigation');
        $minLevel   = $this->params('minLevel');
        $maxLevel   = $this->params('maxLevel');
        $branch     = $this->params('branch');
        $terminate  = $this->getRequest()->isXmlHttpRequest();

        if (!array_key_exists($navigation, $this->config)) {
            $this->getResponse()->setStatusCode(404);
            return false;
        }

        $view = new ViewModel([
            'navigation' => $navigation,
            'minLevel'   => $minLevel,
            'maxLevel'   => $maxLevel,
            'branch'     => $branch,
        ]);
        $view->setTemplate('navigation/render');
        $view->setTerminal($terminate);

        return $view;
    }
}
 