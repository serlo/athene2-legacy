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

use Attachment\Entity\AttachmentInterface;
use Attachment\Entity\FileInterface;
use Language\Entity\LanguageInterface;
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
     * @return AttachmentInterface
     */
    public function getAttachment();

    /**
     * Gets the image.
     *
     * @return FileInterface
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
     * Gets the url.
     *
     * @return string
     */
    public function getUrl();
}
