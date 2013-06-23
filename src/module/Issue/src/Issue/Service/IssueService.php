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
namespace Issue\Service;

use Core\Service\AbstractEntityDecorator;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

class IssueService extends AbstractEntityDecorator implements IssueServiceInterface
{
    public function update (array $data)
    {
        $hydator = new DoctrineHydrator($this->getObjectManager(),get_class($this->getEntity()));
        $hydator->hydrate($data, $this->getEntity());
        $this->persistAndFlush();
        return $this;
    }
}