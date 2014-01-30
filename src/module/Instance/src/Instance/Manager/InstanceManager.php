<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author    Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license   LGPL-3.0
 * @license   http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link      https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Instance\Manager;

use ClassResolver\ClassResolverAwareTrait;
use Common\Traits\ObjectManagerAwareTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Instance\Exception;

class InstanceManager implements InstanceManagerInterface
{
    use ObjectManagerAwareTrait, ClassResolverAwareTrait;

    private $defaultTenant = 1;

    /**
     * @var \Language\Entity\TenantInterface
     */
    protected $requestTenant;

    public function setDefaultTenant($id)
    {
        $this->defaultTenant = $id;

        return $this;
    }

    public function findAllTenants()
    {
        $collection = $this->getObjectManager()->getRepository(
            $this->getClassResolver()->resolveClassName('Language\Entity\LanguageInterface')
        )->findAll();

        return new ArrayCollection($collection);
    }

    public function getDefaultTenant()
    {
        return $this->getTenant($this->defaultTenant);
    }

    public function getTenantFromRequest()
    {
        if (!array_key_exists('HTTP_HOST', (array)$_SERVER)) {
            $this->requestTenant = $this->getDefaultTenant();
        }

        if (!$this->requestTenant) {
            $subdomain = explode('.', $_SERVER['HTTP_HOST'])[0];

            try {
                $this->requestTenant = $this->findTenantByName($subdomain);
            } catch (Exception\InstanceNotFoundException $e) {
                $this->requestTenant = $this->getDefaultTenant();
            }
        }

        return $this->requestTenant;
    }

    public function getTenant($id)
    {
        $className = $this->getClassResolver()->resolveClassName('Language\Entity\LanguageInterface');

        $instance = $this->getObjectManager()->find($className, $id);

        if (!is_object($instance)) {
            throw new Exception\InstanceNotFoundException(sprintf('Language %s could not be found', $id));
        }

        return $instance;
    }

    public function findTenantByName($name)
    {
        if (!is_string($name)) {
            throw new Exception\InvalidArgumentException(sprintf('Expected string but got %s', gettype($name)));
        }

        $instance = $this->getObjectManager()->getRepository(
            $this->getClassResolver()->resolveClassName('Language\Entity\LanguageInterface')
        )->findOneBy(
                array(
                    'code' => $name
                )
            );

        if (!is_object($instance)) {
            throw new Exception\InstanceNotFoundException(sprintf('Language %s could not be found', $name));
        }

        return $instance;
    }
}
