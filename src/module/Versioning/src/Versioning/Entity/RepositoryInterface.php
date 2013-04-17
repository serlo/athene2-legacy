<?php
namespace Versioning\Entity;

use Core\Entity\EntityInterface;
use User\Entity\User;

interface RepositoryInterface
{
	public function setEntity(EntityInterface $entity);
    public function getEntity();
    public function getFieldValues();
    public function setFieldValues(array $data);
    public function getFieldValue($field);
    public function setFieldValue($field, $value);
    public function getId();
    public function get($field);
    public function set($field, $value);    
}