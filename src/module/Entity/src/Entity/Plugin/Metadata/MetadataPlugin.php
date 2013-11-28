<?php
namespace Entity\Plugin\Metadata;

use Entity\Plugin\AbstractPlugin;
use Common\Filter\Slugify;

class MetadataPlugin extends AbstractPlugin
{
    use \Metadata\Manager\MetadataManagerAwareTrait;

    protected function getDefaultConfig()
    {
        return array();
    }

    /**
     * 
     * @return \Metadata\Entity\MetadataInterface[]
     */
    public function getMetadata()
    {
        return $this->getMetadataManager()->findMetadataByObject($this->getEntityService()
            ->getEntity()
            ->getUuidEntity());
    }

    /**
     * 
     * @param unknown $key
     * @return \Metadata\Entity\MetadataInterface[]
     */
    public function findMetadataByKey($key)
    {
        return $this->getMetadataManager()->findMetadataByObjectAndKey($this->getEntityService()
            ->getEntity()
            ->getUuidEntity(), $key);
    }
    
    /**
     * 
     * @return string
     */
    public function getSubject(){
        foreach($this->findMetadataByKey('subject') as $key){
            return $key->getValue();
        }
        return NULL;
    }

    /**
     *
     * @return string
     */
    public function getSlugifiedSubject(){
        $filter = new Slugify();
        return $filter->filter($this->getSubject());
    }
}