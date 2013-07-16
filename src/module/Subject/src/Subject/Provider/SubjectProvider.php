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
namespace Subject\Provider;

use Navigation\Provider\ProviderInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class SubjectProvider implements ProviderInterface
{

    protected $serviceLocator;

    protected $options;

    public function __construct(array $options, ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        $this->options = $options;
    }

    public function provideArray()
    {
        $subject = $this->serviceLocator->get('Subject\Manager\SubjectManager')->get($this->options['subject']);
        $configuration = $this->serviceLocator->get('config');
        $path = $this->options['path'];
        $config = array_merge_recursive($configuration['navigation']['default'], include $path . '/' . $subject->getName() . '/navigation.config.php');
        print_r($config);
        die();
        return $config;
    }
}