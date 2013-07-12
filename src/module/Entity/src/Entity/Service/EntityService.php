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

use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use Core\Collection\DecoratorCollection;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\Form\Form;
use Entity\Entity\EntityInterface;
use Entity\Plugin\PluginManagerAwareInterface;
use Entity\Manager\EntityManagerAwareInterface;
use Entity\Exception\InvalidArgumentException;

class EntityService implements EntityServiceInterface, ServiceLocatorAwareInterface, ObjectManagerAwareInterface, PluginManagerAwareInterface, EntityManagerAwareInterface
{
    use \Zend\ServiceManager\ServiceLocatorAwareTrait,\Common\Traits\ObjectManagerAware,\Entity\Plugin\PluginManagerAware, \Entity\Manager\EntityManagerAware;

    /**
     *
     * @var EntityInterface
     */
    protected $entity;

    /**
     *
     * @var EntityFactoryInterface
     */
    protected $factory;

    /**
     *
     * @return \Entity\Entity\EntityInterface $entity
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     *
     * @param \Entity\Entity\EntityInterface $entity            
     * @return $this
     */
    public function setEntity(EntityInterface $entity)
    {
        $this->entity = $entity;
        return $this;
    }

    public function getTerms()
    {
        return new DecoratorCollection($this->getEntity()->get('terms'), $this->getSharedTaxonomyManager());
    }

    public function refresh()
    {
        if ($this->getObjectManager()->isOpen()) {
            $this->getObjectManager()->refresh($this->getEntity());
        }
        return $this;
    }

    /**
     *
     * @var Form
     */
    protected $form;

    public function setForm(Form $form = NULL)
    {
        if (! $form)
            $form = new Form();
        $this->form = $form;
        return $this;
    }

    public function getForm()
    {
        return $this->form;
    }
    
    protected $pluginWhitelist = array();
    
    public function isPluginWhitelisted($name){
        return isset( $this->pluginWhitelist[$name] ) && $this->pluginWhitelist[$name] === TRUE;
    }
    
    public function whitelistPlugin($name){
        $this->pluginWhitelist[$name] = true;
        return $this;
    }

    /**
     * Get plugin instance
     *
     * @param string $name
     *            Name of plugin to return
     * @param null|array $options
     *            Options to pass to plugin constructor (if not already instantiated)
     * @return mixed
     */
    public function plugin($name, array $options = null)
    {
        if(!$this->isPluginWhitelisted($name)){
            throw new InvalidArgumentException(sprintf('Plugin %s is not whitelisted for this entity.', $name));
        }
        return $this->getPluginManager()->get($name, $options)->injectEntityService($this);
    }

    /**
     * Method overloading: return/call plugins
     *
     * If the plugin is a functor, call it, passing the parameters provided.
     * Otherwise, return the plugin instance.
     *
     * @param string $method            
     * @param array $params            
     * @return mixed
     */
    public function __call($method, $params)
    {
        $plugin = $this->plugin($method);
        if (is_callable($plugin)) {
            return call_user_func_array($plugin, $params);
        }
        
        return $plugin;
    }
}