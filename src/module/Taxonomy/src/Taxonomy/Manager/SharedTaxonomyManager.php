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
use Taxonomy\Exception\ConfigNotFoundException;

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
        } elseif ($taxonomy instanceof $className) {
            $entity = $taxonomy->getEntity();
        } elseif ($taxonomy instanceof $entityClassName) {
            $entity = $taxonomy;
        } elseif ($taxonomy instanceof $termEntityClassName) {
            return $this->getTerm($taxonomy);
        } else {
            throw new InvalidArgumentException();
        }
        
        if (! is_object($entity))
            throw new InvalidArgumentException(sprintf('Taxonomy type %s not found', $taxonomy));
        
        if(!$this->has($entity)){
            $this->add($this->createInstanceFromEntity($entity));
        }
        
        $name = $entity->getId();
        return $this->getInstance($name);
    }

    public function getTerm($element)
    {
        $termEntityClassName = $this->resolveClassName('Taxonomy\Entity\TermTaxonomyEntityInterface');
        if (is_numeric($element)) {
            $entity = $this->getObjectManager()->find($termEntityClassName, (int) $element)->getTaxonomy();
        } elseif ($element instanceof $termEntityClassName) {
            $entity = $element->getTaxonomy();
        } else {
            throw new \InvalidArgumentException();
        }
        
        if (! is_object($entity))
            throw new NotFoundException();
        
        return $this->get($entity)->get($element);
    }
    
    public function has($entity){
        return $this->hasInstance($entity->getId());
    }

    public function deleteTerm($id)
    {
        $term = $this->getTerm($id);
        $term->getManager()->delete($term);
    }
    
    public function getCallback($link){
        if(!array_key_exists($link, $this->config['links']))
            throw new InvalidArgumentException(sprintf('Callback for type %s not found', $link));
        
        return $this->config['links'][$link];
    }

    protected function createInstanceFromEntity($entity)
    {
        if (! is_object($entity))
            throw new NotFoundException();
        
        if(! isset($this->config['types'][$entity->getType()
            ->getName()]))
            throw new ConfigNotFoundException(sprintf('Could not find a configuration for %s', $entity->getType()
            ->getName()));
        
        $instance = parent::createInstance('Taxonomy\Manager\TermManagerInterface');
        $instance->setEntity($entity);
        $instance->setSharedTaxonomyManager($this);
        $instance->setConfig($this->config['types'][$entity->getType()
            ->getName()]);
        return $instance;
    }
}