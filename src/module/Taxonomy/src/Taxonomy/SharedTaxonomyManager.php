<?php
/**
 *
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @copyright 2013 by www.serlo.org
 * @license LGPL
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */
namespace Taxonomy;

use Core\Service\LanguageService;
use Taxonomy\Exception\NotFoundException;
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
        $termEntityClassName = $this->resolve('TermTaxonomyEntityInterface');
        
        if (is_numeric($taxonomy)) {
            $entity = $this->getObjectManager()->find($this->resolve('TaxonomyEntityInterface'), $taxonomy);
            $name = $this->add($this->createInstanceFromEntity($entity));
        } elseif (is_string($taxonomy)) {
            $entity = $this->getObjectManager()
                ->getRepository($this->resolve('TaxonomyEntityInterface'))
                ->findOneBy(array(
                'name' => $taxonomy
            // 'subject' => $subjectService ? $subjectService->getEntity() : $subjectService
                        ));
            $name = $this->add($this->createInstanceFromEntity($entity));
        } elseif ($taxonomy instanceof $className) {
            $name = $this->add($taxonomy);
        } elseif ($taxonomy instanceof $entityClassName) {
            $name = $this->add($this->createInstanceFromEntity($taxonomy));
        } elseif ($taxonomy instanceof $termEntityClassName) {
            return $this->getTerm($taxonomy);
        } else {
            throw new \Exception();
        }
        if (! $this->hasInstance($name)) {
            throw new \Exception();
        }
        return $this->getInstance($name);
    }

    public function getTerm ($element)
    {
        $termEntityClassName = $this->resolve('TermTaxonomyEntityInterface');
        if (is_numeric($element)) {
            $entity = $this->getObjectManager()->find($this->resolve('TermTaxonomyEntityInterface'), (int) $element);
            if (! is_object($entity))
                throw new NotFoundException();
            $entity = $entity->getTaxonomy();
            $name = $this->add($this->createInstanceFromEntity($entity));
        } elseif ($element instanceof $termEntityClassName) {
            $name = $this->add($this->createInstanceFromEntity($element->getTaxonomy()));
        } else {
            throw new \InvalidArgumentException();
        }
        return $this->getInstance($name)->get($element);
    }

    public function deleteTerm ($id)
    {
        $term = $this->getTerm($id);
        $term->getManager()->delete($term);
    }

    protected function createInstanceFromEntity ($entity)
    {
        if (! is_object($entity))
            throw new NotFoundException();
        
        $instance = parent::createInstance();
        $instance->setEntity($entity);
        $instance->setManager($this);
        return $instance;
    }
}