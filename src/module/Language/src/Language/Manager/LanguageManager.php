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
use Language\Service\LanguageServiceInterface;
use Language\Exception\LanguageNotFoundException;
use Language\Exception\InvalidArgumentException;

class LanguageManager implements LanguageManagerInterface {
    use \Common\Traits\ObjectManagerAwareTrait, \ClassResolver\ClassResolverAwareTrait;
    
	private $fallBackLanguageId = 1;
	
	private $languages = array();
	
	public function getFallbackLanugage(){
		return $this->get($this->fallBackLanguageId);
	}
	
	private function add(LanguageServiceInterface $languageService){
		$this->languages[$languageService->getId()] = $languageService;
		return $this;
	}
	
	public function getLanguageFromRequest(){
		return $this->getFallbackLanugage();
	}
	
	public function getLanguage($language = NULL){
	    return $this->get($language);
	}
	
	public function get($language = NULL){
	    $id = $language;
	    if($language === NULL){
	        $language = $this->getFallbackLanugage();
	    } elseif(is_numeric($language)){
	        $language = $this->getObjectManager()->find($this->getClassResolver()->resolveClassName('Language\Entity\LanguageInterface'), (int) $language);
	    } elseif(is_string($language)){
	        $language = $this->getObjectManager()->getRepository($this->getClassResolver()->resolveClassName('Language\Entity\LanguageInterface'))->findOneByCode($language);
	    } elseif ($language instanceof \Language\Entity\LanguageInterface){
	       $id = $language->getId();
	    } else {
	        throw new InvalidArgumentException();
	    }
	    
	    if(!is_object($language))
	        throw new LanguageNotFoundException($id);
	    
		if(!$this->has($language)){
		    return $this->createInstance($language);
		}
		
		return $this->languages[$language->getId()];
	}
	
	public function has($language){
	    if(!is_numeric($language)){
	        $language = $language->getId();
	    }
	    return isset($this->languages[$language]) && is_object($this->languages[$language]);
	}
	
	public function createInstance(LanguageInterface $entity){
	    $instance = $this->getClassResolver()->resolve('Language\Service\LanguageServiceInterface');
        $instance->setEntity($entity);
	    $this->add($instance);
	    return $instance;
	}
}