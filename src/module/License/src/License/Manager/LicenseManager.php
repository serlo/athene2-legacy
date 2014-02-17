<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright   Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace License\Manager;

use Authorization\Service\AuthorizationAssertionTrait;
use ClassResolver\ClassResolverAwareTrait;
use Common\Traits\ConfigAwareTrait;
use Common\Traits\FlushableTrait;
use Common\Traits\ObjectManagerAwareTrait;
use Instance\Entity\InstanceInterface;
use Instance\Manager\InstanceManagerAwareTrait;
use License\Entity\LicenseAwareInterface;
use License\Entity\LicenseInterface;
use License\Exception;
use License\Form\LicenseForm;

class LicenseManager implements LicenseManagerInterface
{
    use ClassResolverAwareTrait, ObjectManagerAwareTrait;
    use ConfigAwareTrait, InstanceManagerAwareTrait;
    use AuthorizationAssertionTrait, FlushableTrait;

    protected function getDefaultConfig()
    {
        return [
            'defaults' => [
                'Deutsch' => 'cc-by-sa-3.0'
            ]
        ];
    }

    public function injectLicense(LicenseAwareInterface $object, LicenseInterface $license = null)
    {
        if (!$license) {
            $license = $this->getDefaultLicense();
        }
        $object->setLicense($license);
        $this->getObjectManager()->persist($object);
    }

    protected function getDefaultLicense()
    {
        $instance = $this->getInstanceManager()->getInstanceFromRequest();
        $code = $instance->getName();
        $defaults = $this->getDefaultConfig()['defaults'];
        if (!array_key_exists($code, $defaults)) {
            throw new Exception\RuntimeException(sprintf('No default license set for instance `%s`', $code));
        }
        $title = $defaults[$code];

        return $this->findLicenseByTitleAndInterface($title, $instance);
    }

    public function findLicenseByTitleAndInterface($title, InstanceInterface $instance)
    {
        if (!is_string($title)) {
            throw new Exception\InvalidArgumentException(sprintf(
                'Expected parameter 1 to be string, but got `%s`',
                gettype($title)
            ));
        }

        $className = $this->getClassResolver()->resolveClassName('License\Entity\LicenseInterface');
        $license   = $this->getObjectManager()->getRepository($className)->findOneBy(
            [
                'title'    => $title,
                'instance' => $instance->getId()
            ]
        );

        if (!is_object($license)) {
            throw new Exception\LicenseNotFoundException(sprintf(
                'License not found by title `%s` and instance `%s`.',
                $title,
                $instance->getName()
            ));
        }

        return $license;
    }

    public function getLicense($id)
    {
        $className = $this->getClassResolver()->resolveClassName('License\Entity\LicenseInterface');
        $license = $this->getObjectManager()->find($className, $id);

        if (!is_object($license)) {
            throw new Exception\LicenseNotFoundException(sprintf('License not found by id `%s`.', $id));
        }

        return $license;
    }

    /*
     * (non-PHPdoc) @see \License\Manager\LicenseManagerInterface::addLicense()
     */
    public function addLicense(LicenseForm $form, InstanceInterface $instance)
    {
        $this->assertGranted('license.create');

        /* @var $entity \License\Entity\LicenseInterface */
        $entity = $form->getObject();
        $entity->setInstance($instance);
        $form->bind($entity);
        $this->getObjectManager()->persist($entity);
    }

    public function getLicenseForm($id = null)
    {
        if ($id !== null) {
            $license = $this->getLicense($id);
        } else {
            $license = $this->getClassResolver()->resolve('License\Entity\LicenseInterface');
        }
        $form = new LicenseForm();
        $form->bind($license);

        return $form;
    }

    public function removeLicense($id)
    {
        $license = $this->getLicense($id);
        $this->assertGranted('license.purge', $license);

        $this->getObjectManager()->remove($license);
    }

    public function updateLicense(LicenseForm $form)
    {
        $form->bind($form->getObject());
        $license = $form->getObject();

        $this->assertGranted('license.update', $license);

        $this->getObjectManager()->persist($license);
    }

    /*
     * (non-PHPdoc) @see \License\Manager\LicenseManagerInterface::findAllLicenses()
     */
    public function findAllLicenses()
    {
        $className = $this->getClassResolver()->resolveClassName('License\Entity\LicenseInterface');

        return $this->getObjectManager()->getRepository($className)->findAll();
    }

    public function findLicensesByInstance(InstanceInterface $instanceService)
    {
        $className = $this->getClassResolver()->resolveClassName('License\Entity\LicenseInterface');

        return $this->getObjectManager()->getRepository($className)->findBy(
            [
                'instance' => $instanceService
            ]
        );
    }
}
