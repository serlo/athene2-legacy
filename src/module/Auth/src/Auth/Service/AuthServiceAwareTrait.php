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
namespace Auth\Service;

trait AuthServiceAwareTrait
{

    /**
     *
     * @var AuthServiceInterface
     */
    protected $authService;

    /**
     *
     * @return \Auth\Service\AuthServiceInterface
     *         $authService
     */
    public function getAuthService ()
    {
        return $this->authService;
    }

    /**
     *
     * @param \Auth\Service\AuthServiceInterface $authService            
     * @return $this
     */
    public function setAuthService ($authService)
    {
        $this->authService = $authService;
        return $this;
    }
}