<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author    Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license   LGPL-3.0
 * @license   http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link      https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013-2014 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Migrator\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * An entity.
 *
 * @ORM\Entity
 * @ORM\Table(name="serlo_dev.blog_posts")
 */
class Blog
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\Column(type="integer", name="user_id")
     */
    protected $author;

    /**
     * @ORM\Column(type="integer")
     */
    protected $date;

    /**
     * @ORM\Column(type="integer")
     */
    protected $publish;

    /**
     * @ORM\Column(type="string")
     */
    protected $title;

    /**
     * @ORM\Column(type="string")
     */
    protected $content;

    /**
     * @return mixed
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return (new \DateTime())->setDate(date('Y', $this->date), date('m', $this->date), date('d', $this->date));
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getPublish()
    {
        return (new \DateTime())->setDate(date('Y', $this->publish), date('m', $this->publish), date('d', $this->publish));
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }
}
