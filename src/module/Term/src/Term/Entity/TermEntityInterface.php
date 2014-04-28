<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 */
namespace Term\Entity;

use Instance\Entity\InstanceAwareInterface;

interface TermEntityInterface extends InstanceAwareInterface
{

    /**
     * @return int $id
     */
    public function getId();

    /**
     * @return string $name
     */
    public function getName();

    /**
     * @return string $slug
     */
    public function getSlug();

    /**
     * @param string $name
     * @return void
     */
    public function setName($name);
}