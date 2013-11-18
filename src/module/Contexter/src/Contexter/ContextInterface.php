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
namespace Contexter;

use Contexter\Entity\ContextInterface;
use Contexter\Entity\TypeInterface;

interface ContextInterface
{

    /**
     *
     * @param ContextInterface $entity            
     * @return $this
     */
    public function setEntity(ContextInterface $entity);

    /**
     *
     * @return ContextInterface;
     */
    public function getEntity();

    /**
     *
     * @return string
     */
    public function getTitle();

    /**
     *
     * @return string
     */
    public function getUrl();

    /**
     *
     * @param string $key            
     * @return mixed
     */
    public function getOption($key);

    /**
     *
     * @param string $routeName            
     * @param array $params            
     * @return $this
     */
    public function addRoute($routeName, array $params = array());

    /**
     *
     * @param string $name            
     * @return TypeInterface
     */
    public function findTypeByName($name);
}