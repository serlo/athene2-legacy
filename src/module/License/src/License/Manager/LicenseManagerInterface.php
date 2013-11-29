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
use Language\Service\LanguageServiceInterface;
use License\Form\LicenseForm;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;

interface LicenseManagerInterface extends ObjectManagerAwareInterface
{

    /**
     * 
     * @param int $id
     * @return LicenseInterface
     */
    public function getLicense($id);
    
    /**
     * 
     * @param LicenseForm $form
     * @param LanguageServiceInterface $language
     */
    public function addLicense(LicenseForm $form, LanguageServiceInterface $language);

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
    
    /**
     * 
     * @param LanguageServiceInterface $languageService
     * @return LicenseInterface[]
     */
    public function findLicensesByLanguage(LanguageServiceInterface $languageService);
    
    /**
     * 
     * @param LicenseForm $form
     * @return $this
     */
    public function updateLicense(LicenseForm $form);
    
    /**
     * 
     * @param int $id
     * @return LicenseForm
     */
    public function getLicenseForm($id = NULL);
}