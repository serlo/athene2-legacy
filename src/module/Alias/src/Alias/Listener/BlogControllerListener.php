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

class BlogControllerListener extends AbstractListener
{

    /**
     * Gets executed on post create & update
     *
     * @param Event $e            
     * @return void
     */
    public function onUpdate(Event $e)
    {
        $blog = $e->getParam('blog');
        $post = $e->getParam('post');
        $actor = $e->getParam('actor');
        $data = $e->getParam('data');
        $language = $e->getParam('language');
        $entity = $post->getEntity();
        
        $url = $e->getTarget()
            ->url()
            ->fromRoute('blog/post/view', array(
            'blog' => $blog->getId(),
            'post' => $post->getId()
        ));
        
        $this->getAliasManager()->autoAlias('blogPost', $url, $entity->getUuidEntity(), $language);
    }

    public function attachShared(\Zend\EventManager\SharedEventManagerInterface $events)
    {
        $this->listeners[] = $events->attach($this->getMonitoredClass(), 'post.create', array(
            $this,
            'onUpdate'
        ));
        
        $this->listeners[] = $events->attach($this->getMonitoredClass(), 'post.update', array(
            $this,
            'onUpdate'
        ));
    }

    protected function getMonitoredClass()
    {
        return 'Blog\Controller\BlogController';
    }
}