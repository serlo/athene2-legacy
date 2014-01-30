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
namespace Alias\View\Helper;

use Alias\AliasManagerAwareTrait;
use Alias\Exception\AliasNotFoundException;
use Common\Traits\ConfigAwareTrait;
use Instance\Manager\InstanceManagerAwareTrait;
use Zend\View\Helper\Url as ZendUrl;

class Url extends ZendUrl
{
    use AliasManagerAwareTrait, InstanceManagerAwareTrait;
    use ConfigAwareTrait;

    protected function getDefaultConfig()
    {
        return ['uri_head' => '/alias'];
    }

    public function __invoke($name = null, $params = array(), $options = array(), $reuseMatchedParams = false)
    {
        $link = parent::__invoke($name, $params, $options, $reuseMatchedParams);

        try {
            $aliasManager = $this->getAliasManager();
            $instance     = $this->getInstanceManager()->getTenantFromRequest();
            $alias        = $aliasManager->findAliasBySource($link, $instance);

            return $this->getOption('uri_head') . '/' . $alias;
        } catch (AliasNotFoundException $e) {
            return $link;
        }
    }
}
