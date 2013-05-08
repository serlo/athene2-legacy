<?php
/**
 *
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @copyright 2013 by www.serlo.org
 * @license LGPL
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */
namespace Versioning\Entity;

use Core\Entity\EntityInterface;

interface RevisionInterface extends EntityInterface
{
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
     * @return mixed
     */
    public function getDate();
    
    /**
     * Gets the author
     * 
     * @return EntityInterface
     */
    public function getAuthor();
    
    /**
     * Sets the date
     * 
     * @param mixed $date
     * @return $this
     */
    public function setDate($date);
    
    /**
     * Sets the author
     * 
     * @param EntityInterface $user
     * @return $this
     */
    public function setAuthor(EntityInterface $user);
}