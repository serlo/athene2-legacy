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

interface EventInterface
{

    /**
     * Returns the event's id.
     *
     * @return int
     */
    public function getId();

    /**
     * Returns the event's name.
     *
     * @return string
     */
    public function getName();

    /**
     * Returns the event's description.
     *
     * @return string
     */
    public function getDescription();

    /**
     * Sets the event's name.
     *
     * @param string $name            
     * @return self
     */
    public function setName($name);

    /**
     * Sets the event's description.
     *
     * @param string $description            
     * @return self
     */
    public function setDescription($description);
}