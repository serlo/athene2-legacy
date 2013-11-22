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
namespace Event\Entity;

use Uuid\Entity\UuidInterface;

interface EventParameterInterface
{

    /**
     *
     * @return int
     */
    public function getId();

    /**
     *
     * @return EventLogInterface
     */
    public function getLog();

    /**
     *
     * @return UuidInterface
     */
    public function getObject();

    /**
     *
     * @return string
     */
    public function getName();

    /**
     * 
     * @param EventLogInterface $log
     * @return $this
     */
    public function setLog(EventLogInterface $log);

    /**
     * 
     * @param UuidInterface $object
     * @return $this
     */
    public function setObject(UuidInterface $object);

    /**
     * 
     * @param EventParameterNameInterface $name
     * @return $this
     */
    public function setName(EventParameterNameInterface $name);
}