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

interface RepositoryInterface extends EntityInterface
{

    public function getRevisions ();
    
    public function addRevision();
}