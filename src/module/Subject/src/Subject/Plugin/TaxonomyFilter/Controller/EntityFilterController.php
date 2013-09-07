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
namespace Subject\Plugin\Curriculum\Controller;

use Subject\Plugin\Controller\AbstractController;

class EntityFilterController extends AbstractController
{

    public function removeAction ()
    {
        $plugin = $this->getPlugin();
        $plugin->removeEntity($this->params('entity'));
        $this->redirect()->toUrl($this->getRequest()
            ->getHeader('Referer')
            ->getUri());
    }

    public function getPlugin ($id = NULL)
    {
        $plugin = parent::getPlugin($id);
        
        if ($this->params('path', false)) {
            $plugin->setTopic(explode('/', $this->params('path')));
        }
        if ($this->params('curriculum', false)) {
            $plugin->setCurriculum($this->getParam('curriculum'));
        }
        
        return $plugin;
    }

    protected function getTopic ()
    {
        return $this->getPlugin()->getTopic();
    }

    protected function getCurriculum ()
    {
        return $this->getPlugin()->getCurriculum();
    }
}