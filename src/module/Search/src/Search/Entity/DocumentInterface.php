<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 */
namespace Search\Entity;

interface DocumentInterface
{
    /**
     * @return string
     */
    public function getContent();

    /**
     * @return int
     */
    public function getId();

    /**
     * @return int|null
     */
    public function getInstance();

    /**
     * @return array
     */
    public function getKeywords();

    /**
     * @return string
     */
    public function getLink();

    /**
     * @return string
     */
    public function getTitle();

    /**
     * @return string
     */
    public function getType();

    /**
     * @return array
     */
    public function toArray();
}
