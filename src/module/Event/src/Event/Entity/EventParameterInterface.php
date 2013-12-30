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
     * @return string
     */
    public function getName();

    /**
     * 
     * @return UuidInterface
     */
    public function getObject();

    /**
     * 
     * @param EventLogInterface $log
     * @return self
     */
    public function setLog(EventLogInterface $log);

    /**
     * 
     * @param EventParameterNameInterface $name
     * @return self
     */
    public function setName(EventParameterNameInterface $name);

    /**
     * 
     * @param UuidInterface $uuid
     * @return self
     */
    public function setObject(UuidInterface $uuid);
}