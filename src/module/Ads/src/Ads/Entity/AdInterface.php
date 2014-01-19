<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Jakob Pfab (jakob.pfab@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright   Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Ads\Entity;

use Language\Entity\LanguageInterface;
use Upload\Entity\UploadInterface;
use User\Entity\UserInterface;

interface AdInterface
{

    /**
     * Gets the id.
     *
     * @return int
     */
    public function getId();

    /**
     * Gets the content.
     *
     * @return string
     */
    public function getContent();

    /**
     * Gets the title.
     *
     * @return string
     */
    public function getTitle();

    /**
     * Gets the language.
     *
     * @return LanguageInterface
     */
    public function getLanguage();

    /**
     * Gets the image.
     *
     * @return UploadInterface
     */
    public function getImage();

    /**
     * Gets the author.
     *
     * @return UserInterface
     */
    public function getAuthor();

    /**
     * Gets the frequency.
     *
     * @return float
     */
    public function getFrequency();

    /**
     * Gets the clicks.
     *
     * @return int
     */
    public function getClicks();

    /**
     * Gets the views.
     *
     * @return int
     */
    public function getViews();

    /**
     * Gets the url.
     *
     * @return string
     */
    public function getUrl();


}