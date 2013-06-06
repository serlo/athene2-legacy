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
     *
     * @var LanguageService
     */
    protected $languageService;

    /**
     *
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    protected $objectManager;

    protected $options = array(
        'instances' => array(
            'manages' => 'Taxonomy\TermManager',
            'TaxonomyEntityInterface' => 'Taxonomy\Entity\Taxonomy',
            'TermTaxonomyEntityInterface' => 'Taxonomy\Entity\TermTaxonomy',
            'TermManagerInterface' => 'Taxonomy\TermManager'
        )
    );

    public function __construct ()
    {
        parent::__construct($this->options);
    }

    /**
     *
     * @return \Core\Service\LanguageService $languageService
     */
    public function getLanguageService ()
    {
        return $this->languageService;
    }

    /**
     *
     * @param \Core\Service\LanguageService $languageService            
     * @return $this
     */
    public function setLanguageService (LanguageService $languageService)
    {
        $this->languageService = $languageService;
        return $this;
    }
    
    /*
     * (non-PHPdoc) @see \DoctrineModule\Persistence\ObjectManagerAwareInterface::getObjectManager()
     */
    public function getObjectManager ()
    {
        return $this->objectManager;
    }
    
    /*
     * (non-PHPdoc) @see \DoctrineModule\Persistence\ObjectManagerAwareInterface::setObjectManager()
     */
    public function setObjectManager (\Doctrine\Common\Persistence\ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
        return $this;
    }

    public function add (TermManagerInterface $termManager)
    {
        $this->addInstance($termManager->getId(), $termManager);
        return $termManager->getId();
    }

    public function get ($taxonomy)
    {
        $className = $this->resolve('manages');
        $entityClassName = $this->resolve('TaxonomyEntityInterface');
        
        if (is_numeric($taxonomy)) {
            $entity = $this->getObjectManager()->find($this->resolve('TaxonomyEntityInterface'), $taxonomy);
            $name = $this->add($this->createInstance($entity));
        } elseif (is_string($taxonomy)) {
            $entity = $this->getObjectManager()
                ->getRepository($this->resolve('TaxonomyEntityInterface'))
                ->findOneBy(array(
                'name' => $taxonomy
            // 'subject' => $subjectService ? $subjectService->getEntity() : $subjectService
                        ));
            $name = $this->add($this->createInstance($entity));
        } elseif ($taxonomy instanceof $className) {
            $name = $this->add($taxonomy);
        } elseif ($taxonomy instanceof $entityClassName) {
            $name = $this->add($this->createInstance($taxonomy));
        } else {
            throw new \Exception();
        }
        if (! $this->hasInstance($name)) {
            throw new \Exception();
        }
        return $this->getInstance($name);
    }

    public function getTerm ($id)
    {
        if (! is_numeric($id))
            throw new \InvalidArgumentException();
        
        $entity = $this->getObjectManager()->find($this->resolve('TermTaxonomyEntityInterface'), (int) $id);
        
        if (! is_object($entity))
            throw new NotFoundException();
        
        $entity = $entity->getTaxonomy();
        
        $name = $this->add($this->createInstance($entity));
        return $this->getInstance($name)->get($id);
    }
    
    public function deleteTerm($id){
        $term = $this->getTerm($id);
        $term->getManager()->delete($term);
    }

    protected function createInstance ($entity)
    {
        if(!is_object($entity))
            throw new NotFoundException();
        
        $instance = parent::createInstance();
        $instance->setEntity($entity);
        return $instance;
    }
}