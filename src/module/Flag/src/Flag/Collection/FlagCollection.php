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
namespace Flag\Collection;

use Common\Collection\AbstractDelegatorCollection;
use Flag\Exception\InvalidArgumentException;
use Flag\Manager\FlagManagerInterface;

class FlagCollection extends AbstractDelegatorCollection
{

    /**
     *
     * @return FlagManagerInterface
     */
    public function getManager()
    {
        return parent::getManager();
    }
    
    public function getFromManager($key)
    {
        return $this->getManager()->getFlag($key->getId());
    }

    protected function validManager($manager)
    {
        if (! $manager instanceof FlagManagerInterface)
            throw new InvalidArgumentException(sprintf('`%s` does not implement `FlagManagerInterface`', get_class($manager)));
    }
}