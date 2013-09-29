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
namespace Language\Manager;

use Language\Entity\LanguageInterface;
use Language\Exception;

class LanguageManager implements LanguageManagerInterface
{
    use \Common\Traits\ObjectManagerAwareTrait,\Common\Traits\InstanceManagerTrait;

    private $fallBackLanguageId = 1;

    public function setFallBackLanguage($id){
        $this->fallBackLanguageId = $id;
        return $this;
    }
    
    public function getFallbackLanugage()
    {
        return $this->getLanguage($this->fallBackLanguageId);
    }

    public function getLanguageFromRequest()
    {
        return $this->getFallbackLanugage();
    }

    public function getLanguage($id)
    {
        if (! is_numeric($id))
            throw new Exception\InvalidArgumentException(sprintf('Expected int but got %s', gettype($id)));
        
        if (! $this->hasInstance($id)) {
            $language = $this->getObjectManager()->find($this->getClassResolver()
                ->resolveClassName('Language\Entity\LanguageInterface'), $id);
            
            if (! is_object($language))
                throw new Exception\LanguageNotFoundException(sprintf('Language %s could not be found', $id));
            
            $this->addInstance($language->getId(), $this->createService($language));
        }
        
        return $this->getInstance($id);
    }

    public function findLanguageByCode($code)
    {
        if (! is_string($code))
            throw new Exception\InvalidArgumentException(sprintf('Expected string but got %s', gettype($code)));
        
        $language = $this->getObjectManager()
            ->getRepository($this->getClassResolver()
            ->resolveClassName('Language\Entity\LanguageInterface'))
            ->findOneBy(array(
            'code' => $code
        ));
        
        if (! is_object($language))
            throw new Exception\LanguageNotFoundException(sprintf('Language %s could not be found', $code));
        
        if (! $this->hasInstance($language->getId())) {
            $this->addInstance($language->getId(), $this->createService($language));
        }
        
        return $this->getInstance($language->getId());
    }

    protected function createService(LanguageInterface $entity)
    {
        $instance = $this->createInstance('Language\Service\LanguageServiceInterface');
        $instance->setEntity($entity);
        return $instance;
    }
}