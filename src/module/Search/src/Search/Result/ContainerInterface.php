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
     * @param ResultInterface $result
     * @return void
     */
    public function addResult(ResultInterface $result);

    /**
     * @return ResultInterface[]
     */
    public function getResults();

    /**
     * @return array
     */
    public function toArray();
}
