<?php
/**
 *
 *
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license LGPL-3.0
 * @license http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Term\Manager;

use Term\Service\TermServiceInterface;
use Language\Service\LanguageServiceInterface;
use Language\Model\LanguageModelInterface;

interface TermManagerInterface
{

    /**
     *
     * @param string $name            
     * @param string $slug            
     * @param LanguageModelInterface $language            
     * @return TermServiceInterface
     */
    public function createTerm($name, $slug = NULL, LanguageModelInterface $language);

    /**
     *
     * @param TermServiceInterface|int|string $term            
     * @return TermServiceInterface
     */
    public function getTerm($term);

    /**
     *
     * @param unknown $name            
     * @param LanguageModelInterface $language            
     * @return TermServiceInterface
     */
    public function findTermByName($name, LanguageModelInterface $language);

    /**
     *
     * @param unknown $slug            
     * @param LanguageModelInterface $language            
     * @return TermServiceInterface
     */
    public function findTermBySlug($slug, LanguageModelInterface $language);
}