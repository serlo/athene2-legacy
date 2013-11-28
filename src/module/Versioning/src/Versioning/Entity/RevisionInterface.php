<?php
/**
 *
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @copyright 2013 by www.serlo.org
 * @license LGPL
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */
namespace Versioning\Entity;

use User\Entity\UserInterface;
interface RevisionInterface
{
    /**
     * 
     * @return int
     */
    public function getId();
    
    /**
     * Returns the repository
     * 
     * @return RepositoryInterface
     */
    public function getRepository ();
    
    /**
     * Sets the repository
     * 
     * @param RepositoryInterface $repository
     * @return $this
     */
    public function setRepository(RepositoryInterface $repository);
    
    /**
     * Gets the date´
     * 
     * @return \DateTime
     */
    public function getDate();
    
    /**
     * Gets the author
     * 
     * @return UserInterface
     */
    public function getAuthor();
    
    /**
     * Sets the date
     * 
     * @param \DateTime $date
     * @return $this
     */
    public function setDate(\DateTime $date);
    
    /**
     * Sets the author
     * 
     * @param UserInterface $user
     * @return $this
     */
    public function setAuthor(UserInterface $user);
}