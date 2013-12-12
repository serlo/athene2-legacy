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
namespace Alias\Listener;

use Zend\EventManager\Event;

class PageControllerListener extends AbstractListener
{

    /**
     * Gets executed on page create
     *
     * @param Event $e            
     * @return void
     */
    public function onUpdate(Event $e)
    {
        $repositoryid = $e->getParam('repositoryid');
        $slug = $e->getParam('slug');
        $repository = $e->getParam('repository');
        $language = $e->getParam('language');
        $entity = $repository;
        
        $url = $e->getTarget()
            ->url()
            ->fromRoute('page/article', array(
            'repositoryid' => $repositoryid
        ));
        
        $this->getAliasManager()->createAlias($url, $slug, $slug . '-' . $repositoryid, $repository->getUuidEntity(), $language);
    }

    public function attachShared(\Zend\EventManager\SharedEventManagerInterface $events)
    {
        $this->listeners[] = $events->attach($this->getMonitoredClass(), 'page.create', array(
            $this,
            'onUpdate'
        ));
    }

    protected function getMonitoredClass()
    {
        return 'Page\Controller\IndexController';
    }
}