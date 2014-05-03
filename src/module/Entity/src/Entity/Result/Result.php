<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 */
namespace Entity\Result;

class Result implements ResultInterface
{

    protected $result;

    /**
     * @return field_type $result
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param field_type $result
     * @return self
     */
    public function setResult($result)
    {
        $this->result = $result;

        return $this;
    }
}
