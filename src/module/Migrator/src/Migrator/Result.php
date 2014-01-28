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
namespace Migrator;

class Result
{
    protected $map = [];

    public function addResults(array $results)
    {
        foreach($results as $old => $new){
            $this->add($old, $new);
        }
    }

    public function add($old, $new)
    {
        if (isset($this->map[$old])) {
            throw new \Exception();
        }

        $this->map[$old] = $new;
    }

    public function getMap()
    {
        return $this->map;
    }

    public function get($old)
    {
        if (!isset($this->map[$old])) {
            throw new \Exception();
        }

        return $this->map[$old];
    }
}
 