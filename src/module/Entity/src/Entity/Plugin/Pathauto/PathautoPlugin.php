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
namespace Entity\Plugin\Pathauto;

use Entity\Plugin\AbstractPlugin;

class PathautoPlugin extends AbstractPlugin
{
    use\Token\TokenizerAwareTrait,\Zend\ServiceManager\ServiceLocatorAwareTrait,\Alias\AliasManagerAwareTrait,\Language\Manager\LanguageManagerAwareTrait;

    protected function getDefaultConfig()
    {
        return array(
            'provider' => 'Entity\Plugin\Pathauto\Provider\TokenProvider',
            'tokenize' => '{subject}/{type}/{title}'
        );
    }

    public function inject()
    {
        $provider = $this->getOption('provider');
        $provider = $this->getServiceLocator()->get($provider);
        /* @var $provider \Token\Provider\ProviderInterface */
        
        $alias = $this->getTokenizer()->transliterate($provider, $this->getEntityService(), $this->getOption('tokenize'));
        
        $languageService = $this->getLanguageManager()->getLanguageFromRequest();
        
        $this->getAliasManager()->createAlias('/entity/view/' . $this->getEntityService()
            ->getId(), strtolower($alias), strtolower($alias . '-' . $this->getEntityService()
            ->getId()), $this->getEntityService()->getEntity(), $languageService);
        
        return $this;
    }
    
	/* (non-PHPdoc)
     * @see \Common\Listener\AbstractSharedListenerAggregate::getMonitoredClass()
     */
    protected function getMonitoredClass ()
    {
        return 'Entity\Controller\EntityController';
    }
}