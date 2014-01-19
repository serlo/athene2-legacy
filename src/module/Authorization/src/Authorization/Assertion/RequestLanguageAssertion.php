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
namespace Authorization\Assertion;

use Language\Manager\LanguageManagerAwareTrait;
use Language\Manager\LanguageManagerInterface;
use ZfcRbac\Service\AuthorizationService;

class RequestLanguageAssertion implements ControllerAssertionInterface
{
    use LanguageManagerAwareTrait;

    public function __construct(LanguageManagerInterface $languageManager)
    {
        $this->languageManager = $languageManager;
    }

    public function assert(AuthorizationService $authorizationService)
    {
        $language = $this->getLanguageManager()->getLanguageFromRequest();

        return (new LanguageAssertion())->assert($authorizationService, $language);
    }
}
