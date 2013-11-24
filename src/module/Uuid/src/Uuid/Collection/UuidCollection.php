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
namespace Uuid\Collection;

use Common\Collection\AbstractDelegatorCollection;
use Uuid\Exception\InvalidArgumentException;
use Uuid\Entity\UuidInterface;
use Uuid\Manager\UuidManagerInterface;

class UuidCollection extends AbstractDelegatorCollection
{

    /**
     *
     * @return UuidManagerInterface
     */
    public function getManager()
    {
        return parent::getManager();
    }
    
    /*
     * (non-PHPdoc) @see \Common\Collection\AbstractDelegatorCollection::getFromManager()
     */
    public function getFromManager($key)
    {
        if (! $key instanceof UuidInterface)
            throw new InvalidArgumentException(sprintf('`%s` does not implement `EntityManagerInterface`', get_class($key)));
        
        return $this->getManager()->getService($key->getId());
    }

    protected function validManager($manager)
    {
        if (! $manager instanceof UuidManagerInterface)
            throw new InvalidArgumentException(sprintf('`%s` does not implement `UuidManagerInterface`', get_class($manager)));
    }
}