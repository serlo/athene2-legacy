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
use License\Form\LicenseForm;
use Language\Service\LanguageServiceInterface;
use License\Entity\LicenseAwareInterface;
use License\Entity\LicenseInterface;

class LicenseManager implements LicenseManagerInterface
{
    use \Common\Traits\InstanceManagerTrait,\Common\Traits\ObjectManagerAwareTrait,\Common\Traits\ConfigAwareTrait,\Language\Manager\LanguageManagerAwareTrait;

    protected function getDefaultConfig()
    {
        return array(
            'defaults' => array(
                'de' => 'cc-by-sa-3.0'
            )
        );
    }

    public function injectLicense(LicenseAwareInterface $object, LicenseInterface $license = NULL)
    {
        if (! $license) {
            $license = $this->getDefaultLicense();
        }
        $object->setLicense($license);
        $this->getObjectManager()->persist($object);
        return $this;
    }

    protected function getDefaultLicense()
    {
        $language = $this->getLanguageManager()->getLanguageFromRequest();
        $code = $language->getCode();
        $defaults = $this->getDefaultConfig()['defaults'];
        if(!array_key_exists($code, $defaults))
            throw new Exception\RuntimeException(sprintf('No default license set for language `%s`', $code));
        $title = $defaults[$code];
        return $this->findLicenseByTitleAndLanguage($title, $language);
    }
    
    public function findLicenseByTitleAndLanguage($title, LanguageServiceInterface $language){
        if (! is_string($title))
            throw new Exception\InvalidArgumentException(sprintf('Expected parameter 1 to be string, but got `%s`', gettype($title)));
        
        $license = $this->getObjectManager()->getRepository($this->getClassResolver()->resolveClassName('License\Entity\LicenseInterface'))->findOneBy(array(
            'title' => $title,
            'language' => $language->getId()
        ));
        
        if (! is_object($license))
            throw new Exception\LicenseNotFoundException(sprintf('License not found by title `%s` and language `%s`.', $title, $language->getCode()));
        
        return $license;
    }
    
    /*
     * (non-PHPdoc) @see \License\Manager\LicenseManagerInterface::getLicense()
     */
    public function getLicense($id)
    {
        if (! is_numeric($id))
            throw new Exception\InvalidArgumentException(sprintf('Expected parameter 1 to be numeric, but got `%s`', gettype($id)));
        
        $className = $this->getClassResolver()->resolveClassName('License\Entity\LicenseInterface');
        $license = $this->getObjectManager()->find($className, $id);
        
        if (! is_object($license))
            throw new Exception\LicenseNotFoundException(sprintf('License not found by id `%s`.', $id));
        
        return $license;
    }
    
    /*
     * (non-PHPdoc) @see \License\Manager\LicenseManagerInterface::addLicense()
     */
    public function addLicense(LicenseForm $form, LanguageServiceInterface $languageService)
    {
        /* @var $entity \License\Entity\LicenseInterface */
        $entity = $form->getObject();
        $entity->setLanguage($languageService->getEntity());
        $form->bind($entity);
        $this->getObjectManager()->persist($entity);
        return $this;
    }

    public function getLicenseForm($id = NULL)
    {
        if ($id !== NULL) {
            $license = $this->getLicense($id);
        } else {
            $license = $this->getClassResolver()->resolve('License\Entity\LicenseInterface');
        }
        $form = new LicenseForm();
        $form->bind($license);
        return $form;
    }
    
    /*
     * (non-PHPdoc) @see \License\Manager\LicenseManagerInterface::removeLicense()
     */
    public function removeLicense($id)
    {
        $license = $this->getLicense($id);
        $this->getObjectManager()->remove($license);
        return $this;
    }

    public function updateLicense(LicenseForm $form)
    {
        $form->bind($form->getObject());
        $license = $form->getObject();
        $this->getObjectManager()->persist($license);
        return $this;
    }
    
    /*
     * (non-PHPdoc) @see \License\Manager\LicenseManagerInterface::findAllLicenses()
     */
    public function findAllLicenses()
    {
        $className = $this->getClassResolver()->resolveClassName('License\Entity\LicenseInterface');
        return $this->getObjectManager()
            ->getRepository($className)
            ->findAll();
    }

    public function findLicensesByLanguage(LanguageServiceInterface $languageService)
    {
        $className = $this->getClassResolver()->resolveClassName('License\Entity\LicenseInterface');
        return $this->getObjectManager()
            ->getRepository($className)
            ->findBy(array(
            'language' => $languageService->getEntity()
        ));
    }
}