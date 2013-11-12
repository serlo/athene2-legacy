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
namespace LearningResource\Plugin\Aggregate\Aggregator;

use Uuid\Entity\UuidHolder;
class UuidResult implements ResultInterface
{

    /**
     *
     * @var UuidHolder
     */
    protected $object;
    
    public function __construct($object){
        $this->setObject($object);
    }

    /**
     *
     * @return UuidHolder $object
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     *
     * @param UuidHolder $object            
     * @return $this
     */
    public function setObject(UuidHolder $object)
    {
        $this->object = $object;
        return $this;
    }
    
    /*
     * (non-PHPdoc) @see \LearningResource\Plugin\Aggregate\Aggregator\ResultInterface::getTitle()
     */
    public function getTitle()
    {
        return $this->getObject()->getName();
    }
    
    /*
     * (non-PHPdoc) @see \LearningResource\Plugin\Aggregate\Aggregator\ResultInterface::getRoute()
     */
    public function getRoute()
    {
        return 'uuid/router';
    }
    
    /*
     * (non-PHPdoc) @see \LearningResource\Plugin\Aggregate\Aggregator\ResultInterface::getParams()
     */
    public function getParams()
    {
        return array(
            'uuid' => $this->getObject()->getId(),
        );
    }
}