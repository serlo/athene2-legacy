<?php
/**
 *
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @copyright 2013 by www.serlo.org
 * @license LGPL
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */
namespace Taxonomy\Service;

use Taxonomy\Exception\LinkNotAllowedException;
use Taxonomy\Exception\InvalidArgumentException;
use Taxonomy\Entity\TermTaxonomyEntityInterface;
use Taxonomy\Collection\TermCollection;
use Taxonomy\Exception\NotFoundException;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Taxonomy\Manager\TermManagerInterface;

class TermService implements TermServiceInterface
{
    
    use\ClassResolver\ClassResolverAwareTrait ,\Zend\ServiceManager\ServiceLocatorAwareTrait,\Term\Manager\TermManagerAwareTrait,\Common\Traits\EntityDelegatorTrait,\Taxonomy\Manager\SharedTaxonomyManagerAwareTrait,\Common\Traits\ObjectManagerAwareTrait;

    /**
     *
     * @var \Taxonomy\Manager\TermManagerInterface
     */
    protected $manager;

    public function getDescendantBySlugs(array $path)
    {
        $term = $this;
        $found = NULL;
        
        foreach ($path as $part) {
            $found = false;
            foreach ($term->getChildren() as $child) {
                if ($child->getSlug() == $part) {
                    $term = $child;
                    $found = $child;
                    break;
                }
            }
            if (! $found)
                throw new NotFoundException('Term not found');
        }
        return $found;
    }

    public function getChildrenByTaxonomyName($taxonomy)
    {
        $tax = $this->getSharedTaxonomyManager()->get($taxonomy);
        $array = $this->getEntity()
            ->getChildren()
            ->matching(Criteria::create()->where(Criteria::expr()->eq('taxonomy', $tax->getEntity())));
        $collection = new TermCollection($array, $this->getSharedTaxonomyManager());
        return $collection;
    }

    public function getTemplate($template)
    {
        if (! isset($this->getOption('options')['templates'][$template]))
            throw new InvalidArgumentException(sprintf('Template `%s` not found for taxonomy `%s`', $template, $this->getTaxonomy()->getName()));
        
        return $this->getOption('options')['templates'][$template];
    }

    public function hasChildren()
    {
        return $this->getEntity()->hasChildren();
    }
    
    /*
     * (non-PHPdoc) @see \Taxonomy\Service\TermServiceInterface::getParent()
     */
    public function getParent()
    {
        return $this->getSharedTaxonomyManager()->getTerm($this->getEntity()
            ->getParent());
    }
    
    /*
     * (non-PHPdoc) @see \Taxonomy\Service\TermServiceInterface::getChildren()
     */
    public function getChildren()
    {
        /*
         * return $this->getManager()->get($this->getEntity() ->get('children'));
         */
        return new TermCollection($this->getEntity()->getChildren(), $this->getSharedTaxonomyManager());
    }
    
    /*
     * (non-PHPdoc) @see \Taxonomy\Service\TermServiceInterface::getAllLinks()
     */
    public function getAllLinks()
    {
        $return = array();
        foreach ($this->getAllowedLinks() as $targetField => $options) {
            $return[$targetField] = $this->getLinks($targetField);
        }
        return $return;
    }

    public function hasLinks($targetField)
    {
        if (! $this->isLinkAllowed($targetField))
            return false;
        
        return $this->getEntity()
            ->getRelations($targetField)
            ->count() != 0;
    }

    public function countLinks($targetField)
    {
        if (! $this->isLinkAllowed($targetField))
            return 0;
        
        return $this->getEntity()
            ->getRelations($targetField)
            ->count();
    }
    
    /*
     * (non-PHPdoc) @see \Taxonomy\Service\TermServiceInterface::getLinks()
     */
    public function getLinks($targetField, $recursive = false, $allowedTaxonomies = NULL)
    {
        if (! $recursive) {
            $this->isLinkAllowedWithException($targetField);
            $callback = $this->getCallbackForLink($targetField);
            return $callback($this->getServiceLocator(), $this->getEntity()->getRelations($targetField));
        } else {
            $collection = new ArrayCollection();
            $collection = $this->injectLinks($collection, $this, $targetField, $allowedTaxonomies);
            $callback = $this->getCallbackForLink($targetField);
            return $callback($this->getServiceLocator(), $collection);
        }
    }

    protected function injectLinks(Collection $collection, TermService $term, $targetField, $allowedTaxonomies = NULL)
    {
        if (! $allowedTaxonomies) {
            return $collection;
        }
        
        if ($term->isLinkAllowed($targetField)) {
            foreach ($term->getEntity()->getRelations($targetField) as $link) {
                $collection->add($link);
            }
        }
        
        foreach ($term->getChildren() as $child) {
            if (in_array($child->getTaxonomy()->getName(), $allowedTaxonomies)) {
                $this->injectLinks($collection, $child, $targetField, $allowedTaxonomies);
            }
        }
        return $collection;
    }

    public function getCallbackForLink($link)
    {
        return $this->getSharedTaxonomyManager()->getCallback($link);
    }

    public function addLink($targetField, $target)
    {
        $target = $this->findEntity($target);
        $this->isLinkAllowedWithException($targetField);
        $entity = $this->getEntity();
        $entity->getRelations($targetField)->add($target);
        return $this;
    }
    
    /*
     * (non-PHPdoc) @see \Taxonomy\Service\TermServiceInterface::removeLink()
     */
    public function removeLink($targetField, $target)
    {
        $this->isLinkAllowedWithException($targetField);
        $entity = $this->getEntity();
        
        $entity->getRelations($targetField)->remove($target->getId());
        $target->getTerms()->remove($entity->getId());
        
        $this->getObjectManager()->flush();
        return $this;
    }

    private function findEntity($target)
    {
        return $target;
    }

    public function hasLink($targetField, $target)
    {
        $this->isLinkAllowedWithException($targetField);
        $targets = $this->getEntity()->getRelations($targetField);
        return $targets->contains($target->getId());
    }

    protected function isLinkAllowedWithException($targetField)
    {
        if (! $this->isLinkAllowed($targetField))
            throw new LinkNotAllowedException();
    }

    public function getAllowedLinks()
    {
        return $this->getOption('options')['allowed_links'];
    }

    public function isLinkAllowed($targetField)
    {
        return in_array($targetField, (array) $this->getOption('options')['allowed_links']);
    }

    public function update(array $data)
    {
        $merged = array_replace_recursive(array(
            'term' => array(
                'name' => $this->getName()
            ),
            'parent' => $this->getParent()->getId(),
            'taxonomy' => $this->getTaxonomy()->getId()
        ), $data);
        
        $this->setName($data['term']['name']);
        unset($data['term']);
        /*
         * try { $this->populate($data); } catch (\Core\Exception\UnknownPropertyException $e) {}
         */
        $this->persistAndFlush();
        return $this;
    }

    public function knowsAncestor($ancestor)
    {
        if (is_numeric($ancestor)) {} elseif ($ancestor instanceof TermServiceInterface) {
            $ancestor = $ancestor->getId();
        } else {
            throw new InvalidArgumentException();
        }
        
        $term = $this;
        while ($term->getParent()) {
            $term = $term->getParent();
            if ($term->getId() == $ancestor)
                return true;
        }
        return false;
    }

    public function setName($name)
    {
        $term = $this->getTermManager()->getTerm($name);
        $this->getEntity()->set('term', $term->getEntity());
        return $this;
    }

    protected $radix = false;

    public function childNodeAllowed(TermTaxonomyEntityInterface $term)
    {
        return $this->allowsChildType($term->getTaxonomy()
            ->getName());
    }

    public function parentNodeAllowed(TermTaxonomyEntityInterface $term)
    {
        return $this->allowsParentType($term->getTaxonomy()
            ->getName());
    }

    public function allowsParentType($type)
    {
        return in_array($type, $this->getOption('options')['allowed_parents']); // disabled: ; || $this->getTaxonomy()->getName() == $type;
    }

    public function allowsChildType($type)
    {
        return $this->getSharedTaxonomyManager()
            ->get($type)
            ->allowsParentType($this->getTaxonomy()
            ->getName());
    }

    public function getAllowedParentTypes()
    {
        return $this->getManager()->getAllowedParentTypes();
    }

    public function getAllowedChildrenTypes()
    {
        return $this->getManager()->getAllowedChildrenTypes();
    }

    public function radixEnabled()
    {
        return $this->$this->getOption('options')['radix_enabled'];
    }

    public function setParent($parent)
    {
        $entity = $this->getEntity();
        if ($parent == NULL) {
            if ($this->radixEnabled()) {
                $entity->setParent($parent);
            } else {
                throw new InvalidArgumentException('Radix not allowed.');
            }
        } else {
            if ($this->parentNodeAllowed($parent)) {
                $entity->setParent($parent);
            } else {
                throw new InvalidArgumentException('Parent `' . $parent->getId() . '` not allowed for `' . $entity->getId() . '`.');
            }
        }
    }
    
    public function getConfig()
    {
        return $this->getManager()->getConfig();
    }

    public function getOption($name)
    {
        return $this->getManager()->getOption($name);
    }

    public function getId()
    {
        return $this->getEntity()->getId();
    }

    public function getName()
    {
        return $this->getEntity()->getName();
    }

    public function getType()
    {
        return $this->getEntity()->getType();
    }

    public function getTaxonomy()
    {
        return $this->getEntity()->getTaxonomy();
    }

    public function getTypeName()
    {
        return $this->getEntity()
            ->getType()
            ->getName();
    }

    public function getSlug()
    {
        return $this->getEntity()->getSlug();
    }

    public function getArrayCopy()
    {
        return $this->getEntity()->getArrayCopy();
    }

    public function getManager()
    {
        return $this->manager;
    }

    public function setManager(TermManagerInterface $termManager)
    {
        $this->manager = $termManager;
        return $this;
    }
}