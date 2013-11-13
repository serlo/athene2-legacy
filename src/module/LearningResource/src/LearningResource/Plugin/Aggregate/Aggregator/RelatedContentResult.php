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

use Uuid\Entity\UuidInterface;

class RelatedContentResult implements ResultInterface
{

    /**
     *
     * @var string
     */
    protected $url;

    /**
     *
     * @var mixed
     */
    protected $object;

    public function __construct($object)
    {
        $this->setObject($object);
    }

    /**
     *
     * @return mixed $object
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     *
     * @return string $title
     */
    public function getTitle()
    {
        return $this->getObject()->getTitle();
    }

    /**
     *
     * @return string $url
     */
    public function getUrl()
    {
        return $this->getObject()->getUrl();
    }

    /**
     *
     * @param mixed $object            
     * @return $this
     */
    public function setObject($object)
    {
        $this->object = $object;
        return $this;
    }
}