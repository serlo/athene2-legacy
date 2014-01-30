<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright   Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace License\Entity;

use Instance\Entity\InstanceAwareInterface;

interface LicenseInterface extends InstanceAwareInterface
{

    /**
     * @return string
     */
    public function getContent();

    /**
     * @return string
     */
    public function getIconHref();

    /**
     * @return int
     */
    public function getId();

    /**
     * @return string
     */
    public function getTitle();

    /**
     * @return string
     */
    public function getUrl();

    /**
     * @param string $content
     * @return self
     */
    public function setContent($content);

    /**
     * @param string $iconHref
     * @return self
     */
    public function setIconHref($iconHref);

    /**
     * @param string $title
     * @return self
     */
    public function setTitle($title);

    /**
     * @param string $url
     * @return self
     */
    public function setUrl($url);
}