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
namespace Flag\Entity;

use Uuid\Entity\UuidInterface;
use User\Entity\UserInterface;
use Type\Entity\TypeAwareInterface;

interface FlagInterface extends TypeAwareInterface
{

    /**
     *
     * @return int
     */
    public function getId();

    /**
     *
     * @return UuidInterface
     */
    public function getObject();

    /**
     *
     * @return string
     */
    public function getContent();

    /**
     *
     * @return UserInterface
     */
    public function getReporter();
    
    /**
     * 
     * @return \DateTime
     */
    public function getTimestamp ();

    /**
     *
     * @param UuidInterface $uuid            
     * @return $this
     */
    public function setObject(UuidInterface $uuid);

    /**
     *
     * @param string $content            
     * @return $this
     */
    public function setContent($content);

    /**
     *
     * @param UserInterface $user            
     * @return $this
     */
    public function setReporter(UserInterface $user);
}