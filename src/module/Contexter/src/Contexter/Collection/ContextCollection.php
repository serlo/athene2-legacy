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
namespace Contexter\Collection;

use Common\Collection\AbstractDelegatorCollection;
use Contexter\Exception\InvalidArgumentException;
use Contexter\ContexterInterface;

class ContextCollection extends AbstractDelegatorCollection
{

    /**
     *
     * @return ContexterInterface
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
        return $this->getManager()->getContext($key->getId());
    }

    protected function validManager($manager)
    {
        if (! $manager instanceof ContexterInterface)
            throw new InvalidArgumentException(sprintf('`%s` does not implement `ContexterInterface`', get_class($manager)));
    }
}