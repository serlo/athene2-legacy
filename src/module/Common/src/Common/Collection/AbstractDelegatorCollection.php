<?php
namespace Common\Collection;

use Doctrine\ORM\PersistentCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Selectable;

abstract class AbstractDelegatorCollection implements Collection, Selectable
{
    /**
     * @var object
     */
    protected $manager;

    protected $container = array();
    
    protected $delegates = array();
    
    /**
     * @var Collection
     */
    protected $collection;
    
    abstract public function getDelegate($delegator);
    
    protected function validManager($manager){
        if(!is_object($manager))
            throw new \InvalidArgumentException('Manager must be an object');
    }
    
    public function __construct ($collection, $manager)
    {
        $this->setCollection($collection);
        $this->setManager($manager);
    }

    /**
     *
     * @return $manager
     */
    public function getManager ()
    {
        return $this->manager;
    }

    /**
     *
     * @param $manager
     * @return $this
     */
    public function setManager($manager)
    {
        $this->validManager($manager);
        $this->manager = $manager;
        return $this;
    }

    /**
     *
     * @return new Collections $collection
     */
    public function getCollection ()
    {
        return $this->collection;
    }

    /**
     *
     * @param new Collections\ArrayCollection( $collection
     * @return $this
     */
    public function setCollection (Collection $collection)
    {
        $this->collection = $collection;
        return $this;
    }

    /*
     * (non-PHPdoc) @see \Doctrine\Common\Collections\Collection::add()
    */
    public function add ($element)
    {
        $return = $this->getCollection()->add($this->getDelegate($element));
        $this->hydrate();
        return $return;
    }

    /*
     * (non-PHPdoc) @see \Doctrine\Common\Collections\Collection::clear()
    */
    public function clear ()
    {
        return $this->getCollection()->clear();
    }

    /*
     * (non-PHPdoc) @see \Doctrine\Common\Collections\Collection::contains()
    */
    public function contains ($element)
    {
        return $this->getCollection()->contains($this->getDelegate($element));
    }

    /*
     * (non-PHPdoc) @see \Doctrine\Common\Collections\Collection::isEmpty()
    */
    public function isEmpty ()
    {
        return $this->getCollection()->isEmpty();
    }

    /*
     * (non-PHPdoc) @see \Doctrine\Common\Collections\Collection::remove()
    */
    public function remove ($key)
    {
        return $this->getCollection()->remove($key);
    }

    /*
     * (non-PHPdoc) @see \Doctrine\Common\Collections\Collection::removeElement()
    */
    public function removeElement ($element)
    {
        return $this->getCollection()->removeElement($this->getDelegate($element));
    }

    /*
     * (non-PHPdoc) @see \Doctrine\Common\Collections\Collection::containsKey()
    */
    public function containsKey ($key)
    {
        return $this->getCollection()->containsKey($key);
    }

    /*
     * (non-PHPdoc) @see \Doctrine\Common\Collections\Collection::getKeys()
    */
    public function getKeys ()
    {
        return $this->getCollection()->getKeys();
    }

    /*
     * (non-PHPdoc) @see \Doctrine\Common\Collections\Collection::getValues()
    */
    public function getValues ()
    {
        return $this->getCollection()->getValues();
    }

    /*
     * (non-PHPdoc) @see \Doctrine\Common\Collections\Collection::set()
    */
    public function set ($key, $value)
    {
        return $this->getCollection()->set($key, $value);
    }

    /*
     * (non-PHPdoc) @see \Doctrine\Common\Collections\Collection::toArray()
    */
    public function toArray ()
    {
        return $this->getCollection()->toArray();
    }

    /*
     * (non-PHPdoc) @see \Doctrine\Common\Collections\Collection::first()
    */
    public function first ()
    {
        return $this->getCollection()->first();
    }

    /*
     * (non-PHPdoc) @see \Doctrine\Common\Collections\Collection::last()
    */
    public function last ()
    {
        return $this->getCollection()->last();
    }

    /*
     * (non-PHPdoc) @see \Doctrine\Common\Collections\Collection::key()
    */
    public function key ()
    {
        return $this->getCollection()->key();
    }

    /*
     * (non-PHPdoc) @see \Doctrine\Common\Collections\Collection::current()
    */
    public function current ()
    {
        return $this->getCollection()->current();
    }

    /*
     * (non-PHPdoc) @see \Doctrine\Common\Collections\Collection::next()
    */
    public function next ()
    {
        return $this->getCollection()->next();
    }

    /*
     * (non-PHPdoc) @see \Doctrine\Common\Collections\Collection::exists()
    */
    public function exists (\Closure $p)
    {
        return $this->getCollection()->exists($p);
    }

    /*
     * (non-PHPdoc) @see \Doctrine\Common\Collections\Collection::filter()
    */
    public function filter (\Closure $p)
    {
        if($this->asService){
            $class = get_called_class();
            return new $class($this->getCollection()->filter($p), $this->getManager());
        } else {
            return $this->getCollection()->filter($p);
        }
    }

    /*
     * (non-PHPdoc) @see \Doctrine\Common\Collections\Collection::forAll()
    */
    public function forAll (\Closure $p)
    {
        throw new \Exception('Not implemented yet');
        return $this->getCollection()->forAll($p);
    }

    /*
     * (non-PHPdoc) @see \Doctrine\Common\Collections\Collection::map()
    */
    public function map (\Closure $func)
    {
        throw new \Exception('Not implemented yet');
        return $this->getCollection()->map($func);
    }

    /*
     * (non-PHPdoc) @see \Doctrine\Common\Collections\Collection::partition()
    */
    public function partition (\Closure $p)
    {
        throw new \Exception('Not implemented yet');
        return $this->getCollection()->partition($p);
    }

    /*
     * (non-PHPdoc) @see \Doctrine\Common\Collections\Collection::indexOf()
    */
    public function indexOf ($element)
    {
        return $this->getCollection()->indexOf($this->getDelegate($element));
    }

    /*
     * (non-PHPdoc) @see \Doctrine\Common\Collections\Collection::slice()
    */
    public function slice ($offset, $length = null)
    {
        throw new \Exception('Not implemented yet');
        return $this->getCollection()->slice($offset, $length);
    }

    /*
     * (non-PHPdoc) @see ArrayAccess::offsetExists()
    */
    public function offsetExists ($offset)
    {
        return $this->getCollection()->offsetExists($offset);
    }

    /*
     * (non-PHPdoc) @see ArrayAccess::offsetGet()
    */
    public function offsetGet ($offset)
    {
        return $this->getCollection()->offsetGet($offset);
    }

    /*
     * (non-PHPdoc) @see ArrayAccess::offsetSet()
    */
    public function offsetSet ($offset, $value)
    {
        return $this->getCollection()->offsetSet($offset, $value);
    }

    /*
     * (non-PHPdoc) @see ArrayAccess::offsetUnset()
    */
    public function offsetUnset ($offset)
    {
        return $this->getCollection()->offsetUnset($offset);
    }

    protected $services;

    /*
     * (non-PHPdoc) @see IteratorAggregate::getIterator()
    */
    public function getIterator ()
    {
        if($this->asService){
            $this->hydrate();
            return new \ArrayIterator($this->delegates);
        } else {
            return $this->getCollection()->getIterator();
        }
    }

    /*
     * (non-PHPdoc) @see Countable::count()
    */
    public function count ()
    {
        return $this->getCollection()->count();
    }

    /*
     * (non-PHPdoc) @see \Doctrine\Common\Collections\Collection::get()
    */
    public function get ($key)
    {
        if($this->asService){
            if(array_key_exists($key, $this->delegates)){
                return $this->delegates[$key];                  
            } else {
                $service = $this->getFromManager($this->getCollection()->get($key));
                if($service === NULL){
                    throw new \RuntimeException('getFromManager returned null.');
                }
                $this->delegates[$key] = $service;
                return $service;  
            }          
        } else {
            return $this->getCollection()->get($key);
        }
    }
    
    protected function hydrate(){
        $this->asService();
        foreach($this->getCollection() as $key => $element){
            $this->get($key);
        }
        return $this->revertSettings();
    }

    abstract public function getFromManager ($key);
    /*{
        return $this->getManager()->get($this->get($key));
    }*/
    
    protected $asServiceOld = true;

    protected $asService = true;

    public function asService(){
        $this->asServiceOld = $this->asService;
        $this->asService = true;
        return $this;
    }

    public function asEntity(){
        $this->asServiceOld = $this->asService;
        $this->asService = false;
        return $this;
    }
    
    public function revertSettings(){
        $this->asService = $this->asServiceOld;
        return $this;
    }

    /**
     *
     * @param \Doctrine\Common\Collections\Criteria $criteria
     */
    public function matching (\Doctrine\Common\Collections\Criteria $criteria)
    {
        if(!$this->getCollection() instanceof PersistentCollection)
            throw new \Exception('Collection is not a PersistentCollection.');

        return $this->getCollection()->matching($criteria);
    }
}