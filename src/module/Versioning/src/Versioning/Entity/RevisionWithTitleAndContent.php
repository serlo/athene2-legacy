<?php
namespace Versioning\Entity;

class RevisionWithTitleAndContent extends AbstractRevision
{
    public function getFieldValues(){
        return array(
	        'id' => $this->getId(),
            'title' => $this->getEntity()->title,
            'content' => $this->getEntity()->content
        );
    }
}