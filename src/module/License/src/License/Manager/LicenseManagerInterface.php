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

use Common\ObjectManager\Flushable;
use Instance\Entity\InstanceInterface;
use License\Entity\LicenseAwareInterface;
use License\Entity\LicenseInterface;
use License\Form\LicenseForm;

interface LicenseManagerInterface extends Flushable
{

    /**
     * @param int $id
     * @return LicenseInterface
     */
    public function getLicense($id);

    /**
     * @param LicenseForm       $form
     * @param InstanceInterface $instance
     */
    public function addLicense(LicenseForm $form, InstanceInterface $instance);

    /**
     * @param int $id
     * @return void
     */
    public function removeLicense($id);

    /**
     * @return LicenseInterface[]
     */
    public function findAllLicenses();

    /**
     * @param InstanceInterface $instanceService
     * @return LicenseInterface[]
     */
    public function findLicensesByLanguage(InstanceInterface $instanceService);

    /**
     * @param LicenseForm $form
     * @return void
     */
    public function updateLicense(LicenseForm $form);

    /**
     * @param int $id
     * @return LicenseForm
     */
    public function getLicenseForm($id = null);

    /**
     * @param LicenseAwareInterface $object
     * @param LicenseInterface      $license
     * @return void
     */
    public function injectLicense(LicenseAwareInterface $object, LicenseInterface $license = null);
}