<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 */
namespace Search\Result;

interface ContainerInterface
{

    /**
     * @param ResultInterface $reuslt
     * @return void
     */
    public function addResult(ResultInterface $reuslt);

    /**
     * @param ContainerInterface $container
     * @return void
     */
    public function addContainer(ContainerInterface $container);

    /**
     * @return ContainerInterface[]
     */
    public function getContainers();

    /**
     * @return ResultInterface[]
     */
    public function getResults();

    /**
     * @return string
     */
    public function getName();
}
