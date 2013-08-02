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
namespace Entity\Plugin\Controller;

use Zend\Mvc\Controller\AbstractActionController;

abstract class AbstractController extends AbstractActionController
{
    use\Entity\Manager\EntityManagerAwareTrait;

    protected function getEntity ($id = NULL)
    {
        if (! $id) {
            $id = $this->getParam('entity');
        }
        
        $entity = $this->getEntityManager()->get($id);
        
        if (! $entity->providesComponent($this->getParam('provider')))
            throw new \Exception('Entity does not know provider `' . $this->getParam('provider') . '`.');
        
        return $entity;
    }
}