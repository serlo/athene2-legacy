<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 */
namespace License\Manager;

use Authorization\Service\AuthorizationAssertionTrait;
use ClassResolver\ClassResolverAwareTrait;
use ClassResolver\ClassResolverInterface;
use Common\Traits\ConfigAwareTrait;
use Common\Traits\FlushableTrait;
use Common\Traits\ObjectManagerAwareTrait;
use Doctrine\Common\Persistence\ObjectManager;
use Instance\Entity\InstanceInterface;
use Instance\Manager\InstanceManagerAwareTrait;
use Instance\Manager\InstanceManagerInterface;
use License\Entity\LicenseAwareInterface;
use License\Entity\LicenseInterface;
use License\Exception;
use License\Form\LicenseForm;
use Zend\EventManager\EventManagerAwareTrait;
use ZfcRbac\Service\AuthorizationService;

class LicenseManager implements LicenseManagerInterface
{
    use ClassResolverAwareTrait, ObjectManagerAwareTrait;
    use InstanceManagerAwareTrait, EventManagerAwareTrait;
    use AuthorizationAssertionTrait, FlushableTrait;

    public function __construct(
        AuthorizationService $authorizationService,
        ClassResolverInterface $classResolver,
        InstanceManagerInterface $instanceManager,
        ObjectManager $objectManager
    ) {
        $this->setAuthorizationService($authorizationService);
        $this->classResolver   = $classResolver;
        $this->instanceManager = $instanceManager;
        $this->objectManager   = $objectManager;
    }

    public function injectLicense(LicenseAwareInterface $object, LicenseInterface $license = null)
    {
        if (!$license) {
            $license = $this->getDefaultLicense();
        }
        $object->setLicense($license);
        $this->getObjectManager()->persist($object);
        $this->getEventManager()->trigger('inject', $this, ['object' => $object, 'license' => $license]);
    }

    public function findLicenseByTitleAndInstance($title, InstanceInterface $instance)
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

    public function addLicense(LicenseForm $form)
    {
        $instance = $this->getInstanceManager()->getInstanceFromRequest();
        $this->assertGranted('license.create', $instance);

        if (!$form->isValid()) {
            throw new Exception\RuntimeException(print_r($form->getMessages(), true));
        }

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

    public function getLicense($id)
    {
        $className = $this->getClassResolver()->resolveClassName('License\Entity\LicenseInterface');
        $license   = $this->getObjectManager()->find($className, $id);

        if (!is_object($license)) {
            throw new Exception\LicenseNotFoundException(sprintf('License not found by id `%s`.', $id));
        }

        $this->assertGranted('license.get', $license);

        return $license;
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
        if (!$form->isValid()) {
            throw new Exception\RuntimeException(print_r($form->getMessages(), true));
        }
        $license = $form->getObject();
        $this->assertGranted('license.update', $license);
        $this->getObjectManager()->persist($license);
    }

    public function findAllLicenses()
    {
        $className = $this->getClassResolver()->resolveClassName('License\Entity\LicenseInterface');
        $licenses  = $this->getObjectManager()->getRepository($className)->findAll();
        foreach ($licenses as $license) {
            $this->assertGranted('license.get', $license);
        }
        return $licenses;
    }

    public function findLicensesByInstance(InstanceInterface $instance)
    {
        $className = $this->getClassResolver()->resolveClassName('License\Entity\LicenseInterface');
        $licenses  = $this->getObjectManager()->getRepository($className)->findBy(['instance' => $instance]);
        foreach ($licenses as $license) {
            $this->assertGranted('license.get', $license);
        }
        return $licenses;
    }

    protected function getDefaultLicense()
    {
        $instance  = $this->getInstanceManager()->getInstanceFromRequest();
        $className = $this->getClassResolver()->resolveClassName('License\Entity\LicenseInterface');
        $license   = $this->getObjectManager()->getRepository($className)->findOneBy(
            [
                'default'  => true,
                'instance' => $instance->getId()
            ]
        );

        if (!is_object($license)) {
            throw new Exception\Runtimeexception(sprintf('No default license set for for %s', $license->getName()));
        }

        return $license;
    }
}
