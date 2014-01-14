<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author    Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license   LGPL-3.0
 * @license   http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link      https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2014 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Discussion\Factory;


use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class DiscussionHelperFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $plugin                = new Discussion();
        $serviceLocator        = $serviceLocator->getServiceLocator();
        $discussionManager     = $serviceLocator->get('Discussion\DiscussionManager');
        $userManager           = $serviceLocator->get('User\Manager\UserManager');
        $languageManager       = $serviceLocator->get('Language\Manager\LanguageManager');
        $sharedTaxonomyManager = $serviceLocator->get('Taxonomy\Manager\TaxonomyManager');

        $plugin->setDiscussionManager($discussionManager);
        $plugin->setUserManager($userManager);
        $plugin->setLanguageManager($languageManager);
        $plugin->setTaxonomyManager($sharedTaxonomyManager);

        return $plugin;
    }
}
