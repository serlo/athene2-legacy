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
namespace Link\Manager;

interface SharedLinkManagerInterface
{

    /**
     *
     * @param string $name            
     * @param string $repositoryName            
     * @return LinkManagerInterface
     */
    public function findLinkManagerByName($name, $repositoryName);

    /**
     *
     * @param int $id            
     * @param string $repositoryName            
     * @return LinkManagerInterface
     */
    public function getLinkManager($id, $repositoryName);
}