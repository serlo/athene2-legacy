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

class TermService implements TermServiceInterface
{
    
    use\ClassResolver\ClassResolverAwareTrait ,\Zend\ServiceManager\ServiceLocatorAwareTrait,\Term\Manager\TermManagerAwareTrait,\Common\Traits\EntityDelegatorTrait,\Taxonomy\Manager\SharedTaxonomyManagerAwareTrait,\Common\Traits\ObjectManagerAwareTrait;

    /**
     * 
     * @param TermTaxonomyInterface $term
     * @return $this;
     */
    public function setTermTaxonomy(TermTaxonomyInterface $term){
        $this->setEntity($term);
        return $this;
    }
    
    /**
     * 
     * @return TermTaxonomyInterface $term
     */
    public function getTermTaxonomy(){
        return $this->getEntity();
    }
    
    
    /**
     *
     * @var \Taxonomy\Manager\TaxonomyManagerInterface
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

    public function findChildrenByTaxonomyName($taxonomy)
    {
        $tax = $this->getSharedTaxonomyManager()->getTaxonomy($taxonomy);
        $array = $this->getTermTaxonomy()
            ->getChildren()
            ->matching(Criteria::create()->where(Criteria::expr()->eq('taxonomy', $tax->getTermTaxonomy())));
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
        return $this->getTermTaxonomy()->hasChildren();
    }
    
    public function getParent()
    {
        return $this->getSharedTaxonomyManager()->getTermService($this->getTermTaxonomy()
            ->getParent());
    }
    
    public function getChildren()
    {
        return new TermCollection($this->getTermTaxonomy()->getChildren(), $this->getSharedTaxonomyManager());
    }
    
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
        
        return $this->getTermTaxonomy()
            ->getAssociatedions($targetField)
            ->count() != 0;
    }

    public function countLinks($targetField)
    {
        if (! $this->isLinkAllowed($targetField))
            return 0;
        
        return $this->getTermTaxonomy()
            ->getAssociated($targetField)
            ->count();
    }
    
    public function getLinks($targetField, $recursive = false, $allowedTaxonomies = NULL)
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

    protected function injectLinks(Collection $collection, TermService $term, $targetField, $allowedTaxonomies = NULL)
    {
        if (! $allowedTaxonomies) {
            return $collection;
        }
        
        if ($term->isLinkAllowed($targetField)) {
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

    public function getCallbackForLink($link)
    {
        return $this->getSharedTaxonomyManager()->getCallback($link);
    }

    public function addLink($targetField, $target)
    {
        $target = $this->findEntity($target);
        $this->isLinkAllowedWithException($targetField);
        $entity = $this->getTermTaxonomy();
        $entity->getAssociated($targetField)->add($target);
        return $this;
    }
    
    public function removeLink($targetField, $target)
    {
        $this->isLinkAllowedWithException($targetField);
        $entity = $this->getTermTaxonomy();
        
        $entity->getAssociated($targetField)->remove($target->getId());
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
        $targets = $this->getTermTaxonomy()->getAssociated($targetField);
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
        $this->getTermTaxonomy()->set('term', $term->getTermTaxonomy());
        return $this;
    }

    protected $radix = false;

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
        return in_array($type, $this->getOption('options')['allowed_parents']);
    }

    public function allowsChildType($type)
    {
        return $this->getSharedTaxonomyManager()
            ->getTaxonomy($type)
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

    public function getType()
    {
        return $this->getTermTaxonomy()->getType();
    }

    public function getTaxonomy()
    {
        return $this->getTermTaxonomy()->getTaxonomy();
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

    public function getArrayCopy()
    {
        return $this->getTermTaxonomy()->getArrayCopy();
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
}