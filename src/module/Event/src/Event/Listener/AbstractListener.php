<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright   Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Event\Listener;

use Common\Listener\AbstractSharedListenerAggregate;
use Event\EventManagerAwareTrait;
use Event\EventManagerInterface;
use Event\Exception\RuntimeException;
use Language\Entity\LanguageInterface;
use Language\Manager\LanguageManagerAwareTrait;
use Language\Manager\LanguageManagerInterface;
use User\Entity\UserInterface;
use User\Manager\UserManagerAwareTrait;
use User\Manager\UserManagerInterface;
use Uuid\Entity\UuidHolder;

abstract class AbstractListener extends AbstractSharedListenerAggregate
{
    use EventManagerAwareTrait, LanguageManagerAwareTrait, UserManagerAwareTrait;

    public function __construct(
        EventManagerInterface $eventManager,
        LanguageManagerInterface $languageManager,
        UserManagerInterface $userManager
    ) {
        if (!class_exists($this->getMonitoredClass())) {
            throw new RuntimeException(sprintf(
                'The class you are trying to attach to does not exist: %s',
                $this->getMonitoredClass()
            ));
        }
        $this->eventManager    = $eventManager;
        $this->languageManager = $languageManager;
        $this->userManager     = $userManager;
    }

    public function logEvent($name, LanguageInterface $language, UserInterface $actor, $uuid, array $params = array())
    {
        if ($uuid instanceof UuidHolder) {
            $uuid = $uuid->getUuidEntity();
        }

        $this->getEventManager()->logEvent($name, $language, $actor, $uuid, $params);
    }
}