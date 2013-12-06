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
namespace Taxonomy\Model;

interface TaxonomyTermNodeModelInterface
{
    /**
     * @return int
     */
    public function getId();

    /**
     *
     * @param int $position            
     * @return self
     */
    public function setPosition($position);

    /**
     *
     * @return int
     */
    public function getPosition();

    /**
     *
     * @param TaxonomyTermModelInterface $taxonomyTerm            
     * @return self
     */
    public function setTaxonomyTerm(TaxonomyTermModelInterface $taxonomyTerm);

    /**
     *
     * @return TaxonomyTermModelInterface
     */
    public function getTaxonomyTerm();
    
    /**
     * 
     * @param object $object
     * @return self
     */
    public function setObject(TaxonomyTermModelAwareInterface $object);
    
    /**
     * 
     * @return TaxonomyTermModelAwareInterface
     */
    public function getObject();
}