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

use Language\Entity\LanguageEntityInterface;
use Language\Exception;
use Doctrine\Common\Collections\ArrayCollection;
use Language\Collection\LanguageCollection;

class LanguageManager implements LanguageManagerInterface
{
    use \Common\Traits\ObjectManagerAwareTrait,\Common\Traits\InstanceManagerTrait;

    private $fallBackLanguageId = 1;

    /**
     *
     * @var \Language\Service\LanguageServiceInterface
     */
    protected $requestLanguage;

    public function setFallBackLanguage($id)
    {
        $this->fallBackLanguageId = $id;
        return $this;
    }
    
    public function findAllLanguages(){
        $collection = $this->getObjectManager()->getRepository($this->getClassResolver()->resolveClassName('Language\Entity\LanguageEntityInterface'))->findAll();
        $collection = new ArrayCollection($collection);
        return new LanguageCollection($collection, $this);
    }

    public function getFallbackLanugage()
    {
        return $this->getLanguage($this->fallBackLanguageId);
    }

    public function getLanguageFromRequest()
    {
        if (! array_key_exists('HTTP_HOST', (array) $_SERVER))
            $this->requestLanguage = $this->getFallbackLanugage();
        
        if (! $this->requestLanguage) {
            $subdomain = explode('.', $_SERVER['HTTP_HOST'])[0];
            
            try {
                $this->requestLanguage = $this->findLanguageByCode($subdomain);
            } catch (Exception\LanguageNotFoundException $e) {
                $this->requestLanguage = $this->getFallbackLanugage();
            }
        }
        return $this->requestLanguage;
    }

    public function getLanguage($id)
    {
        if (! is_numeric($id))
            throw new Exception\InvalidArgumentException(sprintf('Expected int but got %s', gettype($id)));
        
        if (! $this->hasInstance($id)) {
            $language = $this->getObjectManager()->find($this->getClassResolver()
                ->resolveClassName('Language\Entity\LanguageEntityInterface'), $id);
            
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
            ->resolveClassName('Language\Entity\LanguageEntityInterface'))
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

    protected function createService(LanguageEntityInterface $entity)
    {
        $instance = $this->createInstance('Language\Service\LanguageServiceInterface');
        $instance->setEntity($entity);
        return $instance;
    }
}