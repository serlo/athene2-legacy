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
use Entity\Exception;

abstract class AbstractController extends AbstractActionController
{
    use \Entity\Manager\EntityManagerAwareTrait;

    /**
     * 
     * @param string $id
     * @throws \Exception
     */
    protected function getPlugin ($id = NULL)
    {
        if (! $id) {
            $id = $this->params('entity');
        }
        
        $entity = $this->getEntityManager()->getEntity($id);
        
        if (! $entity->isPluginWhitelisted($this->params('plugin')))
            throw new Exception\RuntimeException(sprintf('Plugin %s not supported.', $this->params('plugin')));
        
        $scope = $this->params('plugin');
        
        return $entity->$scope();
    }
}