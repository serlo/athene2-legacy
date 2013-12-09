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
namespace Entity\Service;

use Zend\EventManager\EventManagerAwareInterface;
use Common\Normalize\Normalizable;
use Entity\Entity\EntityInterface;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use Entity\Model\EntityModelInterface;

interface EntityServiceInterface extends EventManagerAwareInterface, Normalizable, EntityModelInterface
{

    /**
     * Sets the config
     *
     * @param array $config            
     * @return self
     */
    public function setConfig(array $config);

    /**
     * Checks if a specific scope exists
     *
     * @param string $name            
     * @return bool
     */
    public function hasPlugin($name);

    /**
     *
     * @param string $name            
     * @return bool
     */
    public function isPluginWhitelisted($name);

    /**
     *
     * @param array $config            
     * @return self
     */
    public function whitelistPlugins(array $config);

    /**
     *
     * @param string $name            
     * @param array $options            
     * @return self
     */
    public function setPluginOptions($name, array $options);

    /**
     *
     * @param string $name            
     * @return
     *
     */
    public function getPluginOptions($name);

    /**
     *
     * @param string $name            
     * @param string $plugin            
     */
    public function whitelistPlugin($name, $plugin);
    /**
     *
     * @param unknown $name            
     */
    public function plugin($name);

    /**
     *
     * @param string $plugin            
     * @return array
     */
    public function getScopesForPlugin($plugin);

    /**
     *
     * @param self $entity            
     */
    public function setEntity(EntityInterface $entity);
}