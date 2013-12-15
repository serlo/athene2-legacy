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
namespace Blog\Manager;

use Language\Model\LanguageModelInterface;
use Taxonomy\Model\TaxonomyTermModelInterface;
use Blog\Entity\PostInterface;

interface BlogManagerInterface
{

    /**
     *
     * @param int $id            
     * @return TaxonomyTermModelInterface
     */
    public function getBlog($id);
    
    /**
     * 
     * @param LanguageModelInterface $languageService
     * @return TaxonomyTermModelInterface[]
     */
    public function findAllBlogs(LanguageModelInterface $languageService);
    
    /**
     * Make changes persistent
     * 
     * @return self
     */
    public function flush();
    
    /**
     * 
     * @param int $id
     * @return PostInterface
     */
    public function getPost($id);
}