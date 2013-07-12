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
namespace Entity\Plugin;

use Zend\ServiceManager\AbstractPluginManager;
use Entity\Exception\InvalidArgumentException;

class PluginManager extends AbstractPluginManager implements PluginManagerInterface
{
    /*
     * (non-PHPdoc) @see \Zend\ServiceManager\AbstractPluginManager::validatePlugin()
     */
    public function validatePlugin($plugin)
    {
        if ($plugin instanceof PluginInterface) {
            return true;
        }
        
        throw new InvalidArgumentException(sprintf('%s does not implement %s.', get_class($plugin), __NAMESPACE__.'\PluginManager'));
    }
    
    /*
     * (non-PHPdoc) @see \Entity\Plugin\PluginManagerInterface::add()
     */
    public function add()
    {
        // TODO Auto-generated method stub
    }
}