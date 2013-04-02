<?php
namespace Versioning\Entity;

class RevisionWithTitleAndContent extends AbstractRevision
{
    
    public function getFieldValues(){
        return array(
	        'id' => $this->getId(),
            'content' => $this->getEntity()->content
        );
    }
}