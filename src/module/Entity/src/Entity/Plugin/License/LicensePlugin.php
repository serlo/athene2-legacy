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
        return $this;
    }
    
    public function setLicense($id)
    {
        $license = $this->getLicenseManager()->getLicense($id);
        $this->getLicenseManager()->injectLicense($this->getEntityService()->getEntity(), $license);
        return $this;
    }

    public function getId()
    {
        return $this->getLicense()->getId();
    }

    public function getTitle()
    {
        return $this->getLicense()->getTitle();
    }

    public function getIcon()
    {
        return $this->getLicense()->getIconHref();
    }

    public function getUrl()
    {
        return $this->getLicense()->getUrl();
    }

    public function getLicense()
    {
        return $this->getLicenseManager()->getLicense($this->getEntityService()
            ->getEntity()
            ->getLicense()
            ->getId());
    }
    
    public function getLicenses($languageService)
    {
        return $this->getLicenseManager()->findLicensesByLanguage($languageService);
    }
}