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
namespace Search\Adapter\SphinxQL;

use Search\Adapter\AdapterInterface;
use Foolz\SphinxQL\Connection;
use Foolz\SphinxQL\SphinxQL;

abstract class AbstractSphinxAdapter implements AdapterInterface
{

    /**
     *
     * @var Connection
     */
    protected $connection;

    /**
     *
     * @return Connection $connection
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     *
     * @param Connection $connection            
     * @return $this
     */
    public function setConnection(Connection $connection)
    {
        $this->connection = $connection;
        return $this;
    }
    
    public function forge(){
        return SphinxQL::forge($this->getConnection());
    }
}