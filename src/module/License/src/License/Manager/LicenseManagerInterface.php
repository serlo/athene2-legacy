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
namespace License\Manager;

use License\Entity\LicenseInterface;

interface LicenseManagerInterface
{

    /**
     * 
     * @param int $id
     * @return LicenseInterface
     */
    public function getLicense($id);

    /**
     * 
     * @param string $title
     * @param string $url
     * @param string $content
     * @return LicenseInterface
     */
    public function addLicense($title, $url, $content = NULL);

    /**
     * 
     * @param int $id
     * @return $this
     */
    public function removeLicense($id);

    /**
     * 
     * @return LicenseInterface[]
     */
    public function findAllLicenses();
}