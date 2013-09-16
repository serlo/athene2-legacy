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
    
    use\Common\Traits\ObjectManagerAwareTrait,\Language\Manager\LanguageManagerAwareTrait, \Common\Traits\ConfigAwareTrait;

    /**
     * @return multitype: $config
     */
    public function getDefaultConfig ()
    {
        return array();
    }

	/**
     * Constructor
     *
     * @param array $config            
     */
    public function __construct($config)
    {
        $this->setConfig($config);
    }

    public function add(TermManagerInterface $termManager)
    {
        $this->addInstance($termManager->getId(), $termManager);
        return $termManager->getId();
    }

    public function get($taxonomy, $language = NULL)
    {
        $className = $this->getClassResolver()->resolveClassName('Taxonomy\Manager\TermManagerInterface');
        $entityClassName = $this->getClassResolver()->resolveClassName('Taxonomy\Entity\TaxonomyEntityInterface');
        $termEntityClassName = $this->getClassResolver()->resolveClassName('Taxonomy\Entity\TermTaxonomyEntityInterface');
        
        if (!$language instanceof LanguageServiceInterface)
            $language = $this->getLanguageManager()->get($language);
        if (is_numeric($taxonomy)) {
            $entity = $this->getObjectManager()->find($this->getClassResolver()->resolveClassName('Taxonomy\Entity\TaxonomyEntityInterface'), $taxonomy);
        } elseif (is_string($taxonomy)) {
            
            $type = $this->getObjectManager()
                ->getRepository($this->getClassResolver()->resolveClassName('Taxonomy\Entity\TaxonomyTypeInterface'))
                ->findOneBy(array(
                'name' => $taxonomy
            ));
            
            if (! is_object($type))
                throw new InvalidArgumentException(sprintf('Taxonomy type %s not found', $taxonomy));
            
            $entity = $this->getObjectManager()
                ->getRepository($this->getClassResolver()->resolveClassName('Taxonomy\Entity\TaxonomyEntityInterface'))
                ->findOneBy(array(
                'language' => $language->getId(),
                'type' => $type->getId()
            ));
        } elseif ($taxonomy instanceof $className) {
            $entity = $taxonomy->getEntity();
        } elseif ($taxonomy instanceof $entityClassName) {
            $entity = $taxonomy;
        } else {
            throw new InvalidArgumentException();
        }
        
        if (! is_object($entity) || ! $entity instanceof $entityClassName )
            throw new NotFoundException(sprintf('Taxonomy %s not found in repository %s with language set to %s', $taxonomy, $entityClassName, $language->getId()));
        
        if(!$this->has($entity)){
            $this->add($this->createInstanceFromEntity($entity));
        }
        
        $name = $entity->getId();
        return $this->getInstance($name);
    }

    public function getTerm($element)
    {
        $termEntityClassName = $this->getClassResolver()->resolveClassName('Taxonomy\Entity\TermTaxonomyEntityInterface');
        if (is_numeric($element)) {
            $entity = $this->getObjectManager()->find($termEntityClassName, (int) $element); //->getTaxonomy();
        } elseif ($element instanceof $termEntityClassName) {
            $entity = $element;//->getTaxonomy();
        } else {
            throw new \InvalidArgumentException();
        }
        
        if (! is_object($entity))
            throw new NotFoundException(sprintf('That term does not exist!'));
        
        return $this->get($entity->getTaxonomy())->get($entity);
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
    
    public function getAllowedChildrenTypes($type){
        $return = array();
        foreach($this->config['types'] as $name => $config){
            if(array_key_exists('allowed_parents', $config['options']) && in_array($type, $config['options']['allowed_parents'])){
                $return[] = $name;
            }
        }
        return $return;
    }

    protected function createInstanceFromEntity($entity)
    {
        if (! is_object($entity))
            throw new NotFoundException();
        
        if(! array_key_exists($entity->getType()->getName(), $this->config['types'])){
            
            throw new ConfigNotFoundException(sprintf('Could not find a configuration for %s. Data: %s', $entity->getType()
            ->getName(), print_r($this->config['types'], TRUE)));
        }
        
        $instance = parent::createInstance('Taxonomy\Manager\TermManagerInterface');
        $instance->setEntity($entity);
        $instance->setSharedTaxonomyManager($this);
        $instance->setLanguageService($this->getLanguageManager()->get($entity->getLanguage()->getId()));
        $instance->setConfig($this->config['types'][$entity->getType()
            ->getName()]);
        return $instance;
    }
}