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
use Taxonomy\Entity\TermTaxonomyInterface;
use Taxonomy\Collection\TermCollection;
use Taxonomy\Exception\NotFoundException;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Taxonomy\Manager\TaxonomyManagerInterface;
use Common\ArrayCopyProvider;

class TermService implements TermServiceInterface, ArrayCopyProvider
{
    
    use \ClassResolver\ClassResolverAwareTrait ,\Zend\ServiceManager\ServiceLocatorAwareTrait,\Common\Traits\EntityDelegatorTrait, \Taxonomy\Manager\SharedTaxonomyManagerAwareTrait;

    /**
     *
     * @var \Taxonomy\Manager\TaxonomyManagerInterface
     */
    protected $manager;

    /**
     *
     * @param TermTaxonomyInterface $term            
     * @return $this;
     */
    public function setTermTaxonomy(TermTaxonomyInterface $term)
    {
        $this->setEntity($term);
        return $this;
    }
    
    public function getArrayCopy(){
        return $this->getEntity()->getArrayCopy();
    }

    /**
     *
     * @return TermTaxonomyInterface $term
     */
    public function getTermTaxonomy()
    {
        return $this->getEntity();
    }

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

    public function findChildrenByTaxonomyName($taxonomy)
    {
        $language = $this->getLanguageService();
        $tax = $this->getSharedTaxonomyManager()->findTaxonomyByName($taxonomy, $language);
        $array = $this->getTermTaxonomy()
            ->getChildren()
            ->matching(Criteria::create()->where(Criteria::expr()->eq('taxonomy', $tax->getEntity())));
        $collection = new TermCollection($array, $this->getSharedTaxonomyManager());
        return $collection;
    }

    public function getTemplate($template)
    {
        if (! isset($this->getOption('templates')[$template]))
            throw new InvalidArgumentException(sprintf('Template `%s` not found for taxonomy `%s`', $template, $this->getTaxonomy()->getName()));
        
        return $this->getOption('templates')[$template];
    }

    public function hasChildren()
    {
        return $this->getTermTaxonomy()->hasChildren();
    }

    public function getParent()
    {
        return $this->getSharedTaxonomyManager()->getTerm($this->getTermTaxonomy()
            ->getParent()->getId());
    }

    public function getChildren()
    {
        return new TermCollection($this->getTermTaxonomy()->getChildren(), $this->getSharedTaxonomyManager());
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
        
        return $this->getTermTaxonomy()
            ->getAssociatedions($targetField)
            ->count() != 0;
    }

    public function countLinks($targetField)
    {
        if (! $this->isAssociationAllowed($targetField))
            return 0;
        
        return $this->getTermTaxonomy()
            ->getAssociated($targetField)
            ->count();
    }

    public function getAssociated($targetField, $recursive = false, $allowedTaxonomies = NULL)
    {
        if (! $recursive) {
            $this->isLinkAllowedWithException($targetField);
            $callback = $this->getCallbackForLink($targetField);
            return $callback($this->getServiceLocator(), $this->getTermTaxonomy()->getAssociated($targetField));
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

    public function associate($targetField, $target)
    {
        $this->isLinkAllowedWithException($targetField);
        $entity = $this->getTermTaxonomy();
        
        $entity->addAssociation($targetField, $target);
        return $this;
    }

    public function removeAssociation($targetField, $target)
    {
        $this->isLinkAllowedWithException($targetField);
        $entity = $this->getTermTaxonomy();
        
        $entity->removeAssociation($targetField, $target->getId());
        
        return $this;
    }

    public function isAssociated($targetField, $target)
    {
        $this->isLinkAllowedWithException($targetField);
        $targets = $this->getTermTaxonomy()->getAssociated($targetField);
        return $targets->contains($target->getId());
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
        $this->getTermTaxonomy()->set('term', $term->getTermTaxonomy());
        return $this;
    }

    public function childNodeAllowed(TermTaxonomyInterface $term)
    {
        return $this->allowsChildType($term->getTaxonomy()
            ->getName());
    }

    public function parentNodeAllowed(TermTaxonomyInterface $term)
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
        return $this->$this->getOption('radix_enabled');
    }

    public function setParent($parent)
    {
        $entity = $this->getTermTaxonomy();
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
        return $this->getTermTaxonomy()->getId();
    }

    public function getName()
    {
        return $this->getTermTaxonomy()->getName();
    }

    public function getTaxonomy()
    {
        return $this->getTermTaxonomy()->getTaxonomy();
    }
    
    public function getLanguageService(){
        return $this->getManager()->getLanguageService();
    }
    

    public function getTypeName()
    {
        return $this->getTermTaxonomy()
            ->getType()
            ->getName();
    }

    public function getSlug()
    {
        return $this->getTermTaxonomy()->getSlug();
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
            foreach ($term->getTermTaxonomy()->getAssociated($targetField) as $link) {
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
}