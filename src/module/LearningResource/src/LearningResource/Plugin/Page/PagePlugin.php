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
namespace LearningResource\Plugin\Page;

use Entity\Plugin\AbstractPlugin;
use Zend\View\Model\ViewModel;

class PagePlugin extends AbstractPlugin
{

    protected function getDefaultConfig()
    {
        return array(
            'template' => 'learning-resource/plugin/page/default',
            'provider' => 'provider',
            'fields' => array(
            )
        );
    }

    protected function getContentFor($field)
    {
        $fields = $this->getOption('fields');
        $provider = $this->getOption('provider');
        
        if (in_array($field, $fields)) {
            return $this->getEntityService()
                ->$provider()
                ->get($field);
        }
        throw new \Entity\Exception\RuntimeException(sprintf('Could not find an alias for %s', $field));
    }

    public function getTemplate()
    {
        return $this->getOption('template');
    }

    public function hydrate(ViewModel $model)
    {
        foreach($this->getOption('fields') as $field){
            $model->setVariable($field, $this->getContentFor($field));
        }
        return $this;
    }
}