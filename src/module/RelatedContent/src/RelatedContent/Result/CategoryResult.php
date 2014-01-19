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
namespace RelatedContent\Result;

use RelatedContent\Entity\CategoryInterface;
use RelatedContent\Entity\TypeInterface;
use RelatedContent\Exception;

class CategoryResult extends AbstractResult
{

    public function getType()
    {
        return 'category';
    }
    
    public function setObject(TypeInterface $object){
        if(!$object instanceof CategoryInterface)
            throw new Exception\InvalidArgumentException(sprintf('Expected CategoryInterface but got `%s`', get_class($object)));
        return parent::setObject($object);
    }
    
    /**
     *
     * @return CategoryInterface
     */
    public function getObject()
    {
        return parent::getObject();
    }
    
    /*
     * (non-PHPdoc) @see \RelatedContent\Result\ResultInterface::getTitle()
     */
    public function getTitle()
    {
        return $this->getObject()->getTitle();
    }
    
    /*
     * (non-PHPdoc) @see \RelatedContent\Result\ResultInterface::getUrl()
     */
    public function getUrl()
    {
        return NULL;
    }
}