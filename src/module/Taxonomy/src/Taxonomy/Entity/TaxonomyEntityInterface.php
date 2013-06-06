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
namespace Taxonomy\Entity;

interface TaxonomyEntityInterface
{
    /**
     *
     * @return field_type $factory
     */
    public function getFactory ();
    
    /**
     *
     * @param \Doctrine\Common\Collections\ArrayCollection $terms
     * @return $this
     */
    public function setTerms ($terms);
    
    /**
     *
     * @param field_type $name
     * @return $this
     */
    public function setName ($name);
    
    /**
     *
     * @param field_type $factory
     * @return $this
     */
    public function setFactory ($factory);
}