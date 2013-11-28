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

use Link\Entity\LinkableInterface;
use Link\Exception;

class LinkManager extends AbstractManager implements LinkManagerInterface
{
    use\Common\Traits\EntityDelegatorTrait;

    /**
     * 
     * @return LinkableInterface
     */
    public function getEntity()
    {
        return $this->entity;
    }
    
    public function getLink(LinkableInterface $entity)
    {
        $id = $entity->getId();
        if (! $this->hasInstance($id)) {
            $this->addInstance($entity->getId(), $this->createService($entity));
        }
        return $this->getInstance($id);
    }

    protected function createService(LinkableInterface $entity)
    {
        $instance = parent::createInstance('Link\Service\LinkServiceInterface');
        $instance->setEntity($entity);
        $instance->setLinkManager($this);
        return $instance;
    }
}