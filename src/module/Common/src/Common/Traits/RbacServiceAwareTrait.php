<?php
namespace Common\Traits;

use ZfcRbac\Service\Rbac;

trait RbacServiceAwareTrait
{

    /**
     *
     * @var Rbac
     */
    protected $rbacService;

    /**
     *
     * @return \ZfcRbac\Service\Rbac $rbacService
     */
    public function getRbacService()
    {
        return $this->rbacService;
    }

    /**
     *
     * @param \ZfcRbac\Service\Rbac $rbacService            
     * @return self
     */
    public function setRbacService($rbacService)
    {
        $this->rbacService = $rbacService;
        return $this;
    }
}