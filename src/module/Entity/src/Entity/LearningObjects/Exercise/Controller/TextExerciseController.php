<?php
/**
 * 
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @copyright 2013 by www.serlo.org
 * @license LGPL
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */
namespace Entity\LearningObjects\Exercise\Controller;

use Entity\LearningObjects\Controller\AbstractController;

class TextExerciseController extends AbstractController
{

    public function indexAction ()
    {
        $id = $this->getParam('id');
        $entity = $this->getEntityManager()->get($id);
        return $entity->toViewModel('display');        
    }

    public function showAction ()
    {
        $id = $this->getParam('id');
        $entity = $this->getEntityManager()->get($id);
        return $entity->toViewModel('display');
    }

    public function updateAction ()
    {
        $id = $this->getParam('id');
        $entity = $this->getEntityManager()->get($id);
        return $entity->toViewModel('form');
    }

    public function createAction ()
    {
        /*
         * $id = $this->getParam('id'); $entity = $this->getEntityManager()->get($id); return $entity->toViewModel('form');
         */
    }
}