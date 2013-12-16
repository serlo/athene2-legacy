<?php
/**
 * 
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @copyright 2013 by www.serlo.org
 * @license LGPL
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */
namespace Link\Service;

use Link\Entity\LinkableInterface;
use Link\Entity\LinkInterface;
use Link\Exception;
use Type\Entity\TypeInterface;

class LinkService implements LinkServiceInterface
{
    
    use\Common\Traits\ObjectManagerAwareTrait,\Type\TypeManagerAwareTrait;

    /**
     *
     * @var LinkableInterface
     */
    protected $entity;

    public function getEntity()
    {
        return $this->entity;
    }

    public function associate(LinkableInterface $parent, LinkableInterface $child, $typeName, $position = 0)
    {
        $type = $this->getTypeManager()->findTypeByName($typeName);
        $link = $parent->createLink();
        
        $link->setParent($parent);
        $link->setChild($child);
        $link->setType($type);
        $link->setPosition($position);
        
        $this->getObjectManager()->persist($link);
        return $this;
    }

    public function dissociate(LinkableInterface $parent, LinkableInterface $child, $typeName, $position = 0)
    {
        $type = $this->getTypeManager()->findTypeByName($typeName);
        $link = $this->findLinkByChild($parent, $child, $type);
        if ($link) {
            $this->getObjectManager()->remove($link);
        }
        return $this;
    }

    public function sortChildren(LinkableInterface $parent, $typename, array $children)
    {
        
    }

    public function sortParents(LinkableInterface $child, $typename, array $parents);

    public function setEntity(LinkableInterface $entity)
    {
        $this->entity = $entity;
        return $this;
    }

    protected function findLinkByChild(LinkableInterface $element, LinkableInterface $child, TypeInterface $type)
    {
        /* @var $link LinkInterface */
        foreach ($element->getChildLinks() as $link) {
            if ($link->getChild() === $child && $link->getType() === $type) {
                return $link;
            }
        }
        return NULL;
    }

    protected function findLinkByParent(LinkableInterface $element, LinkableInterface $parent, TypeInterface $type)
    {
        /* @var $link LinkInterface */
        foreach ($element->getParentLinks() as $link) {
            if ($link->getParent() === $parent && $link->getType() === $type) {
                return $link;
            }
        }
        return NULL;
    }

    public function getParent($id)
    {
        $parents = $this->getEntity()
            ->getParents($this->getLinkManager()
            ->getEntity())
            ->filter(function (LinkableInterface $link) use($id)
        {
            return $link->getId() == $id;
        });
        return $parents->current();
    }

    public function getChild($id)
    {
        $children = $this->getEntity()
            ->getChildren($this->getLinkManager()
            ->getEntity())
            ->filter(function (LinkableInterface $link) use($id)
        {
            return $link->getId() == $id;
        });
        return $children->current();
    }

    public function orderChildren(array $children)
    {
        $position = 0;
        foreach ($children as $child) {
            if (! array_key_exists('id', $child)) {
                throw new Exception\RuntimeException(sprintf('Key `id` not found. Array should look like `array(array(\'id\' => 1), array(\'id\' => 2))`'));
            }
            $link = $this->getChild($child['id']);
            $entity = $this->getEntity()->positionChild($link, $this->getLinkManager()
                ->getEntity(), $position);
            $this->getObjectManager()->persist($entity);
            $position ++;
        }
        return $this;
    }

    public function orderParents(array $parents)
    {
        $position = 0;
        foreach ($parents as $parent) {
            if (! array_key_exists('id', $parent)) {
                throw new Exception\RuntimeException(sprintf('Key `id` not found. Array should look like `array(array(\'id\' => 1), array(\'id\' => 2))`'));
            }
            $link = $this->getChild($parent['id']);
            $entity = $this->getEntity()->positionParent($link, $this->getLinkManager()
                ->getEntity(), $position);
            $this->getObjectManager()->persist($entity);
            $position ++;
        }
        return $this;
    }

    public function removeChild($child)
    {
        if (! ($child instanceof LinkServiceInterface || $child instanceof LinkableInterface))
            throw new \InvalidArgumentException();
        
        if ($child instanceof LinkServiceInterface)
            $child = $child->getEntity();
        
        $this->getEntity()->removeChild($child, $this->getLinkManager()
            ->getEntity());
    }

    public function removeParent($parent)
    {
        if (! ($parent instanceof LinkServiceInterface || $parent instanceof LinkableInterface))
            throw new \InvalidArgumentException();
        
        if ($parent instanceof LinkServiceInterface)
            $parent = $parent->getEntity();
        
        $this->getEntity()->removeParent($parent, $this->getLinkManager()
            ->getEntity());
    }

    public function getChildren()
    {
        return $this->getEntity()->getChildren($this->getLinkManager()
            ->getEntity());
    }

    public function getParents()
    {
        return $this->getEntity()->getParents($this->getLinkManager()
            ->getEntity());
    }

    public function addParent($parent, $order = NULL)
    {
        if (! ($parent instanceof LinkServiceInterface || $parent instanceof LinkableInterface))
            throw new \InvalidArgumentException();
        
        if ($parent instanceof LinkServiceInterface)
            $parent = $parent->getEntity();
        
        $this->getEntity()->addParent($parent, $this->getLinkManager()
            ->getEntity(), $order);
        
        return $this;
    }

    public function addChild($child, $oder = NULL)
    {
        if (! ($child instanceof LinkServiceInterface || $child instanceof LinkableInterface))
            throw new \InvalidArgumentException();
        
        if ($child instanceof LinkServiceInterface)
            $child = $child->getEntity();
        
        $this->getEntity()->addChild($child, $this->getLinkManager()
            ->getEntity(), $oder);
        
        return $this;
    }
}