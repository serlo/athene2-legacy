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

use Authorization\Exception\InvalidArgumentException;
use Language\Entity\LanguageAwareInterface;
use Language\Entity\LanguageInterface;
use Language\Manager\LanguageManagerAwareTrait;
use ZfcRbac\Service\AuthorizationService;

class LanguageAssertion implements ControllerAssertionInterface
{
    public function assert(AuthorizationService $authorizationService, $language = null)
    {
        if ($language instanceof LanguageInterface) {
        } elseif ($language instanceof LanguageAwareInterface) {
            $language = $language->getLanguage();
        } else {
            throw new InvalidArgumentException(sprintf(
                'Expected instance of LanguageInterface or LanguageAwareInterface but got "%s"',
                is_object($language) ? get_class($language) : gettype($language)
            ));
        }

        return $authorizationService->isGranted($language->getPermission());
    }
}
