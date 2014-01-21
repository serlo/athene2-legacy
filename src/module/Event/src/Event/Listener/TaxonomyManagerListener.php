<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author         Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license        LGPL-3.0
 * @license        http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link           https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright      Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Event\Listener;

use Zend\EventManager\Event;

class TaxonomyManagerListener extends AbstractMvcListener
{

    public function onCreate(Event $e)
    {
        $term     = $e->getParam('term');
        $user     = $this->getUserManager()->getUserFromAuthenticator();
        $language = $this->getLanguageManager()->getLanguageFromRequest();

        $this->logEvent('taxonomy/term/create', $language, $user, $term);
    }

    public function onUpdate(Event $e)
    {
        $term     = $e->getParam('term');
        $user     = $this->getUserManager()->getUserFromAuthenticator();
        $language = $this->getLanguageManager()->getLanguageFromRequest();

        $this->logEvent('taxonomy/term/update', $language, $user, $term);
    }

    public function onAssociate(Event $e)
    {
        $term     = $e->getParam('term');
        $user     = $this->getUserManager()->getUserFromAuthenticator();
        $language = $this->getLanguageManager()->getLanguageFromRequest();

        $this->logEvent(
            'taxonomy/term/associate',
            $language,
            $user,
            $term,
            [
                [
                    'name'   => 'object',
                    'value' => $e->getParam('object')->getUuidEntity()
                ]
            ]
        );
    }

    public function onDissociate(Event $e)
    {
        $term     = $e->getParam('term');
        $user     = $this->getUserManager()->getUserFromAuthenticator();
        $language = $this->getLanguageManager()->getLanguageFromRequest();

        $this->logEvent(
            'taxonomy/term/dissociate',
            $language,
            $user,
            $term,
            [
                [
                    'name'   => 'object',
                    'value' => $e->getParam('object')->getUuidEntity()
                ]
            ]
        );
    }

    public function attachShared(\Zend\EventManager\SharedEventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(
            $this->getMonitoredClass(),
            'create',
            array(
                $this,
                'onCreate'
            )
        );

        $this->listeners[] = $events->attach(
            $this->getMonitoredClass(),
            'update',
            array(
                $this,
                'onUpdate'
            )
        );

        $this->listeners[] = $events->attach(
            $this->getMonitoredClass(),
            'associate',
            array(
                $this,
                'onAssociate'
            )
        );

        $this->listeners[] = $events->attach(
            $this->getMonitoredClass(),
            'dissociate',
            array(
                $this,
                'onDissociate'
            )
        );
    }

    protected function getMonitoredClass()
    {
        return 'Taxonomy\Manager\TaxonomyManager';
    }
}