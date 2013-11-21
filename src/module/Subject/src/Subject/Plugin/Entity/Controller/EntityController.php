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
namespace Subject\Plugin\Entity\Controller;

use Subject\Plugin\Controller\AbstractController;
use Zend\View\Model\ViewModel;

class EntityController extends AbstractController
{
    public function unrevisedAction(){
        $entities = $this->getPlugin()->getUnrevisedEntities();
        $view = new ViewModel(array('entities' => $entities, 'subject' => $this->getPlugin()->getSubjectService()));
        $view->setTemplate('subject/plugin/entity/unrevised');
        return $view;
    }
    
    public function trashBinAction(){
        $entities = $this->getPlugin()->getTrashedEntities();
        $view = new ViewModel(array('entities' => $entities, 'subject' => $this->getPlugin()->getSubjectService()));
        $view->setTemplate('subject/plugin/entity/trash-bin');
        return $view;
    }
}