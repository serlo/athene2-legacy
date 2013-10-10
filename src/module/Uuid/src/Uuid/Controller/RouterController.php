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
namespace Uuid\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use \Uuid\Router\UuidRouterInterface;

class RouterController extends AbstractActionController
{

    /**
     *
     * @var UuidRouterInterface
     */
    protected $uuidRouter;

    /**
     *
     * @return UuidRouterInterface $uuidRouter
     */
    public function getUuidRouter()
    {
        return $this->uuidRouter;
    }

    /**
     *
     * @param UuidRouterInterface $uuidRouter            
     * @return $this
     */
    public function setUuidRouter(UuidRouterInterface $uuidRouter)
    {
        $this->uuidRouter = $uuidRouter;
        return $this;
    }

    public function assembleAction()
    {
        $this->redirect()->toUrl($this->getUuidRouter()
            ->assemble($this->params('uuid')));
        return '';
    }
}