<?php
namespace RelatedContent\Entity;

interface TypeInterface
{
    /**
     * 
     * @return int
     */
    public function getId();
    
    /**
     * 
     * @return RelatedContentInterface
     */
    public function getHolder();

    /**
     *
     * @return RelationInterface
     */
    public function getContainer();
    
    /**
     * 
     * @param HolderInterface $container
     * @return $this
     */
    public function setHolder(HolderInterface $holder);
}