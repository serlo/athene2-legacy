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
namespace Language\Collection;

use Common\Collection\AbstractDelegatorCollection;
use Language\Exception\InvalidArgumentException;
use Language\Manager\LanguageManagerInterface;

class LanguageCollection extends AbstractDelegatorCollection
{    
    /*
     * (non-PHPdoc) @see \Common\Collection\AbstractDelegatorCollection::getFromManager()
     */
    public function getFromManager($key)
    {
        $term = $this->getManager()->getLanguage($key->getId());
        return $term;
    }

    protected function validManager($manager)
    {
        if (! $manager instanceof LanguageManagerInterface)
            throw new InvalidArgumentException(sprintf('`%s` does not implement `LanguageManagerInterface`', get_class($manager)));
    }

    /**
     *
     * @return LanguageManagerInterface
     */
    public function getManager()
    {
        return parent::getManager();
    }
}