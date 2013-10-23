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
namespace Discussion\Filter;

use Discussion\Exception;

class DiscussionFilterChain
{
    use \Common\Traits\ConfigAwareTrait,\Common\Traits\EntityManagerAwareTrait,\ClassResolver\ClassResolverAwareTrait;

    protected $chain;

    /**
     *
     * @var PluginManager
     */
    protected $pluginManager;

    /**
     *
     * @return PluginManager $pluginManager
     */
    public function getPluginManager()
    {
        return $this->pluginManager;
    }

    /**
     *
     * @param PluginManager $pluginManager            
     * @return $this
     */
    public function setPluginManager(PluginManager $pluginManager)
    {
        $this->pluginManager = $pluginManager;
        return $this;
    }

    public function __construct()
    {
        $this->chain = array();
    }

    public function getDefaultConfig()
    {
        return array();
    }

    public function attach($callback, array $params = array())
    {
        if ($this->getOption($callback) === NULL)
            throw new Exception\RuntimeException(sprintf('Filter `%s` not found!', $callback));
        
        $filter = $this->getOption($callback);
        //$options = $this->getOption($callback)['options'];
        $plugin = $this->getPluginManager()->get($filter);//, $options);
        $plugin->setParams($params);
        
        $this->chain[] = $plugin;
    }

    public function filter()
    {
        $builder = $this->getEntityManager()->createQueryBuilder();
        $builder->select('c')
            ->from($this->getClassResolver()
            ->resolveClassName('Discussion\Entity\CommentInterface'), 'c')
            ->where($builder->expr()
            ->isNull('c.parent'));
        
        foreach ($this->chain as $filter) {
            $filter->filter($builder);
        }
        
        return $builder->getQuery()->getResult();
    }
}