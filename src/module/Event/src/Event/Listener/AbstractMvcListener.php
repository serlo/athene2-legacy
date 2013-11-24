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

use User\Entity\UserInterface;
use Language\Entity\LanguageInterface;
use Common\Listener\AbstractSharedListenerAggregate;
use Uuid\Entity\UuidInterface;
use Uuid\Entity\UuidHolder;

abstract class AbstractMvcListener extends AbstractSharedListenerAggregate
{
    use \Event\EventManagerAwareTrait;

    /**
     * Tells the EventManager to log a certain event.
     * The EventType is automatically generated through the controller.
     *
     * @param string $name            
     * @param LanguageInterface $language            
     * @param UserInterface $actor          
     * @param array $params  
     * @param $uuid            
     * @return void
     */
    public function logEvent($name, LanguageInterface $language, UserInterface $actor, $uuid, array $params = array())
    {
        if($uuid instanceof UuidHolder)
            $uuid = $uuid->getUuidEntity();
        
        $this->getEventManager()->logEvent($name, $language, $actor, $uuid, $params);
    }
}