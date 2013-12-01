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
namespace Entity\Plugin\License;

use Entity\Plugin\AbstractPlugin;

class LicensePlugin extends AbstractPlugin
{
    use\License\Manager\LicenseManagerAwareTrait;

    public function getDefaultConfig()
    {
        return array();
    }

    public function inject()
    {
        $this->getLicenseManager()->injectLicense($this->getEntityService()
            ->getEntity());
    }
}