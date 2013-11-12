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
use Taxonomy\Entity\TaxonomyTermInterface;
use Taxonomy\Collection\TermCollection;
use Taxonomy\Exception;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Taxonomy\Manager\TaxonomyManagerInterface;
use Taxonomy\Exception\TermNotFoundException;

class TermService implements TermServiceInterface
{
    
    use\ClassResolver\ClassResolverAwareTrait ,\Zend\ServiceManager\ServiceLocatorAwareTrait,\Common\Traits\EntityDelegatorTrait,\Taxonomy\Manager\SharedTaxonomyManagerAwareTrait;

    /**
     *
     * @var \Taxonomy\Manager\TaxonomyManagerInterface
     */
    protected $manager;

    /**
     *
     * @param TaxonomyTermInterface $term            
     * @return $this;
     */
    public function setTaxonomyTerm(TaxonomyTermInterface $term)
    {
        $this->setEntity($term);
        return $this;
    }

    public function getArrayCopy()
    {
        return $this->getEntity()->getArrayCopy();
    }

    /**
     *
     * @return TaxonomyTermInterface $term
     */
    public function getTaxonomyTerm()
    {
        return $this->getEntity();
    }

    public function orderAssociated($association, $object, $order)
    {
        $entity = $this->getEntity()->orderAssociated($association, $object, $order);
        $this->getSharedTaxonomyManager()
            ->getObjectManager()
            ->persist($entity);
        return $this;
    }

    public function getDescendantBySlugs(array $path)
    {
        $term = $this;
        $found = NULL;
        $partsFound = 0;
        
        foreach ($path as &$part) {
            $found = false;
            $part = strtolower($part);
            if (strtolower($this->getSlug()) == $part) {
                $found = $this;
                $partsFound ++;
            } else {
                foreach ($term->getChildren() as $child) {
                    if (strtolower($child->getSlug()) == $part) {
                        $term = $child;
                        $found = $child;
                        $partsFound ++;
                        break;
                    }
                }
                if (! is_object($found))
                    throw new TermNotFoundException(sprintf('Could not find term with acestors: %s', implode(',', $path)));
            }
            if (! is_object($found))
                throw new TermNotFoundException(sprintf('Could not find term with acestors: %s', implode(',', $path)));
        }
        return $found;
    }

    public function findChildrenByTaxonomyName($taxonomy)
    {
        return $this->filterChildren((array) $taxonomy);
    }

    public function getTemplate($template)
    {
        if (! isset($this->getOption('templates')[$template]))
            throw new InvalidArgumentException(sprintf('Template `%s` not found for taxonomy `%s`', $template, $this->getTaxonomy()->getName()));
        
        return $this->getOption('templates')[$template];
    }

    public function hasChildren()
    {
        return $this->getTaxonomyTerm()->hasChildren();
    }

    public function hasParent()
    {
        return $this->getTaxonomyTerm()->hasParent();
    }

    public function getParent()
    {
        return $this->getSharedTaxonomyManager()->getTerm($this->getTaxonomyTerm()
            ->getParent()
            ->getId());
    }

    public function filterChildren(array $types)
    {
        $collection = $this->getTaxonomyTerm()
            ->getChildren()
            ->filter(function (TaxonomyTermInterface $term) use($types)
        {
            return in_array($term->getTaxonomy()
                ->getName(), $types);
        });
        return new TermCollection($collection, $this->getSharedTaxonomyManager());
    }

    public function getChildren()
    {
        return new TermCollection($this->getTaxonomyTerm()->getChildren(), $this->getSharedTaxonomyManager());
    }

    public function getAllLinks()
    {
        $return = array();
        foreach ($this->getAllowedAssociations() as $targetField => $options) {
            $return[$targetField] = $this->getAssociated($targetField);
        }
        return $return;
    }

    public function hasLinks($targetField)
    {
        if (! $this->isAssociationAllowed($targetField))
            return false;
        
        return $this->getTaxonomyTerm()
            ->getAssociatedions($targetField)
            ->count() != 0;
    }

    public function countLinks($targetField)
    {
        if (! $this->isAssociationAllowed($targetField))
            return 0;
        
        return $this->getTaxonomyTerm()
            ->getAssociated($targetField)
            ->count();
    }

    public function getAssociated($targetField, $recursive = false, $allowedTaxonomies = NULL)
    {
        if (! $recursive) {
            $this->isLinkAllowedWithException($targetField);
            $callback = $this->getCallbackForLink($targetField);
            return $callback($this->getServiceLocator(), $this->getTaxonomyTerm()->getAssociated($targetField));
        } else {
            $collection = new ArrayCollection();
            $collection = $this->injectLinks($collection, $this, $targetField, $allowedTaxonomies);
            $callback = $this->getCallbackForLink($targetField);
            return $callback($this->getServiceLocator(), $collection);
        }
    }

    public function getCallbackForLink($link)
    {
        return $this->getSharedTaxonomyManager()->getCallback($link);
    }

    public function getTemplateForAssociation($association)
    {
        return $this->getSharedTaxonomyManager()->getTemplateForAssociation($association);
    }

    public function associate($targetField, $target)
    {
        $this->isLinkAllowedWithException($targetField);
        $entity = $this->getTaxonomyTerm();
        
        $entity->addAssociation($targetField, $target);
        return $this;
    }

    public function removeAssociation($targetField, $target)
    {
        $this->isLinkAllowedWithException($targetField);
        $entity = $this->getTaxonomyTerm();
        
        $entity->removeAssociation($targetField, $target);
        
        return $this;
    }

    public function isAssociated($targetField, $target)
    {
        $this->isLinkAllowedWithException($targetField);
        $targets = $this->getTaxonomyTerm()->getAssociated($targetField);
        return $targets->contains($target);
    }

    protected function isLinkAllowedWithException($targetField)
    {
        if (! $this->isAssociationAllowed($targetField))
            throw new LinkNotAllowedException();
    }

    public function getAllowedAssociations()
    {
        return $this->getOption('allowed_associations');
    }

    public function isAssociationAllowed($targetField)
    {
        return in_array($targetField, (array) $this->getOption('allowed_associations'));
    }

    public function findAncestorByType($type)
    {
        $term = $this;
        while ($term->getParent()) {
            $term = $term->getParent();
            if ($term->getTaxonomy()->getName() == $type)
                return $term;
        }
        throw new Exception\RuntimeException(sprintf('Term `%s` does not know an ancestor of type `%s`', $this->getName(), $type));
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
        $this->getTaxonomyTerm()->set('term', $term->getTermTaxonomy());
        return $this;
    }

    public function childNodeAllowed(TermServiceInterface $term)
    {
        return $this->allowsChildType($term->getTaxonomy()
            ->getName());
    }

    public function parentNodeAllowed(TermServiceInterface $term)
    {
        return $this->allowsParentType($term->getTaxonomy()
            ->getName());
    }

    public function allowsParentType($type)
    {
        return in_array($type, $this->getOption('allowed_parents'));
    }

    public function allowsChildType($type)
    {
        $language = $this->getLanguageService();
        return $this->getSharedTaxonomyManager()
            ->findTaxonomyByName($type, $language)
            ->allowsParentType($this->getTaxonomy()
            ->getName());
    }

    public function getAllowedParentTypeNames()
    {
        return $this->getManager()->getAllowedParentTypeNames();
    }

    public function getAllowedChildrenTypeNames()
    {
        return $this->getManager()->getAllowedChildrenTypeNames();
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
        return $this->getOption('radix_enabled');
    }

    public function setParent(TermServiceInterface $parent = NULL)
    {
        $entity = $this->getTaxonomyTerm();
        if ($parent === NULL) {
            if ($this->radixEnabled()) {
                $entity->setParent(NULL);
            } else {
                throw new Exception\RuntimeException('Radix not allowed.');
            }
        } else {
            if ($this->parentNodeAllowed($parent)) {
                $entity->setParent($parent->getEntity());
            } else {
                throw new Exception\RuntimeException(sprintf('Parent `%s` (%d) not allowed for `%s` (%d).', $parent->getName(), $parent->getId(), $entity->getName(), $entity->getId()));
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
        return $this->getTaxonomyTerm()->getId();
    }

    public function getName()
    {
        return $this->getTaxonomyTerm()->getName();
    }

    public function getTaxonomy()
    {
        return $this->getTaxonomyTerm()->getTaxonomy();
    }

    public function getLanguageService()
    {
        return $this->getManager()->getLanguageService();
    }

    public function getTypeName()
    {
        return $this->getTaxonomyTerm()
            ->getTaxonomy()
            ->getName();
    }

    public function getSlug()
    {
        return $this->getTaxonomyTerm()->getSlug();
    }

    public function getManager()
    {
        return $this->manager;
    }

    public function setManager(TaxonomyManagerInterface $termManager)
    {
        $this->manager = $termManager;
        return $this;
    }

    protected function injectLinks(Collection $collection, TermService $term, $targetField, $allowedTaxonomies = NULL)
    {
        if (! $allowedTaxonomies) {
            return $collection;
        }
        
        if ($term->isAssociationAllowed($targetField)) {
            foreach ($term->getTaxonomyTerm()->getAssociated($targetField) as $link) {
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

    public function setOrder($order)
    {
        $this->getEntity()->setWeight($order);
        return $this;
    }

    public function getDescription()
    {
        return $this->getEntity()->getDescription();
    }
}
