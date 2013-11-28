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

use License\Exception;

class LicenseManager implements LicenseManagerInterface
{
    use \Common\Traits\InstanceManagerTrait, \Common\Traits\ObjectManagerAwareTrait;
    
	/* (non-PHPdoc)
     * @see \License\Manager\LicenseManagerInterface::getLicense()
     */
    public function getLicense ($id)
    {
        if(is_numeric($id))
            throw new Exception\InvalidArgumentException(sprintf('Expected parameter 1 to be numeric, but got `%s`', gettype($id)));
    }

	/* (non-PHPdoc)
     * @see \License\Manager\LicenseManagerInterface::addLicense()
     */
    public function addLicense ($title, $url, $content = NULL)
    {
        // TODO Auto-generated method stub
        
    }

	/* (non-PHPdoc)
     * @see \License\Manager\LicenseManagerInterface::removeLicense()
     */
    public function removeLicense ($id)
    {
        // TODO Auto-generated method stub
        
    }

	/* (non-PHPdoc)
     * @see \License\Manager\LicenseManagerInterface::findAllLicenses()
     */
    public function findAllLicenses ()
    {
        // TODO Auto-generated method stub
        
    }

}