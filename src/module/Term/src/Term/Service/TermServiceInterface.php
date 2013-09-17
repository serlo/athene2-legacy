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
namespace Term\Service;

use Term\Manager\TermManagerInterface;

interface TermServiceInterface
{
    /**
     * @param TermManagerInterface $manager
     * @return $this
     */
    public function setManager(TermManagerInterface $manager);
    
    /**
     * 
     * @return TermManagerInterface
     */
    public function getManager();

    /**
     *
     * @return \Language\Service\LanguageServiceInterface $language
     */
    public function getLanguage();

    /**
     *
     * @return field_type $name
     */
    public function getName();

    /**
     *
     * @return field_type $slug
     */
    public function getSlug();

    /**
     *
     * @param int $language            
     * @return $this
     */
    public function setLanguage($language);

    /**
     *
     * @param string $name            
     * @return $this
     */
    public function setName($name);

    /**
     *
     * @param string $slug            
     * @return $this
     */
    public function setSlug($slug);
}