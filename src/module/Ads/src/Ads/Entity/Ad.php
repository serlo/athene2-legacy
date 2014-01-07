<?php
/**
 * 
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author  Jakob Pfab (jakob.pfab@serlo.org)
 * @license	LGPL-3.0
 * @license	http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link		https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */

namespace Ads\Entity;

use Doctrine\ORM\Mapping as ORM;
use User\Entity\UserInterface;
use Ads\Entity\AdInterface;

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
     * @ORM\ManyToOne(targetEntity="Upload\Entity\Upload")
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
    
    /**
     * @ORM\Column(type="integer")
     */
    protected $views;
    
  public function getId(){
      return $this->id;
  }

    public function setContent($content){
        $this->content=$content;
        return $this;
    }
    public function getContent()
    {
        return $this->content;
    }
    
    public function setTitle($title){
        $this->title=$title;
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
    

    public function setAuthor(UserInterface $author)
    {
        $this->author = $author;
        return $this;
    }
    
    public function getAuthor()
    {
        return $this->author;
    }
    
    public function setViews($views){
        $this->views=$views;
        return $this;
    }
    
    public function getViews()
    {
        return $this->views;
    }
    
    public function setClicks($clicks){
        $this->clicks=$clicks;
        return $this;
    }
    
    public function getClicks()
    {
        return $this->clicks;
    }

    public function getImage(){
        return $this->image;
    }
    
    public function setUrl($url){
        $this->url=$url;
        return $this;
    }
    
    public function getUrl()
    {
        return $this->url;
    }
    

    public function populate(array $data = array())
    {
        $this->injectFromArray('author', $data);
        $this->injectFromArray('title', $data);
        $this->injectFromArray('content', $data);
        $this->injectFromArray('image', $data);
        $this->injectFromArray('language', $data);
        $this->injectFromArray('frequency', $data);
        $this->injectFromArray('clicks', $data);
        $this->injectFromArray('url', $data);
        $this->injectFromArray('views', $data);
        return $this;
    }
    

    private function injectFromArray($key, array $array, $default = NULL)
    {
        if (array_key_exists($key, $array)) {
            $this->$key = $array[$key];
        } elseif ($default !== NULL) {
            $this->$key = $default;
        }
    }

}
