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
use DateTime;
use Uuid\Entity\UuidHolder;

interface RevisionInterface extends UuidHolder
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
    public function getRepository();

    /**
     * Gets the date´
     *
     * @return DateTime
     */
    public function getTimestamp();

    /**
     * Gets the author
     *
     * @return UserInterface
     */
    public function getAuthor();

    /**
     * Sets the date
     *
     * @param DateTime $date            
     * @return self
     */
    public function setTimestamp(DateTime $date);

    /**
     * Sets the author
     *
     * @param UserInterface $user            
     * @return self
     */
    public function setAuthor(UserInterface $user);

    /**
     * Sets the repository
     *
     * @param RepositoryInterface $repository            
     * @return self
     */
    public function setRepository(RepositoryInterface $repository);
}