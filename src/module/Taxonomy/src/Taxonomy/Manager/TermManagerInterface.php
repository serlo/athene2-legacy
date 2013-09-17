<?php
/**
 *
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license LGPL-3.0
 * @license http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Taxonomy\Manager;

use Language\Service\LanguageServiceInterface;
use Taxonomy\Entity\TermTaxonomyEntityInterface;
use Taxonomy\Service\TermServiceInterface;

interface TermManagerInterface
{

    /**
     *
     * @param int $id            
     * @return TermServiceInterface
     */
    public function getTerm($id);

    /**
     *
     * @param array $ancestors            
     * @return TermServiceInterface
     */
    public function findTermByAncestors(array $ancestors);

    public function hasTermService($id);

    public function updateTerm($id, array $data);

    public function createTerm(array $data, LanguageServiceInterface $language);

    public function deleteTerm($term);

    public function addTerm(TermServiceInterface $termService);

    /**
     *
     * @return TermCollection
     */
    public function getRootTerms();

    public function getAllowedChildrenTypes();

    public function allowsParentType($type);

    public function getAllowedParentTypes();

    public function getId();

    public function getType();

    public function setType($type);

    public function getTerms();

    public function setTerms($terms);

    /**
     *
     * @return SharedTaxonomyManager $manager
     */
    public function getManager();

    /**
     *
     * @param SharedTaxonomyManager $manager            
     * @return $this
     */
    public function setManager(SharedTaxonomyManager $manager);
}