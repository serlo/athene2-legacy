<?php
namespace Versioning\Entity;

use Core\Entity\AbstractEntityAdapter;

class Repository extends AbstractEntityAdapter implements RepositoryInterface
{
	public function getFieldValues() {
	    return array(
	        'id' => $this->getId()
	    );		
	}
    
    public function delete(){
        return $this;
    }
}
