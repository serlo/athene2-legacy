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
namespace Blog\Entity;

use User\Entity\UserInterface;
use Taxonomy\Entity\TermTaxonomyInterface;

interface PostInterface
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
     * Sets the content.
     *
     * @param string $content            
     * @return $this
     */
    public function setContent($content);

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
     * Sets the author.
     *
     * @param UserInterface $author            
     * @return $this
     */
    public function setAuthor(UserInterface $author);

    /**
     * Gets the author.
     *
     * @return UserInterface
     */
    public function getAuthor();

    /**
     * Gets the category.
     *
     * @return TermTaxonomyInterface $category
     */
    public function getCategory();

    /**
     * Sets the category.
     *
     * @param TermTaxonomyInterface $category            
     * @return $this
     */
    public function setCategory(TermTaxonomyInterface $category);

    /**
     * Sets the creation date.
     *
     * @param \Datetime $date            
     * @return $this
     */
    public function setDate(\Datetime $date);

    /**
     * Sets the publish date.
     *
     * @param \Datetime $publish            
     * @return $this
     */
    public function setPublish(\Datetime $publish);

    /**
     * Gets the creation date.
     *
     * @return \Datetime
     */
    public function getDate();

    /**
     * Gets the publish date.
     *
     *
     * @return \DateTime
     */
    public function getPublish();
}