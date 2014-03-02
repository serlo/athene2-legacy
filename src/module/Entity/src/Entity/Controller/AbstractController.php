<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author    Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license   LGPL-3.0
 * @license   http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link      https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Entity\Controller;

use Entity\Entity\EntityInterface;
use Entity\Exception\EntityNotFoundException;
use Entity\Manager\EntityManagerAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;

abstract class AbstractController extends AbstractActionController
{
    use EntityManagerAwareTrait;

    /**
     * @param int $id
     *
     * @return EntityInterface
     */
    public function getEntity($id = null)
    {
        $id = $id ? : $this->params('entity');
        try {
            return $this->getEntityManager()->getEntity($id);
        } catch (EntityNotFoundException $e) {
            $this->getResponse()->setStatusCode(404);
            return false;
        }
    }
}
