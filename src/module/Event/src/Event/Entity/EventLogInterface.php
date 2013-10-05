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

use User\Entity\UserInterface;
use Uuid\Entity\UuidInterface;

interface EventLogInterface
{
    public function setUuid(UuidInterface $uuid);
    public function setEvent(EventInterface $event);
    public function setActor(UserInterface $actor);
    public function setVerb(EventStringInterface $string);
    public function setObject($object);
}