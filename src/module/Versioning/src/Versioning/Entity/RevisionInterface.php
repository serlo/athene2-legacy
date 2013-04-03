<?php
namespace Versioning\Entity;

interface RevisionInterface
{    
    /**
     * @return int
     */
    public function getId();
    
    /**
     * @param object $entity
     * @return $this
     */
    public function setEntity($entity);
    
    /**
     * @return $entity
     */
    public function getEntity();
    
    /**
     * @return array
     */
    public function getFieldValues();
    
    /**
     * 
     * @param array $data
     */
    public function setFieldValues(array $data);
    
    /**
     * 
     * @param string $field
     * @return mixed
     */
    public function getFieldValue($field);
    
    /**
     * 
     * @param string $field
     * @param mixed $value
     */
    public function setFieldValue($field, $value);
    
    /**
     * @return void
     */
    public function delete();
    
    /**
     * @return $this
     */
    public function trash();
    public function setConfirmer(User $user);
    public function setConfirmDate($date);
}