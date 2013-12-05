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
use Taxonomy\Model\TaxonomyTermModelInterface;
use Taxonomy\Collection\TermCollection;
use Taxonomy\Exception;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Taxonomy\Manager\TaxonomyManagerInterface;
use Taxonomy\Exception\TermNotFoundException;
use Common\Normalize\Normalized;
use Taxonomy\Exception\RuntimeException;

class TermService implements TermServiceInterface
{
    
    use\Term\Manager\TermManagerAwareTrait,\Zend\ServiceManager\ServiceLocatorAwareTrait,\Taxonomy\Manager\SharedTaxonomyManagerAwareTrait,\Taxonomy\Router\TermRouterAwareTrait;

    /**
     *
     * @var \Taxonomy\Manager\TaxonomyManagerInterface
     */
    protected $manager;

    /**
     *
     * @var TaxonomyTermModelInterface
     */
    protected $entity;

    public function getEntity()
    {
        return $this->entity;
    }

    public function getArrayCopy()
    {
        return $this->getEntity()->getArrayCopy();
    }

    public function hasChildren()
    {
        return $this->getEntity()->hasChildren();
    }

    public function hasParent()
    {
        return $this->getEntity()->hasParent();
    }

    public function isTrashed()
    {
        return $this->getEntity()->isTrashed();
    }

    public function getId()
    {
        return $this->getEntity()->getId();
    }

    public function getName()
    {
        return $this->getEntity()->getName();
    }

    public function getTaxonomy()
    {
        return $this->getEntity()->getTaxonomy();
    }

    public function getSlug()
    {
        return $this->getEntity()->getSlug();
    }

    public function getDescription()
    {
        return $this->getEntity()->getDescription();
    }

    public function getChildren()
    {
        return new TermCollection($this->getEntity()->getChildren(), $this->getSharedTaxonomyManager());
    }

    public function getPosition()
    {
        return $this->getEntity()->getPosition();
    }

    public function getLanguage()
    {
        return $this->getManager()->getLanguageService();
    }

    public function getParent()
    {
        $parent = $this->getEntity()->getParent();
        if ($parent === NULL) {
            throw new Exception\RuntimeException(sprintf('Taxonomy term `%s` has no parent', $this->getName()));
        }
        return $this->getSharedTaxonomyManager()->getTerm($parent->getId());
    }

    public function countAssociations($association)
    {
        return $this->getEntity()->countAssociations($association);
    }

    public function getAssociated($targetField)
    {
        $this->isLinkAllowedWithException($targetField);
        $callback = $this->getCallbackForLink($targetField);
        return $callback($this->getServiceLocator(), $this->getEntity()->getAssociated($targetField));
    }

    public function isAssociated($association, $object)
    {
        $this->isLinkAllowedWithException($association);
        return $this->getEntity()->isAssociated($association, $object);
    }

    public function positionAssociatedObject($association, TaxonomyTermModelInterface $object, $position)
    {
        $entity = $this->getEntity()->positionAssociatedObject($association, $object, $position);
        return $this;
    }

    public function removeAssociation($association, TaxonomyTermModelInterface $object)
    {
        $this->isLinkAllowedWithException($association);
        $this->getEntity()->removeAssociation($association, $object);
        return $this;
    }

    public function getTerm()
    {
        return $this->getTermManager()->getTerm($this->getEntity()
            ->getTerm());
    }

    public function associateObject($association, \Taxonomy\Model\TaxonomyTermModelInterface $object)
    {
        $this->isLinkAllowedWithException($association);
        $this->getEntity()->addAssociation($association, $object);
        return $this;
    }

    public function getManager()
    {
        return $this->manager;
    }

    public function normalize()
    {
        $routeMatch = $this->getTermRouter()->route($this->getId());
        $normalized = new Normalized();
        $normalized->setTitle($this->getName());
        $normalized->setRouteName($routeMatch->getMatchedRouteName());
        $normalized->setRouteParams($routeMatch->getParams());
        return $normalized;
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

    public function findChildrenByTaxonomyNames(array $names)
    {
        $names = (array) $names;
        $collection = $this->getEntity()
            ->getChildren()
            ->filter(function (TaxonomyTermModelInterface $term) use($names)
        {
            return in_array($term->getTaxonomy()
                ->getName(), $names);
        });
        return new TermCollection($collection, $this->getSharedTaxonomyManager());
    }

    public function getTemplate($template)
    {
        if (! isset($this->getOption('templates')[$template]))
            throw new RuntimeException(sprintf('Template `%s` not found for taxonomy `%s`', $template, $this->getTaxonomy()->getName()));
        
        return $this->getOption('templates')[$template];
    }

    public function getAssociatedRecursive($targetField, array $allowedTaxonomies = array())
    {
        $collection = new ArrayCollection();
        $collection = $this->prepareAssociations($collection, $this, $targetField, $allowedTaxonomies);
        $callback = $this->getCallbackForLink($targetField);
        return $callback($this->getServiceLocator(), $collection);
    }

    public function isAssociationAllowed($targetField)
    {
        return in_array($targetField, (array) $this->getOption('allowed_associations'));
    }

    public function findAncestorByType($name)
    {
        $ancestor = $this->getEntity()->findAncestorByTypeName($name);
        
        if (! is_object($ancestor)) {
            throw new Exception\TermNotFoundException(sprintf('Term `%s` does not know an ancestor of type `%s`', $this->getName(), $name));
        }
        
        return $ancestor;
    }

    public function knowsAncestor(TaxonomyTermModelInterface $taxonomyTerm)
    {
        return $this->knowsAncestor($taxonomyTerm);
    }

    public function getAllowedParentTypes()
    {
        return $this->getManager()->getAllowedParentTypes();
    }

    public function getAllowedChildrenTypes()
    {
        return $this->getManager()->getAllowedChildrenTypes();
    }

    public function setTaxonomy(\Taxonomy\Entity\TaxonomyInterface $taxonomy)
    {
        $this->getEntity()->setTaxonomy($taxonomy);
        return $this;
    }

    public function setDescription($description)
    {
        $this->getEntity()->setDescription($description);
        return $this;
    }

    public function setPosition($position)
    {
        $this->getEntity()->setPosition($position);
        return $this;
    }

    public function setTerm(\Term\Model\TermModelInterface $term)
    {
        $this->getEntity()->setTerm($term->getEntity());
        return $this;
    }

    public function setEntity(TaxonomyTermModelInterface $term)
    {
        $this->setEntity($term);
        return $this;
    }

    public function setParent(TermServiceInterface $parent = NULL)
    {
        $entity = $this->getEntity();
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

    public function setManager(TaxonomyManagerInterface $termManager)
    {
        $this->manager = $termManager;
        return $this;
    }

    protected function getCallbackForLink($link)
    {
        return $this->getSharedTaxonomyManager()->getCallback($link);
    }

    protected function prepareAssociations(Collection $collection, TermService $term, $targetField, array $allowedTaxonomies = array())
    {
        if (empty($allowedTaxonomies)) {
            return $collection;
        }
        
        if ($term->isAssociationAllowed($targetField)) {
            foreach ($term->getEntity()->getAssociated($targetField) as $link) {
                $collection->add($link);
            }
        }
        
        foreach ($term->getChildren() as $child) {
            if (in_array($child->getTaxonomy()->getName(), $allowedTaxonomies)) {
                $this->prepareAssociations($collection, $child, $targetField, $allowedTaxonomies);
            }
        }
        return $collection;
    }

    protected function isLinkAllowedWithException($targetField)
    {
        if (! $this->isAssociationAllowed($targetField))
            throw new LinkNotAllowedException();
    }

    protected function getOption($name)
    {
        return $this->getManager()->getOption($name);
    }
}
