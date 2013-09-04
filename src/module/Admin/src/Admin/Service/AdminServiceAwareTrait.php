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
namespace Admin\Service;

trait AdminServiceAwareTrait
{
    /**
     * 
     * @var AdminServiceInterface
     */
    protected $adminService;
	/**
     * @return AdminServiceInterface $adminService
     */
    public function getAdminService ()
    {
        return $this->adminService;
    }

	/**
     * @param AdminServiceInterface $adminService
     * @return $this
     */
    public function setAdminService (AdminServiceInterface $adminService)
    {
        $this->adminService = $adminService;
        return $this;
    }

}