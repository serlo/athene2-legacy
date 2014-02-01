<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author    Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license   LGPL-3.0
 * @license   http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link      https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013-2014 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Navigation\Entity;

interface ParameterInterface
{
    /**
     * @param ParameterInterface $child
     * @return void
     */
    public function addChild(self $child);

    /**
     * @return self[]
     */
    public function getChildren();

    /**
     * @return int
     */
    public function getId();

    /**
     * @return ParameterKeyInterface
     */
    public function getKey();

    /**
     * @return PageInterface
     */
    public function getPage();

    /**
     * @return string|array
     */
    public function getValue();

    /**
     * @return bool
     */
    public function hasChildren();

    /**
     * @param ParameterInterface $child
     * @return void
     */
    public function removeChild(self $child);

    /**
     * @param ParameterKeyInterface $key
     * @return void
     */
    public function setKey(ParameterKeyInterface $key);

    /**
     * @param PageInterface $page
     * @return void
     */
    public function setPage(PageInterface $page);
}
