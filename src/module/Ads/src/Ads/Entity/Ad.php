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

use Doctrine\ORM\Mapping as ORM;
use Language\Entity\LanguageInterface;
use User\Entity\UserInterface;

/**
 * An Ad for 'Bildung im Netz'
 *
 * @ORM\Entity
 * @ORM\Table(name="ads")
 */
class Ad implements AdInterface
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Language\Entity\Language") *
     */
    protected $language;

    /**
     * @ORM\ManyToOne(targetEntity="Attachment\Entity\Attachment")
     */
    protected $image;

    /**
     * @ORM\ManyToOne(targetEntity="User\Entity\User")
     * @ORM\JoinColumn(name="author_id", referencedColumnName="id")
     */
    protected $author;

    /**
     * @ORM\Column(type="text",length=255)
     */
    protected $title;

    /**
     * @ORM\Column(type="text",length=255)
     */
    protected $url;

    /**
     * @ORM\Column(type="text")
     */
    protected $content;

    /**
     * @ORM\Column(type="float")
     */
    protected $frequency;

    /**
     * @ORM\Column(type="integer")
     */
    protected $clicks;


    public function getId()
    {
        return $this->id;
    }

    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getFrequency()
    {
        return $this->frequency;
    }

    public function setFrequency($frequency)
    {
        $this->frequency = $frequency;

        return $this;
    }

    public function setAuthor(UserInterface $author)
    {
        $this->author = $author;

        return $this;
    }

    public function getAuthor()
    {
        return $this->author;
    }



    public function setClicks($clicks)
    {
        $this->clicks = $clicks;

        return $this;
    }

    public function getClicks()
    {
        return $this->clicks;
    }

    public function setLanguage(LanguageInterface $language)
    {
        $this->language = $language;

        return $this;
    }

    public function getLanguage()
    {
        return $this->language;
    }

    public function setAttachment($attachment)
    {
        $this->image = $attachment;

        return $this;
    }

    public function getAttachment()
    {
        return $this->image;
    }

    public function getImage()
    {
        return $this->getAttachment()->getFirstFile();
    }

    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    public function getUrl()
    {
        return $this->url;
    }

}
