<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 */
namespace Subject\Controller;

use Zend\View\Model\ViewModel;

class EntityController extends AbstractController
{
    public function trashBinAction()
    {
        $subject  = $this->getSubject();
        $entities = $this->getSubjectManager()->getTrashedEntities($subject);
        $view     = new ViewModel(['entities' => $entities, 'subject' => $subject]);
        $view->setTemplate('subject/entity/trash-bin');
        return $view;
    }

    public function unrevisedAction()
    {
        $subject  = $this->getSubject();
        $entities = $this->getSubjectManager()->getUnrevisedRevisions($subject);
        $view     = new ViewModel(['entities' => $entities, 'subject' => $subject]);
        $view->setTemplate('subject/entity/unrevised');
        return $view;
    }
}
