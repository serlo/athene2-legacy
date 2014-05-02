<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author    Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license   LGPL-3.0
 * @license   http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link      https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013-2014 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Normalizer\Controller;

use Instance\Manager\InstanceManagerInterface;
use Uuid\Entity\UuidInterface;
use Uuid\Manager\UuidManagerInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class SitemapController extends AbstractActionController
{
    /**
     * @var InstanceManagerInterface
     */
    protected $instanceManager;

    /**
     * @var UuidManagerInterface
     */
    protected $uuidManager;

    public function __construct(InstanceManagerInterface $instanceManager, UuidManagerInterface $uuidManager)
    {
        $this->instanceManager = $instanceManager;
        $this->uuidManager     = $uuidManager;
    }

    public function indexAction()
    {
        $instance = $this->instanceManager->getInstanceFromRequest();
        $objects  = $this->uuidManager->findUuidsByInstance($instance);
        $objects  = $objects->filter(
            function (UuidInterface $object) {
                return !$object->isTrashed();
            }
        );
        $view     = new ViewModel(['objects' => $objects]);
        $view->setTemplate('normalizer/sitemap');
        $view->setTerminal(true);
        return $view;
    }
}
 