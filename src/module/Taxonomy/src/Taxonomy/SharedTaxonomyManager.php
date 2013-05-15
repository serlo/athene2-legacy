<?php
/**
 *
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @copyright 2013 by www.serlo.org
 * @license LGPL
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */
namespace Taxonomy;

use Doctrine\ORM\EntityManager;
use Core\Service\LanguageService;
use Taxonomy\Exception\NotFoundException;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Taxonomy\Factory\EntityTaxonomy;
use Taxonomy\Entity\Taxonomy;
use Core\AbstractManager;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;

class SharedTaxonomyManager extends AbstractManager implements SharedTaxonomyManagerInterface, ObjectManagerAwareInterface
{
    /**
     * @var LanguageService
     */
    protected $languageService;
    
    /**
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    protected $objectManager;
    
    protected $defaultOptions = array(
        'instance' => array(
            'manages' => 'Taxonomy\TaxonomyManagerInterface',
            'TaxonomyEntityInterface' => 'Taxonomy\Entity\TaxonomyInterface',
            'TermManagerInterface' => 'Taxonomy\TermManagerInteface'
        )
    );
    
    /**
     * @return \Core\Service\LanguageService $languageService
     */
    public function getLanguageService ()
    {
        return $this->languageService;
    }

	/**
     * @param \Core\Service\LanguageService $languageService
     * @return $this
     */
    public function setLanguageService (LanguageService $languageService)
    {
        $this->languageService = $languageService;
        return $this;
    }

	/* (non-PHPdoc)
     * @see \DoctrineModule\Persistence\ObjectManagerAwareInterface::getObjectManager()
     */
    public function getObjectManager ()
    {
        return $this->objectManager;
    }

	/* (non-PHPdoc)
     * @see \DoctrineModule\Persistence\ObjectManagerAwareInterface::setObjectManager()
     */
    public function setObjectManager (\Doctrine\Common\Persistence\ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
        return $this;
    }

	public function add(TermManagerInterface $taxonomyManager){
        $this->addInstance($taxonomyManager->getId(), $taxonomyManager);
        return $taxonomy->getId();
    }
    
    public function get($taxonomy, $languageService = NULL, $subjectService = NULL){
        if(!$languageService)
            $languageService = $this->getLanguageService();
        
        $className = $this->resolve('manages');
        if(is_numeric($taxonomy)){
            $entity = $this->getObjectManager()->find($this->resolve('TaxonomyEntityInterface'),taxonomy);
            $name = $this->add($this->createInstance($entity));
        } else if (is_string($taxonomy)){ 
            $entity = $this->getObjectManager()->getRepository($this->resolve('TaxonomyEntityInterface'))->findOneBy(array('name' => taxonomy, 'language' => $languageService->getEntity(), 'subject' => $subjectService->getEntity()));
            $name = $this->add($this->createInstance($entity));
        } else if (!$taxonomy instanceof $className) {
            $name = $this->add($taxonomy);
        } else {
            throw new \Exception();
        }
        if(!$this->hasInstance($taxonomy)){
            throw new \Exception();            
        }
        return $this->getInstance($name);
    }
    
    protected function createInstance($entity){
        $instance = parent::createInstance();
        $instance->setEntity($entity);
        return $instance;
    }
}