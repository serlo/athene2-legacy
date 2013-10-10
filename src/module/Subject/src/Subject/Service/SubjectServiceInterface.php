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
namespace Subject\Service;

interface SubjectServiceInterface
{

    /**
     *
     * @param string $scope            
     * @return bool
     */
    public function isPluginWhitelisted($scope);

    /**
     *
     * @param array $config            
     * @return $this
     */
    public function whitelistPlugins(array $config);

    /**
     *
     * @param string $scope            
     * @param array $options            
     */
    public function setPluginOptions($scope, array $options);

    /**
     *
     * @param string $scope            
     * @return array
     */
    public function getPluginOptions($scope);

    /**
     *
     * @param name $scope            
     * @param mixed $plugin            
     * @return $this
     */
    public function whitelistPlugin($scope, $plugin);

    /**
     *
     * @param string $scope            
     * @return string
     */
    public function getPluginByScope($scope);

    /**
     * Get plugin instance
     *
     * @param string $scope
     *            Name of plugin to return
     * @return mixed
     */
    public function plugin($scope);
}