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
namespace Blog\Model;

use User\Model\UserModelInterface;
use Taxonomy\Model\TaxonomyTermModelAwareInterface;
use DateTime;
use Common\Model\Wrapable;
use Taxonomy\Model\TaxonomyTermModelInterface;
use Uuid\Entity\UuidHolder;

interface PostModelInterface extends TaxonomyTermModelAwareInterface, Wrapable, UuidHolder
{

    /**
     * Gets the id.
     *
     * @return int
     */
    public function getId();

    /**
     *
     * @return self
     */
    public function getEntity();

    /**
     * Gets the content.
     *
     * @return string
     */
    public function getContent();

    /**
     * Gets the creation date.
     *
     * @return Datetime
     */
    public function getTimestamp();

    /**
     * Gets the publish date.
     *
     *
     * @return DateTime
     */
    public function getPublish();

    /**
     *
     * @return int
     */
    public function isPublished();

    /**
     * Gets the title.
     *
     * @return string
     */
    public function getTitle();

    /**
     * Sets the title.
     *
     * @param string $title            
     * @return $this
     */
    public function setTitle($title);

    /**
     * Gets the author.
     *
     * @return UserInterface
     */
    public function getAuthor();

    /**
     * Gets the category.
     *
     * @return TaxonomyTermModelInterface
     */
    public function getBlog();

    /**
     * Sets the category.
     *
     * @param TaxonomyTermModelInterface $category            
     * @return $this
     */
    public function setBlog(TaxonomyTermModelInterface $category);

    /**
     * Sets the creation date.
     *
     * @param Datetime $date            
     * @return $this
     */
    public function setTimestamp(Datetime $date);

    /**
     * Sets the content.
     *
     * @param string $content            
     * @return $this
     */
    public function setContent($content);

    /**
     * Sets the author.
     *
     * @param UserModelInterface $author            
     * @return $this
     */
    public function setAuthor(UserModelInterface $author);

    /**
     * Sets the publish date.
     *
     * @param Datetime $publish            
     * @return $this
     */
    public function setPublish(Datetime $publish = NULL);
}