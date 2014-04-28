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
namespace Common\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Session\Container;

class RefererProvider extends AbstractPlugin
{
    protected $container;

    public function toUrl($default = '/')
    {
        $referer = $this->getController()->getRequest()->getHeader('Referer');
        $referer = $referer ? $referer->getUri() : $default;

        return $referer;
    }

    public function store()
    {
        if (!$this->container) {
            $this->container = new Container('ref');
        }
        $this->container->ref = $this->toUrl();

        return $this;
    }

    public function fromStorage($default = '/')
    {
        $container = new Container('ref');

        return isset($container->ref) ? $container->ref : $this->toUrl();
    }
}
