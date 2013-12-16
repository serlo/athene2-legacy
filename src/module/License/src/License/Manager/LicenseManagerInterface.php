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
use Language\Model\LanguageModelInterface;
use License\Form\LicenseForm;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use License\Entity\LicenseAwareInterface;
use Common\ObjectManager\Flushable;

interface LicenseManagerInterface extends ObjectManagerAwareInterface, Flushable
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
     * @param LanguageModelInterface $language
     */
    public function addLicense(LicenseForm $form, LanguageModelInterface $language);

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
     * @param LanguageModelInterface $languageService
     * @return LicenseInterface[]
     */
    public function findLicensesByLanguage(LanguageModelInterface $languageService);
    
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
    
    /**
     * 
     * @param LicenseAwareInterface $object
     * @param LicenseInterface $license
     * @return $this
     */
    public function injectLicense(LicenseAwareInterface $object, LicenseInterface $license = NULL);
}