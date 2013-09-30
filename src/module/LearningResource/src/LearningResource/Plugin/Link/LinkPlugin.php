<?php
/**
 * 
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author	Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license	LGPL-3.0
 * @license	http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link		https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace LearningResource\Plugin\Link;

use Entity\Plugin\AbstractPlugin;
use Entity\Collection\EntityCollection;
use Entity\Exception;
use Entity\Service\EntityServiceInterface;

class LinkPlugin extends AbstractPlugin
{
    use\Link\Manager\SharedLinkManagerAwareTrait,\Common\Traits\ObjectManagerAwareTrait;

    public function addParent(EntityServiceInterface $entity)
    {
        if (! in_array($entity->getType()->getName(), $this->getEntityTypes()))
            throw new Exception\RuntimeException(sprintf('Type %s is not allowed on this association.', $entity->getType()->getName()));
        
        if (! $this->associationAllowed($entity))
            throw new Exception\RuntimeException('One-to-one does not allow multiple associations.');
        
        $this->getLinkService()->addParent($entity->getEntity());
        
        return $this;
    }

    public function addChild(EntityServiceInterface $entity)
    {
        if (! in_array($entity->getType()->getName(), $this->getEntityTypes()))
            throw new Exception\RuntimeException(sprintf('Type %s is not allowed on this association.', $entity->getType()->getName()));
        
        if (! $this->associationAllowed($entity))
            throw new Exception\RuntimeException('One-to-one does not allow multiple associations.');
        
        $this->getLinkService()->addChild($entity->getEntity());
        
        return $this;
    }

    public function hasChild(array $entityTypes = NULL)
    {
        return is_object($this->findChild($entityTypes));
    }

    public function hasChildren(array $entityTypes = NULL)
    {
        return $this->findChildren($entityTypes)->count();
    }

    public function hasParents(array $entityTypes = NULL)
    {
        return $this->findParents($entityTypes)->count();
    }

    public function hasParent(array $entityTypes = NULL)
    {
        return is_object($this->findParent($entityTypes));
    }

    public function findChildren(array $entityTypes = NULL)
    {
        if ($this->isOneToOne())
            throw new Exception\RuntimeException('Link allows only one-to-one associations');
        
        if ($entityTypes === NULL)
            $entityTypes = $this->getEntityTypes();
        
        $collection = $this->getLinkService()
            ->getChildren()
            ->filter(function ($e) use($entityTypes)
        {
            return (in_array($e->getType()
                ->getName(), $entityTypes));
        });
        
        return new EntityCollection($collection, $this->getEntityManager());
    }

    public function findParents(array $entityTypes = NULL)
    {
        if ($this->isOneToOne())
            throw new Exception\RuntimeException('Link allows only one-to-one associations');
        
        $collection = $this->getLinkService()
            ->getParents()
            ->filter(function ($e) use($entityTypes)
        {
            return (in_array($e->getType()
                ->getName(), $entityTypes));
        });
        
        return new EntityCollection($collection, $this->getEntityManager());
    }

    public function findParent($entityTypes = NULL)
    {
        if (! $this->isOneToOne())
            throw new Exception\RuntimeException('Link doesn\'t allow one-to-one associations');
        
        if ($entityTypes === NULL)
            $entityTypes = $this->getEntityTypes();
        
        $collection = $this->getLinkService()
            ->getParents()
            ->filter(function ($e) use($entityTypes)
        {
            return (in_array($e->getType()
                ->getName(), $entityTypes));
        });
        $collection = new EntityCollection($collection, $this->getEntityManager());
        
        return $collection->current();
    }

    public function findChild($entityTypes = NULL)
    {
        if (! $this->isOneToOne())
            throw new Exception\RuntimeException('Link doesn\'t allow one-to-one associations');
        
        if ($entityTypes === NULL)
            $entityTypes = $this->getEntityTypes();
        
        $collection = $this->getLinkService()
            ->getChildren()
            ->filter(function ($e) use($entityTypes)
        {
            return (in_array($e->getType()
                ->getName(), $entityTypes));
        });
        $collection = new EntityCollection($collection, $this->getEntityManager());
        
        return $collection->current();
    }

    public function isOneToOne()
    {
        return $this->getOption('association') == 'one-to-one';
    }

    public function getEntityTypes()
    {
        $return = array();
        foreach ($this->getOption('types') as $type) {
            $return[] = $type['to'];
        }
        return $return;
    }

    protected function getDefaultConfig()
    {
        return array(
            'types' => array(),
            'type' => 'type_not_set',
            'association' => 'one-to-one'
        );
    }

    protected function getLinkService()
    {
        return $this->getSharedLinkManager()
            ->findLinkManagerByName($this->getOption('type'), 'Entity\Entity\EntityLinkType')
            ->getLink($this->getEntityService()
            ->getEntity());
    }

    protected function associationAllowed(EntityServiceInterface $entity)
    {
        if ($this->isOneToOne()) {
            // $where = 'WHERE ' . implode(' OR ');
            // $result = $this->getObjectManager()->createQuery("SELECT e FROM ".get_class($entity->getEntity())." JOIN u.parentLinks p JOIN u.childrenLinks c ")->getResult();
            $foreignScope = 'scope_not_found';
            
            $domesticType = $this->getEntityService()
                ->getType()
                ->getName();
            $foreignType = $entity->getType()->getName();
            
            foreach ($this->getOption('types') as $type) {
                if ($type['to'] == $foreignType) {
                    if (! array_key_exists('reversed_by', $type))
                        throw new Exception\RuntimeException('No reverse side defined');
                    $foreignScope = $type['reversed_by'];
                    break;
                }
            }
            
            if (! $entity->isPluginWhitelisted($foreignScope))
                throw new Exception\RuntimeException(sprintf('Association is not configured as bidirectional. Entity (type: %s) does not know scope %s', $foreignType, $foreignScope));
            
            $original = $this->getEntityService();
            
            $return = ! $entity->$foreignScope()->hasChild((array) $domesticType) && ! $entity->$foreignScope()->hasParent((array) $domesticType);
            
            // we need to do this, because this instance will get out of synch
            $this->setEntityService($original);
            return $return;
        } else {
            return true;
        }
    }
}