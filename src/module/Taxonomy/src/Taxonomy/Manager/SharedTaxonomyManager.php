<?php
/**
 *
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @copyright 2013 by www.serlo.org
 * @license LGPL
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */
namespace Taxonomy\Manager;

use Taxonomy\Exception\NotFoundException;

class SharedTaxonomyManager extends AbstractManager implements SharedTaxonomyManagerInterface
{

    use \Common\Traits\ObjectManagerAwareTrait, \Language\Manager\LanguageManagerAwareTrait;

    protected $options = array();
    
    public function __construct ($options)
    {
        $this->options = array_merge_recursive($this->options, $options);
        parent::__construct();
    }

    public function add (TermManagerInterface $termManager)
    {
        $this->addInstance($termManager->getId(), $termManager);
        return $termManager->getId();
    }

    public function get ($taxonomy, $language = NULL)
    {
        $className = $this->resolve('Taxonomy\Manager\TermManagerInterface');
        $entityClassName = $this->resolve('Taxonomy\Entity\TaxonomyEntityInterface');
        $termEntityClassName = $this->resolve('Taxonomy\Entity\TermTaxonomyEntityInterface');
        
        if(!$language)
            $language = $this->getLanguageManager()->getRequestLanguage();
        
        if (is_numeric($taxonomy)) {
            $entity = $this->getObjectManager()->find($this->resolve('Taxonomy\Entity\TaxonomyEntityInterface'), $taxonomy);
            $name = $this->add($this->createInstanceFromEntity($entity));
        } elseif (is_string($taxonomy)) {
            $type = $this->getObjectManager()
                ->getRepository($this->resolve('Taxonomy\Entity\TaxonomyTypeInterface'))
                ->findOneBy(array(
                'name' => $taxonomy
            ));
            $entity = $this->getObjectManager()
                ->getRepository($this->resolve('Taxonomy\Entity\TaxonomyEntityInterface'))
                ->findOneBy(array(
                'language' => $language->getId(),
                'type' => $type->getId(),
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
        $termEntityClassName = $this->resolve('Taxonomy\Entity\TermTaxonomyEntityInterface');
        if (is_numeric($element)) {
            $entity = $this->getObjectManager()->find($this->resolve('Taxonomy\Entity\TermTaxonomyEntityInterface'), (int) $element);
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
        
        $instance = parent::createInstance('Taxonomy\Manager\TermManagerInterface');
        $instance->setEntity($entity);
        $instance->setSharedTaxonomyManager($this);
        $instance->setOptions($this->options[$entity->getType()->getName()]);
        return $instance;
    }
}