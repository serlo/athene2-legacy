<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 */
namespace Instance\Entity;

interface InstanceInterface
{

    /**
     * @return string
     */
    public function __toString();

    /**
     * @return int $id
     */
    public function getId();

    /**
     * @return LanguageInterface
     */
    public function getLanguage();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getSubdomain();

    /**
     * @param string $name
     * @return void
     */
    public function setName($name);
}
