<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 */
namespace Flag\Entity;

use Instance\Entity\InstanceAwareInterface;
use Type\Entity\TypeAwareInterface;
use User\Entity\UserInterface;
use Uuid\Entity\UuidInterface;

interface FlagInterface extends TypeAwareInterface, InstanceAwareInterface
{

    /**
     * @return int
     */
    public function getId();

    /**
     * @return UuidInterface
     */
    public function getObject();

    /**
     * @return string
     */
    public function getContent();

    /**
     * @return UserInterface
     */
    public function getReporter();

    /**
     * @return \DateTime
     */
    public function getTimestamp();

    /**
     * @param UuidInterface $uuid
     * @return self
     */
    public function setObject(UuidInterface $uuid);

    /**
     * @param string $content
     * @return self
     */
    public function setContent($content);

    /**
     * @param UserInterface $user
     * @return self
     */
    public function setReporter(UserInterface $user);
}