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
namespace Contexter\View\Helper;

use Zend\View\Helper\AbstractHelper;

class Contexter extends AbstractHelper
{
    use \Contexter\Router\RouterAwareTrait;

    public function __invoke()
    {
        return $this;
    }

    public function render($type = null)
    {
        if (is_object($this->getRouter()->getRouteMatch())) {
            $matches = $this->getRouter()->match(null, $type);
            return $this->getView()->partial(
                'contexter/helper/default',
                [
                    'router'  => $this->getRouter(),
                    'matches' => $matches,
                    'type'    => $type
                ]
            );
        }
    }

    public function renderButton()
    {
        if (is_object($this->getRouter()->getRouteMatch())) {
            $matches = $this->getRouter()->match();
            return $this->getView()->partial(
                'contexter/helper/button',
                [
                    'router'  => $this->getRouter(),
                    'matches' => $matches
                ]
            );
        }
    }
}
