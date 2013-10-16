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
namespace LearningResource\Plugin\Pathauto;

use Entity\Plugin\AbstractPlugin;
use Alias\Exception\AliasNotUniqueException;
use Language\Service\LanguageServiceInterface;

class PathautoPlugin extends AbstractPlugin
{
    use\Token\TokenizerAwareTrait,\Zend\ServiceManager\ServiceLocatorAwareTrait,\Alias\AliasManagerAwareTrait,\Language\Manager\LanguageManagerAwareTrait;

    protected function getDefaultConfig()
    {
        return array(
            'provider' => 'LearningResource\Plugin\Pathauto\Provider\TokenProvider',
            'tokenize' => '{subject}/{type}/{title}'
        );
    }

    public function inject()
    {
        $provider = $this->getOption('provider');
        $provider = $this->getServiceLocator()->get($provider);
        
        /* @var $provider \Token\Provider\ProviderInterface */
        $provider->setEntityService($this->getEntityService());
        
        $this->getTokenizer()->setProvider($provider);
        $alias = $this->getTokenizer()->transliterate($this->getOption('tokenize'));
        
        $languageService = $this->getLanguageManager()->getLanguageFromRequest();
        
        $this->getAliasManager()->createAlias('/entity/view/' . $this->getEntityService()
            ->getId(), strtolower($alias), strtolower($alias . '-' . $this->getEntityService()
            ->getId()), $languageService);
        
        return $this;
    }
}