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
namespace Uuid\Router;

use \Uuid\Router\Exception;

class UuidRouter implements UuidRouterInterface
{
    use \Uuid\Manager\UuidManagerAwareTrait,\Common\Traits\ConfigAwareTrait;

    protected function getDefaultConfig()
    {
        return array(
            'routes' => array()
        );
    }
    
    /*
     * (non-PHPdoc) @see \Uuid\Router\UuidRouterInterface::assemble()
     */
    public function assemble($id)
    {
        $uuid = $this->getUuidManager()->getUuid($id);
        foreach ($this->getOption('routes') as $type => $uri) {
            if ($uuid->is($type)){
                return sprintf($uri, $uuid->getId());
            }
        }
        throw new Exception\MatchingException(sprintf('Could not find a route for %s', $id));
    }
}