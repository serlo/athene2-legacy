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
namespace Flag\Manager;

use Flag\Entity\TypeInterface;
use User\Service\UserServiceInterface;
use Flag\Service\FlagServiceInterface;
use Flag\Collection\FlagCollection;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;

interface FlagManagerInterface extends ObjectManagerAwareInterface
{

    /**
     *
     * @param int $id            
     * @return FlagServiceInterface
     */
    public function getFlag($id);

    /**
     *
     * @param int $id            
     * @return TypeInterface
     */
    public function getType($id);

    /**
     *
     * @param string $name            
     * @return TypeInterface
     */
    public function findTypeByName($name);

    /**
     *
     * @return FlagCollection FlagServiceInterface[]
     */
    public function findAllFlags();

    /**
     *
     * @return TypeInterface[]
     */
    public function findAllTypes();

    /**
     *
     * @param int $id            
     * @return $this
     */
    public function removeFlag($id);
    
    /**
     * 
     * @param int $type
     * @param string $content
     * @param int $uuid
     * @param UserServiceInterface $reporter
     * @return FlagServiceInterface
     */
    public function addFlag($type, $content, $uuid, UserServiceInterface $reporter);
}