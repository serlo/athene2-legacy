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
namespace Term\Entity;

interface TermInterface
{

    /**
     *
     * @return int $id
     */
    public function getId();

    /**
     *
     * @param int $id            
     * @return $this
     */
    public function setId($id);

    /**
     *
     * @return \Language\Entity\LanguageInterface $language
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