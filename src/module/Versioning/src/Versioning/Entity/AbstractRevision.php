<?php
/**
 * 
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @copyright 2013 by www.serlo.org
 * @license LGPL
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */
namespace Versioning\Entity;

use Core\Entity\AbstractEntityAdapter;

abstract class AbstractRevision extends AbstractEntityAdapter implements RevisionInterface
{

    public function getFieldValues ()
    {
        return array(
            'id' => $this->getId()
        );
    }

    public function untrash ()
    {
        $this->getEntity()->set('trashed', FALSE);
        return $this;
    }

    public function trash ()
    {
        $this->getEntity()->set('trashed', TRUE);
        return $this;
    }
}