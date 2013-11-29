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
namespace License\Entity;

use Language\Entity\LanguageInterface;

interface LicenseInterface
{

    /**
     *
     * @return int
     */
    public function getId();

    /**
     *
     * @return string
     */
    public function getUrl();

    /**
     *
     * @return string
     */
    public function getContent();

    /**
     *
     * @return string
     */
    public function getTitle();

    /**
     *
     * @return string
     */
    public function getIconHref();

    /**
     *
     * @return LanguageInterface
     */
    public function getLanguage();

    /**
     *
     * @param LanguageInterface $language            
     * @return $this
     */
    public function setLanguage(LanguageInterface $language);

    /**
     *
     * @param string $url            
     * @return $this
     */
    public function setUrl($url);

    /**
     *
     * @param string $content            
     * @return $this
     */
    public function setContent($content);

    /**
     *
     * @param string $title            
     * @return $this
     */
    public function setTitle($title);

    /**
     *
     * @param string $iconHref            
     * @return $this
     */
    public function setIconHref($iconHref);
}