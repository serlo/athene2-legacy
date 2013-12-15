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

use Entity\Exception\InvalidArgumentException;
use Taxonomy\Collection\TermCollection;
use Zend\Stdlib\ArrayUtils;
use Common\Normalize\Normalized;
use Entity\Model\EntityModelInterface;
use Entity\Model\TypeModelInterface;
use Datetime;
use Taxonomy\Model\TaxonomyTermModelInterface;
use Taxonomy\Model\TaxonomyTermNodeModelInterface;
use License\Entity\LicenseInterface;
use Versioning\Entity\RevisionInterface;
use Link\Entity\LinkTypeInterface;
use Link\Entity\LinkableInterface;
use Entity\Entity\EntityInterface;

class EntityService implements EntityServiceInterface
{
    use\Language\Manager\LanguageManagerAwareTrait,\Zend\ServiceManager\ServiceLocatorAwareTrait,\Entity\Plugin\PluginManagerAwareTrait,\Entity\Manager\EntityManagerAwareTrait,\Zend\EventManager\EventManagerAwareTrait,\Taxonomy\Manager\SharedTaxonomyManagerAwareTrait;

    /**
     *
     * @var EntityModelInterface
     */
    protected $entity;

    /**
     *
     * @var array
     */
    protected $whitelistedPlugins = array();

    /**
     *
     * @var array
     */
    protected $pluginOptions = array();

    public function getEntity()
    {
        return $this->entity;
    }

    public function getTerms()
    {
        return new TermCollection($this->getEntity()->get('terms'), $this->getSharedTaxonomyManager());
    }

    public function getTimestamp()
    {
        return $this->getEntity()->getTimestamp();
    }

    public function getUuid()
    {
        return $this->getEntity()->getUuid();
    }

    public function getType()
    {
        return $this->getEntity()->getType();
    }

    public function getId()
    {
        return $this->getEntity()->getId();
    }

   

    public function setConfig(array $config)
    {
        $this->whitelistPlugins($config['plugins']);
        return $this;
    }

    public function hasPlugin($name)
    {
        return $this->isPluginWhitelisted($name);
    }

    public function isPluginWhitelisted($name)
    {
        return array_key_exists($name, $this->whitelistedPlugins) && $this->whitelistedPlugins[$name] !== FALSE;
    }

    public function getScopesForPlugin($plugin)
    {
        $return = array();
        foreach ($this->pluginOptions as $scope => $options) {
            if ($options['plugin'] == $plugin) {
                $return[] = $scope;
            }
        }
        return $return;
    }

    public function whitelistPlugins(array $config)
    {
        foreach ($config as $name => $data) {
            $this->whitelistPlugin($name, $data['plugin']);
            $this->setPluginOptions($name, $data);
        }
    }

    public function setPluginOptions($name, array $options)
    {
        if (isset($this->pluginOptions[$name])) {
            $options = ArrayUtils::merge($this->pluginOptions[$name], $options);
        }
        
        $this->pluginOptions[$name] = $options;
        return $this;
    }

    public function getPluginOptions($name)
    {
        return (array_key_exists($name, $this->pluginOptions) && array_key_exists('options', $this->pluginOptions[$name])) ? $this->pluginOptions[$name]['options'] : array();
    }

    public function whitelistPlugin($name, $plugin)
    {
        $this->whitelistedPlugins[$name] = $plugin;
        return $this;
    }

    /**
     * Get plugin instance
     *
     * @param string $scope
     *            Name of plugin to return
     * @return mixed
     */
    public function plugin($scope)
    {
        if (! $this->isPluginWhitelisted($scope)) {
            throw new InvalidArgumentException(sprintf('Plugin %s is not whitelisted for this entity.', $scope));
        }
        
        $pluginManager = $this->getPluginManager();
        
        $pluginManager->setEntityService($this);
        $pluginManager->setPluginOptions($this->getPluginOptions($scope));
        
        $plugin = $this->getPluginManager()->get($this->whitelistedPlugins[$scope]);
        $plugin->setScope($scope);
        return $plugin;
    }

    /**
     *
     * @param EntityInterface $entity            
     * @return \Entity\Service\EntityService
     */
    public function setEntity(EntityInterface $entity)
    {
        $this->entity = $entity;
        return $this;
    }

    /**
     * Method overloading: return/call plugins
     * If the plugin is a functor, call it, passing the parameters provided.
     * Otherwise, return the plugin instance.
     *
     * @param string $method            
     * @param array $params            
     * @return mixed
     */
    public function __call($method, $params)
    {
        if ($this->isPluginWhitelisted($method)) {
            $plugin = $this->plugin($method);
            if (is_callable($plugin)) {
                return call_user_func_array($plugin, $params);
            }
            return $plugin;
        } else {
            if (method_exists($this->getEntity(), $method)) {
                return call_user_func_array(array(
                    $this->getEntity(),
                    $method
                ), $params);
            } else {
                throw new \Exception(sprintf('Method %s not defined.', $method));
            }
        }
    }

    public function setType(TypeModelInterface $type)
    {
        $this->getEntity()->setType($type);
        return $this;
    }

    public function setTimestamp(DateTime $date)
    {
        $this->getEntity()->setTimestamp($date);
        return $this;
    }

    public function getLanguage()
    {
        return $this->getLanguageManager()->getLanguage($this->getEntity()
            ->getLanguage()
            ->getId());
    }

    public function setLanguage(\Language\Model\LanguageModelInterface $language)
    {
        $language = $language->getEntity();
        $this->getEntity()->setLanguage($language);
        return $this;
    }

    public function setUuid(\Uuid\Entity\UuidInterface $uuid)
    {
        $this->getEntity()->setUuid($uuid);
        return $this;
    }

    public function getUuidEntity()
    {
        return $this->getEntity()->getUuidEntity();
    }

    public function getTrashed()
    {
        return $this->getEntity()->getTrashed();
    }

    public function setTrashed($trashed)
    {
        $this->getEntity()->setTrashed($trashed);
        return $this;
    }

    public function getHolderName()
    {
        return $this->getEntity()->getHolderName();
    }

    public function addTaxonomyTerm(TaxonomyTermModelInterface $taxonomyTerm, TaxonomyTermNodeModelInterface $node = NULL)
    {
        $this->getEntity()->addTaxonomyTerm($taxonomyTerm, $node);
        return $this;
    }

    public function removeTaxonomyTerm(TaxonomyTermModelInterface $taxonomyTerm, TaxonomyTermNodeModelInterface $node = NULL)
    {
        $this->getEntity()->removeTaxonomyTerm($taxonomyTerm, $node);
        return $this;
    }

    public function getTaxonomyTerms()
    {
        return $this->getEntity()->getTaxonomyTerms();
    }

    public function setLicense(LicenseInterface $license)
    {
        $this->getEntity()->setLicense($license);
        return $this;
    }

    public function getLicense()
    {
        return $this->getEntity()->getLicense();
    }

    public function getRevisions()
    {
        return $this->getEntity()->getRevisions();
    }
    
    /*
     * (non-PHPdoc) @see \Versioning\Entity\RepositoryInterface::newRevision()
     */
    public function newRevision()
    {
        return $this->getEntity()->newRevision();
    }

    public function getCurrentRevision()
    {
        return $this->getEntity()->getCurrentRevision();
    }

    public function hasCurrentRevision()
    {
        return $this->getEntity()->hasCurrentRevision();
    }

    public function setCurrentRevision(RevisionInterface $revision)
    {
        $this->getEntity()->setCurrentRevision($revision);
        return $this;
    }

    public function addRevision(RevisionInterface $revision)
    {
        $this->getEntity()->addRevision($revision);
        return $this;
    }

    public function removeRevision(RevisionInterface $revision)
    {
        $this->getEntity()->addRevision($revision);
        return $this;
    }

    public function getChildren(LinkTypeInterface $type)
    {
        $this->getEntity()->getChildren($type);
        return $this;
    }

    public function getParents(LinkTypeInterface $type)
    {
        $this->getEntity()->getParents($type);
        return $this;
    }

    public function addChild(LinkableInterface $child, LinkTypeInterface $type)
    {
        $this->getEntity()->addChild($child->getEntity(), $type);
        return $this;
    }

    public function addParent(LinkableInterface $parent, LinkTypeInterface $type)
    {
        $this->getEntity()->addParent($parent->getEntity(), $type);
        return $this;
    }

    public function removeChild(LinkableInterface $child, LinkTypeInterface $type)
    {
        $this->getEntity()->addParent($child->getEntity(), $type);
        return $this;
    }

    public function removeParent(LinkableInterface $parent, LinkTypeInterface $type)
    {
        $this->getEntity()->removeParent($parent->getEntity(), $type);
        return $this;
    }

    public function positionChild(LinkableInterface $child, LinkTypeInterface $type, $position)
    {
        $this->getEntity()->positionChild($child->getEntity(), $type, $position);
        return $this;
    }

    public function positionParent(LinkableInterface $parent, LinkTypeInterface $type, $position)
    {
        $this->getEntity()->positionChild($parent->getEntity(), $type, $position);
        return $this;
    }
}