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
namespace VersioningTest\Entity;

use Versioning\Entity\RevisionInterface;
use User\Entity\UserInterface;

/**
 * @codeCoverageIgnore
 */
class RevisionFake implements RevisionInterface
{
    protected $repository, $author, $date, $id, $fields;
    
	/* (non-PHPdoc)
     * @see \Versioning\Entity\RevisionInterface::getRepository()
     */
    public function getRepository ()
    {
        return $this->repository;
    }

	/* (non-PHPdoc)
     * @see \Versioning\Entity\RevisionInterface::setRepository()
     */
    public function setRepository (\Versioning\Entity\RepositoryInterface $repository)
    {
        $this->repository = $repository;
        return $this;
    }

	/* (non-PHPdoc)
     * @see \Versioning\Entity\RevisionInterface::getTimestamp()
     */
    public function getTimestamp ()
    {
        return $this->date;
    }

	/* (non-PHPdoc)
     * @see \Versioning\Entity\RevisionInterface::getAuthor()
     */
    public function getAuthor ()
    {
        return $this->author;
    }

	/* (non-PHPdoc)
     * @see \Versioning\Entity\RevisionInterface::setDate()
     */
    public function setTimestamp (\DateTime $date)
    {
        $this->date = $date;
        return $this;
    }

	/* (non-PHPdoc)
     * @see \Versioning\Entity\RevisionInterface::setAuthor()
     */
    public function setAuthor (UserInterface $user)
    {
        $this->author = $user;
        return $this;
    }

	/* (non-PHPdoc)
     * @see \Core\Entity\ModelInterface::getId()
     */
    public function getId ()
    {
        return $this->id;
    }

	/* (non-PHPdoc)
     * @see \Core\Entity\ModelInterface::get()
     */
    public function get ($property)
    {
        return $this->fields[$property];
    }

	/* (non-PHPdoc)
     * @see \Core\Entity\ModelInterface::set()
     */
    public function set ($property, $value)
    {
        $this->fields[$property] = $value;
        return $this;
    }
    
	/* (non-PHPdoc)
     * @see \Versioning\Entity\RevisionInterface::setId()
     */
    public function setId ($id)
    {
        $this->id = $id;
    }

}