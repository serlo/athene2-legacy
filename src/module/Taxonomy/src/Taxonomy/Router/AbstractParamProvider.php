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
namespace Taxonomy\Router;

use Taxonomy\Service\TermServiceInterface;

abstract class AbstractParamProvider implements ParamProviderInterface
{

    /**
     *
     * @var TermServiceInterface
     */
    protected $object;

    /**
     *
     * @return TermServiceInterface
     */
    public function getObject()
    {
        return $this->object;
    }

    public function setObject(TermServiceInterface $object)
    {
        $this->object = $object;
        return $this;
    }
}