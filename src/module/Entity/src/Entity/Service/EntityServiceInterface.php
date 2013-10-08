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

interface EntityServiceInterface extends EventManagerAwareInterface
{
    public function getTerms();

    public function getId();

    public function setConfig(array $config);

    public function hasPlugin($name);
    
    public function isPluginWhitelisted($name);

    public function whitelistPlugins(array $config);

    public function setPluginOptions($name, array $options);

    public function getPluginOptions($name);

    public function whitelistPlugin($name, $plugin);

    public function getPlugin($name);

    public function plugin($name);
    
    public function getScopesForPlugin($plugin);
    
    public function isVoided();
}