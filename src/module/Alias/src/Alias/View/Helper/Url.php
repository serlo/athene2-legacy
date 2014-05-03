<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 */
namespace Alias\View\Helper;

use Alias\AliasManagerAwareTrait;
use Alias\AliasManagerInterface;
use Alias\Exception\AliasNotFoundException;
use Common\Traits\ConfigAwareTrait;
use Instance\Manager\InstanceManagerAwareTrait;
use Instance\Manager\InstanceManagerInterface;
use Zend\Cache\Storage\StorageInterface;
use Zend\View\Helper\Url as ZendUrl;

class Url extends ZendUrl
{
    use AliasManagerAwareTrait, InstanceManagerAwareTrait;

    /**
     * @var StorageInterface
     */
    protected $storage;

    public function __construct(
        AliasManagerInterface $aliasManager,
        InstanceManagerInterface $instanceManager
    ) {
        $this->aliasManager    = $aliasManager;
        $this->instanceManager = $instanceManager;
    }

    public function __invoke($name = null, $params = [], $options = [], $reuseMatchedParams = false, $useAlias = true)
    {
        $link = parent::__invoke($name, $params, $options, $reuseMatchedParams);

        if (!$useAlias) {
            return $link;
        }

        try {
            $aliasManager = $this->getAliasManager();
            $instance     = $this->getInstanceManager()->getInstanceFromRequest();
            return $aliasManager->findAliasBySource($link, $instance);
        } catch (AliasNotFoundException $e) {
            // No alias was found -> nothing to do
        }

        return $link;
    }
}
