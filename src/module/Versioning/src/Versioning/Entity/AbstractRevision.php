<?php
namespace Versioning\Entity;

use Core\Entity\AbstractEntityAdapter;

abstract class AbstractRevision extends AbstractEntityAdapter implements RevisionInterface
{
	public function getFieldValues() {
	    return array(
	        'id' => $this->getId()
	    );		
	}
    
    public function untrash(){
        $this->getEntity()->set('trashed',FALSE);
        return $this;        
    }
    
    public function delete(){
        return $this;
    }
    
    public function trash(){
        $this->getEntity()->set('trashed',TRUE);
        return $this;
    }
}