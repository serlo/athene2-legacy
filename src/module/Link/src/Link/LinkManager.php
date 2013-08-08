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
namespace Link;

use Link\Manager\AbstractManager;
use Link\Entity\LinkEntityInterface;
use Link\Service\LinkServiceInterface;

class LinkManager extends AbstractManager implements LinkManagerInterface
{
    
    /*
     *
     * (non-PHPdoc)
     * @see
     * \Link\LinkManagerInterface::get()
     */
    public function get ($id)
    {
        if($id instanceof LinkEntityInterface){
            return $this->getInstance($id->getId());
        } else {
            return $this->getInstance($id);
        }
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
            $this->addInstance($entity->getId(), $instance);
        }
        return $this;//->get($entity->getId());
    }
    
    /*
     *
     * (non-PHPdoc)
     * @see
     * \Link\LinkManagerInterface::has()
     */
    public function has ($name)
    {
        return $this->hasInstance($name);
    }
}