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
namespace Ui\Provider;

use Ui\Navigation\NavigationHydrator;
use Zend\Stdlib\ArrayUtils;

class InstanceAwareProvider implements NavigationHydrator
{
    use\Zend\ServiceManager\ServiceLocatorAwareTrait,\Instance\Manager\InstanceManagerAwareTrait;

    public function hydrateConfig(array &$config)
    {
        $instance = $this->getInstanceManager()->getInstanceFromRequest();
        $config = ArrayUtils::merge($config, include __DIR__ . '/../../../config/navigation/' . $instance->getName() . '.config.php');
        return $config;
    }
}