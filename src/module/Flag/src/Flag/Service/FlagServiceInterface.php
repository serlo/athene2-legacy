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
namespace Flag\Service;

use Flag\Entity\FlagInterface;
use Uuid\Entity\UuidInterface;
use User\Service\UserServiceInterface;
use Flag\Entity\TypeInterface;

interface FlagServiceInterface
{

    /**
     *
     * @param FlagInterface $flag            
     * @return $this
     */
    public function setEntity(FlagInterface $flag);

    /**
     *
     * @return FlagInterface
     */
    public function getEntity();

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
     * @return UserServiceInterface
     */
    public function getReporter();

    /**
     *
     * @return TypeInterface
     */
    public function getType();
    
    /**
     * 
     * @return \DateTime
     */
    public function getTimestamp ();
}