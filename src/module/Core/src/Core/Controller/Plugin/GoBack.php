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
namespace Core\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class GoBack extends AbstractPlugin
{

    function __invoke ($default = '/')
    {
        $ref = $this->getController()->getRequest()
            ->getHeader('Referer')
            ->getUri();
        if(!$ref)
            $ref = $default;
        $this->getController()->redirect()->toUrl($ref);
    }
}