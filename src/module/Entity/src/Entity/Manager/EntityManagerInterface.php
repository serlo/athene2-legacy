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

namespace Entity\Manager;

use Entity\Service\EntityServiceInterface;
use Entity\Entity\EntityInterface;
use Language\Model\LanguageModelInterface;

interface EntityManagerInterface
{
    /**
     * 
     * @param int $id
     * @return EntityServiceInterface
     */
    public function getEntity($id);
    
    /**
     * 
     * @param string $type
     * @param array $data
     * @param LanguageModelInterface $language
     * @return EntityServiceInterface
     */
    public function createEntity($type, array $data = array(), LanguageModelInterface $language);

    /**
     * 
     * @param string $slug
     * @param LanguageModelInterface $languageService
     * @return EntityServiceInterface
     */
    public function findEntityBySlug($slug, LanguageModelInterface $languageService);
    
    public function purgeEntity($id);
}