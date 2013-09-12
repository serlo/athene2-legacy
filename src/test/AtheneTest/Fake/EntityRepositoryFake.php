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
namespace AtheneTest\Fake;

use Doctrine\Common\Persistence\ObjectRepository;

abstract class EntityRepositoryFake implements ObjectRepository
{
    protected $className = 'FooBar';
    
    protected $data = array(
        array(
            'id' => 1,
            'foo' => 'bar'
        )
    );
    
    abstract protected function getData();
    
    protected function getReturnValue(array $entity){
        $return = $this->className;
        $return = new $return();
        foreach($entity as $key => $value){
            $method = 'set'.ucfirst($key);
            $return->$method($value);
        }
        return $return;
    }
    
    /* (non-PHPdoc)
     * @see \Doctrine\Common\Persistence\ObjectRepository::find()
     */
    public function find ($id)
    {
        return $this->findOneBy(array('id' => $id));
    }

	/* (non-PHPdoc)
     * @see \Doctrine\Common\Persistence\ObjectRepository::getClassName()
     */
    public function getClassName ()
    {
        return $this->className;
    }

	public function findAll(){
        return $this->getData();
    }
    
    public function findBy(array $params, array $orderBy = NULL, $limit = NULL, $offset = NULL){
        $found = array();
        $i = 0;
        foreach($this->getData() as $entity){
            $add = false;
            foreach($params as $key => $value){
                if($entity[$key] === $value){
                    $add = true;
                } else {
                    $add = false;
                    break;
                }
            }
            if($add){
                $found[$i] = $this->getReturnValue($entity);
                $i++;
            }
        }
        return $found;
    }
    
    public function findOneBy(array $params){
        if(array_key_exists(0, $this->findBy($params))){
            return $this->findBy($params)[0];
        } else {
            return null;
        }
    }
}