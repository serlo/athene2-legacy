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
use Taxonomy\Exception\TermNotFoundException;
use Taxonomy\Entity\TermTaxonomyInterface;
use Taxonomy\Entity\TaxonomyInterface;
use Doctrine\Common\Collections\Criteria;

class SharedTaxonomyManager extends AbstractManager implements SharedTaxonomyManagerInterface
{
    use\Common\Traits\ObjectManagerAwareTrait,\Language\Manager\LanguageManagerAwareTrait,\Common\Traits\ConfigAwareTrait;

    public function getDefaultConfig()
    {
        return array();
    }

    public function __construct($config)
    {
        $this->setConfig($config);
    }

    public function getTaxonomy($id)
    {
        if (! is_numeric($id))
            throw new InvalidArgumentException(sprintf('Expected int but got %s', gettype($id)));
        
        if (! $this->hasInstance($id)) {
            $entity = $this->getObjectManager()->find($this->getClassResolver()
                ->resolveClassName('Taxonomy\Entity\TaxonomyInterface'), $id);
            
            if (! is_object($entity))
                throw new NotFoundException(sprintf('A taxonomy by the id of %s could not be found.', $id));
            
            $this->addInstance($entity->getId(), $this->createService($entity));
        }
        
        return $this->getInstance($id);
    }

    public function findTaxonomyByName($name, LanguageServiceInterface $language)
    {
        if (! is_string($name))
            throw new InvalidArgumentException(sprintf('Expected string but got %s', gettype($name)));
        
        $type = $this->getObjectManager()
            ->getRepository($this->getClassResolver()
            ->resolveClassName('Taxonomy\Entity\TaxonomyTypeInterface'))
            ->findOneBy(array(
            'name' => $name
        ));
        
        if (! is_object($type))
            throw new InvalidArgumentException(sprintf('Taxonomy type %s not found', $name));
        
        $entity = $type->getTaxonomies()->matching(Criteria::create()->where(Criteria::expr()->eq('language', $language->getEntity()))
            ->setMaxResults(1))->first();
        
        if (! is_object($entity))
            throw new NotFoundException(sprintf('Could not find Taxonomy %s by language %s.', $name, $language->getId()));
        
        if (! $this->hasInstance($entity->getId())) {
            $this->addInstance($entity->getId(), $this->createService($entity));
        }
        
        return $this->getInstance($entity->getId());
    }

    public function getTerm($term)
    {
        if (is_numeric($term)) {
            $term = $this->getObjectManager()->find($this->getClassResolver()
                ->resolveClassName('Taxonomy\Entity\TermTaxonomyInterface'), (int) $term);
        } elseif ($term instanceof TermTaxonomyInterface) {} else {
            if (! is_object()) {
                throw new InvalidArgumentException(sprintf('Expected numeric but got %s', gettype($term)));
            } else {
                throw new InvalidArgumentException(sprintf('Expected `Taxonomy\Entity\TermTaxonomyInterface` but got %s', get_class($term)));
            }
        }
        
        if (! is_object($term))
            throw new TermNotFoundException(sprintf('Term with id %s could not be found', $term));
        
        $return = $this->getTaxonomy($term->getTaxonomy()
            ->getId())
            ->getTerm($term->getId());
        
        return $return;
    }

    public function getCallback($link)
    {
        if (! array_key_exists($link, $this->getOption('links')))
            throw new InvalidArgumentException(sprintf('Callback for type %s not found', $link));
        
        return $this->getOption('links')[$link];
    }

    public function getAllowedChildrenTypes($type)
    {
        $return = array();
        foreach ($this->getOption('types') as $name => $config) {
            if (array_key_exists('allowed_parents', (array) $this->getOption('options')) && in_array($type, $this->getOption('options')['allowed_parents'])) {
                $return[] = $name;
            }
        }
        return $return;
    }

    protected function createService(TaxonomyInterface $entity)
    {
        if (! array_key_exists($entity->getType()->getName(), $this->getOption('types'))) {
            throw new ConfigNotFoundException(sprintf('Could not find a configuration for %s. Data: %s', $entity->getType()->getName(), print_r($this->config['types'], TRUE)));
        }
        
        $instance = parent::createInstance('Taxonomy\Manager\TaxonomyManagerInterface');
        $instance->setEntity($entity);
        $instance->setSharedTaxonomyManager($this);
        $instance->setLanguageService($this->getLanguageManager()
            ->get($entity->getLanguage()
            ->getId()));
        $instance->setConfig($this->getOption('types')[$entity->getType()
            ->getName()]);
        return $instance;
    }
}