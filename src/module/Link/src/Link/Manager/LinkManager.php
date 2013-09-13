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
namespace Link\Manager;

use Link\Entity\LinkEntityInterface;
use Link\Exception\InvalidArgumentException;

class LinkManager extends AbstractManager implements LinkManagerInterface
{
    use \Common\Traits\EntityDelegatorTrait;

	/*
     *
     * (non-PHPdoc)
     * @see
     * \Link\LinkManagerInterface::get()
     */
    public function get ($key)
    {
        $id = $key;
        if($key instanceof LinkEntityInterface){
            $id = $key->getId();
            
            // Lazy-Loading
            if(!$this->has($id)){
                $this->add($key);
            }
        } elseif (is_numeric($key)) {
            
        } else {
            throw new InvalidArgumentException();
        }
        return $this->getInstance($id);
    }

    /*public function create (LinkEntityInterface $entity)
    {
        $instance = parent::createInstance('Link\Service\LinkServiceInterface');
        $instance->setEntity($entity);
        $this->add($instance);
        return $instance;
    }*/
    
    /*
     *
     * (non-PHPdoc)
     * @see
     * \Link\LinkManagerInterface::add()
     */
    public function add (LinkEntityInterface $entity)
    {
        if(!$this->has($entity->getId())){
            $instance = parent::createInstance('Link\Service\LinkServiceInterface');
            $instance->setEntity($entity);
            $instance->setLinkManager($this);
            $this->addInstance($entity->getId(), $instance);
        }
        return $this;
    }
    
    /*
     *
     * (non-PHPdoc)
     * @see
     * \Link\LinkManagerInterface::has()
     */
    public function has ($id)
    {
        return $this->hasInstance($id);
    }
}