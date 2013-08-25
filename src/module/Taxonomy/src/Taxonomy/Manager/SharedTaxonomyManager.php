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
use Taxonomy\Exception\InvalidArgumentException;
use Language\Service\LanguageServiceInterface;

class SharedTaxonomyManager extends AbstractManager implements SharedTaxonomyManagerInterface
{
    
    use\Common\Traits\ObjectManagerAwareTrait,\Language\Manager\LanguageManagerAwareTrait;

    /**
     *
     * @var array
     */
    protected $config;

    /**
     * Constructor
     *
     * @param array $config            
     */
    public function __construct(\Zend\Config\Config $config)
    {
        $config = $config->toArray();
        if (! is_array($this->config))
            $this->config = array();
        
        $this->config = array_merge($this->config, $config);
    }

    public function add(TermManagerInterface $termManager)
    {
        $this->addInstance($termManager->getId(), $termManager);
        return $termManager->getId();
    }

    public function get($taxonomy, $language = NULL)
    {
        $className = $this->resolveClassName('Taxonomy\Manager\TermManagerInterface');
        $entityClassName = $this->resolveClassName('Taxonomy\Entity\TaxonomyEntityInterface');
        $termEntityClassName = $this->resolveClassName('Taxonomy\Entity\TermTaxonomyEntityInterface');
        
        if (!$language instanceof LanguageServiceInterface)
            $language = $this->getLanguageManager()->get($language);
        
        if (is_numeric($taxonomy)) {
            $entity = $this->getObjectManager()->find($this->resolveClassName('Taxonomy\Entity\TaxonomyEntityInterface'), $taxonomy);
            $name = $this->add($this->createInstanceFromEntity($entity));
        } elseif (is_string($taxonomy)) {
            
            $type = $this->getObjectManager()
                ->getRepository($this->resolveClassName('Taxonomy\Entity\TaxonomyTypeInterface'))
                ->findOneBy(array(
                'name' => $taxonomy
            ));
            
            if (! is_object($type))
                throw new InvalidArgumentException(sprintf('Taxonomy type %s not found', $taxonomy));
            
            $entity = $this->getObjectManager()
                ->getRepository($this->resolveClassName('Taxonomy\Entity\TaxonomyEntityInterface'))
                ->findOneBy(array(
                'language' => $language->getId(),
                'type' => $type->getId()
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

    public function getTerm($element)
    {
        $termEntityClassName = $this->resolveClassName('Taxonomy\Entity\TermTaxonomyEntityInterface');
        if (is_numeric($element)) {
            $entity = $this->getObjectManager()->find($this->resolveClassName('Taxonomy\Entity\TermTaxonomyEntityInterface'), (int) $element);
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

    public function deleteTerm($id)
    {
        $term = $this->getTerm($id);
        $term->getManager()->delete($term);
    }

    protected function createInstanceFromEntity($entity)
    {
        if (! is_object($entity))
            throw new NotFoundException();
        
        $instance = parent::createInstance('Taxonomy\Manager\TermManagerInterface');
        $instance->setEntity($entity);
        $instance->setSharedTaxonomyManager($this);
        $instance->setOptions($this->config[$entity->getType()
            ->getName()]);
        return $instance;
    }
}