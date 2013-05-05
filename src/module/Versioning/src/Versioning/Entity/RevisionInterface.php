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
     *
     * @return void
     */
    public function delete ();

    /**
     *
     * @return $this
     */
    public function trash ();

    public function getRepository ();
    
    public function setRepository(RepositoryInterface $repository);
}