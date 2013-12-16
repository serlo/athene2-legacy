<?php
/**
 * 
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @copyright 2013 by www.serlo.org
 * @license LGPL
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */
namespace Link\Service;

use Link\Entity\LinkableInterface;

interface LinkServiceInterface
{

    /**
     *
     * @return LinkableInterface
     */
    public function getEntity();

    /**
     *
     * @param LinkableInterface $parent            
     * @param LinkableInterface $child            
     * @param string $typeName            
     * @param number $position            
     * @return self
     */
    public function associate(LinkableInterface $parent, LinkableInterface $child, $typeName, $position = 0);

    /**
     *
     * @param LinkableInterface $parent            
     * @param LinkableInterface $child            
     * @param string $typeName            
     * @param number $position            
     * @return self
     */
    public function dissociate(LinkableInterface $parent, LinkableInterface $child, $typeName, $position = 0);

    /**
     *
     * @param LinkableInterface $parent            
     * @param string $typename            
     * @param array $children            
     * @return self
     */
    public function sortChildren(LinkableInterface $parent, $typename, array $children);

    /**
     *
     * @param LinkableInterface $parent            
     * @param string $typename            
     * @param array $children            
     * @return self
     */
    public function sortParents(LinkableInterface $child, $typename, array $parents);

    /**
     *
     * @param LinkableInterface $entity            
     * @return self
     */
    public function setEntity(LinkableInterface $entity);
}