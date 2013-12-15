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
namespace Language\Manager;

use Language\Model\LanguageModelInterface;
use Doctrine\Common\Collections\Collection;

interface LanguageManagerInterface
{

    /**
     * 
     * @return LanguageModelInterface
     */
    public function getFallbackLanugage();

    /**
     * 
     * @return LanguageModelInterface
     */
    public function getLanguageFromRequest();

    /**
     * 
     * @param int $id
     * @return LanguageModelInterface
     */
    public function getLanguage($id);
    
    /**
     * 
     * @param string $code
     * @return LanguageModelInterface
     */
    public function findLanguageByCode($code);
    
    /**
     * 
     * @return Collection|LanguageModelInterface[]
     */
    public function findAllLanguages();
}