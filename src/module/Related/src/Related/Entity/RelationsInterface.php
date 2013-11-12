<?php
/**
 * 
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author	Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license	LGPL-3.0
 * @license	http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link		https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Related\Entity;

use Doctrine\Common\Collections\Collection;

interface RelationsInterface
{
    public function getId();
    
    /**
     * 
     * @return Collection
     */
    public function getInternalRelations();
    /**
     * 
     * @return Collection
     */
    public function getExternalRelations();
    
    /**
     * 
     * @param ExternalRelationInterface $externalRelation
     * @return $this
     */
    public function addExternalRelation(ExternalRelationInterface $externalRelation);
    
    /**
     * 
     * @param InternalRelationInterface $internalRelation
     * @return $this
     */
    public function addInternalRelation(InternalRelationInterface $internalRelation);
}