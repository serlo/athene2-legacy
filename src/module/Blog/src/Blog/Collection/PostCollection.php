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
namespace Blog\Collection;

use Common\Collection\AbstractDelegatorCollection;
use Blog\Exception\InvalidArgumentException;
use Blog\Manager\PostManagerInterface;

class PostCollection extends AbstractDelegatorCollection
{
    /**
     * @return PostManagerInterface
     */
    public function getManager(){
        return parent::getManager();
    }
    
    public function getFromManager ($key)
    {
        return $this->getManager()->getPost($key->getId());
    }
    
    protected function validManager($manager){
        if(!$manager instanceof PostManagerInterface)
            throw new InvalidArgumentException(sprintf('`%s` does not implement `PostManagerInterface`', get_class($manager)));
    }
}