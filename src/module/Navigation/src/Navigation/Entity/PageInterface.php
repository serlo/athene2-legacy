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

interface PageInterface
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @param self $page
     * @return void
     */
    public function addChild(self $page);

    /**
     * @param ParameterInterface $parameter
     * @return void
     */
    public function addParameter(ParameterInterface $parameter);

    /**
     * @return PageInterface[]
     */
    public function getChildren();

    /**
     * @return ContainerInterface
     */
    public function getContainer();

    /**
     * @return ParameterInterface[]
     */
    public function getParameters();

    /**
     * @return PageInterface
     */
    public function getParent();

    /**
     * @param self $page
     * @return void
     */
    public function removeChild(self $page);

    /**
     * @param ParameterInterface $parameter
     * @return mixed
     */
    public function removeParameter(ParameterInterface $parameter);

    /**
     * @param ContainerInterface $container
     * @return void
     */
    public function setContainer(ContainerInterface $container);

    /**
     * @param self $page
     * @return PageInterface
     */
    public function setParent(self $page);
}
