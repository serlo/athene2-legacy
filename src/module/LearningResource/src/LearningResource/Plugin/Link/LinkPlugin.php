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
    use \Link\Manager\SharedLinkManagerAwareTrait,\Common\Traits\ObjectManagerAwareTrait;
    
    public function orderAssociated(){
        $this->getLinkService();        
    }
    
    public function orderChildren(array $children){
        $this->getLinkService()->orderChildren($children);
        return $this;
    }
    
    public function orderParents(array $parents){
        $this->getLinkService()->orderParents($parents);
        return $this;
    }
    
    public function hasChild(array $entityTypes = NULL)
    {
        return is_object($this->findChild($entityTypes));
    }

    public function hasChildren(array $entityTypes = NULL)
    {
        return $this->findChildren($entityTypes)->count() != 0;
    }

    public function hasParents(array $entityTypes = NULL)
    {
        return $this->findParents($entityTypes)->count() != 0;
    }

    public function hasParent(array $entityTypes = NULL)
    {
        return is_object($this->findParent($entityTypes));
    }

    public function findChildren(array $entityTypes = NULL)
    {
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
        if ($entityTypes === NULL)
            $entityTypes = $this->getEntityTypes();
        
        $collection = $this->getLinkService()
            ->getParents()
            ->filter(function ($e) use($entityTypes)
        {
            return (in_array($e->getType()
                ->getName(), $entityTypes));
        });
        
        return new EntityCollection($collection, $this->getEntityManager());
    }

    public function findParent(array $entityTypes = NULL)
    {
        return $this->findParents($entityTypes)->first();
    }

    public function findChild(array $entityTypes = NULL)
    {
        return $this->findChildren($entityTypes)->first();
    }

    private function isOneToOne()
    {
        return $this->getOption('association') == 'one-to-one';
    }

    public function getEntityTypes()
    {
        return array_keys($this->getOption('types'));
    }

    protected function getDefaultConfig()
    {
        return array(
            'types' => array(),
            'type' => 'type_not_set',
            'association' => ''
        );
    }

    public function getLinkService()
    {
        return $this->getSharedLinkManager()
            ->findLinkManagerByName($this->getOption('type'), 'Entity\Entity\EntityLinkType')
            ->getLink($this->getEntityService()
            ->getEntity());
    }

    public function add(EntityServiceInterface $to)
    {
        if (! in_array($to->getType()->getName(), $this->getEntityTypes()))
            throw new Exception\RuntimeException(sprintf('Type %s is not allowed on this association.', $to->getType()->getName()));
        
        switch ($this->getOption('association')) {
            case 'one-to-one':
                $class = 'Mapping\OneToOneMapper';
                break;
            case 'many-to-one':
                $class = 'Mapping\ManyToOneMapper';
                break;
            case 'one-to-many':
                throw new Exception\RuntimeException('One-to-many associations are not supported yet. You should strongly consider to establish the association from the owned side.');
                $class = 'Mapping\OneToManyMapper';
                break;
            default:
                throw new Exception\RuntimeException(sprintf('Association `%s` unkown. Known associations are: one-to-one and many-to-one', $this->getOption('association')));
                break;
        }
        
        $foreignScope = 'scope_not_found';
        
        $domesticType = $this->getEntityService()
            ->getType()
            ->getName();
        $foreignType = $to->getType()->getName();
        $original = $this->getEntityService();
        $originalScope = $this->getScope();

        if (! array_key_exists('inversed_by', $this->getOption('types')[$foreignType]))
            throw new Exception\RuntimeException('No reverse side defined');
        $foreignScope = $this->getOption('types')[$foreignType]['inversed_by'];
        
        if (! $to->isPluginWhitelisted($foreignScope))
            throw new Exception\RuntimeException(sprintf('Association is not configured as bidirectional. Entity (type: %s) does not know scope %s', $foreignType, $foreignScope));
        
        $class = __NAMESPACE__ . '\\' . $class;
        $isValid = $class::add($this->getEntityService(), $to, $this->getScope(), $foreignScope);
        
        // refresh plugin
        $original->$originalScope();
        
        return $isValid;
    }

    protected function associationAllowed(EntityServiceInterface $entity)
    {
        if ($this->isOneToOne()) {
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
            $original;
            return $return;
        } else {
            return true;
        }
    }
}