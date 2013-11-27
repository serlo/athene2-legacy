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
namespace Event\Listener;

use Zend\EventManager\Event;

class TaxonomyTermControllerListener extends AbstractMvcListener
{

    public function onCreate(Event $e)
    {
        $language = $e->getParam('language')->getEntity();
        $user = $e->getParam('user')->getEntity();
        $term = $e->getParam('term');
        
        $this->logEvent('taxonomy/term/create', $language, $user, $term);
    }

    public function onUpdate(Event $e)
    {
        $language = $e->getParam('language')->getEntity();
        $user = $e->getParam('user')->getEntity();
        $term = $e->getParam('term')->getEntity();
        
        $this->logEvent('taxonomy/term/update', $language, $user, $term);
    }

    public function onParentChange(Event $e)
    {
        $language = $e->getParam('language')->getEntity();
        $user = $e->getParam('user')->getEntity();
        $term = $e->getParam('term')->getEntity();
        $old = $e->getParam('old')->getEntity()->getUuidEntity();
        $new = $e->getParam('new')->getEntity()->getUuidEntity();
        
        $params = array(
            array(
                'name' => 'old',
                'object' => $old
            ),
            array(
                'name' => 'new',
                'object' => $new
            )
        );
        
        $this->logEvent('taxonomy/term/parent-change', $language, $user, $term, $params);
    }

    public function attachShared(\Zend\EventManager\SharedEventManagerInterface $events)
    {
        $this->listeners[] = $events->attach($this->getMonitoredClass(), 'create', array(
            $this,
            'onCreate'
        ));
        
        $this->listeners[] = $events->attach($this->getMonitoredClass(), 'update', array(
            $this,
            'onUpdate'
        ));
        
        $this->listeners[] = $events->attach($this->getMonitoredClass(), 'parent-change', array(
            $this,
            'onParentChange'
        ));
    }

    protected function getMonitoredClass()
    {
        return 'Taxonomy\Controller\TermController';
    }
}