<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author    Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license   LGPL-3.0
 * @license   http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link      https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Contexter\Adapter;

use Language\Manager\LanguageManagerAwareTrait;

class EntityPluginControllerAdapter extends AbstractAdapter
{
    use LanguageManagerAwareTrait;

    public function getProvidedParams()
    {
        $params         = $this->getRouteParams();
        $entityService  = $this->getController()->getEntityService($params['entity']);

        $array = [
            'type'      => $entityService->getType()->getName(),
            'language'  => $this->getLanguageManager()
                            ->getLanguageFromRequest()
                            ->getCode()
        ];

        if ($entityService->hasPlugin('learningResource')) {
            $array['subject'] = $entityService->learningResource()
                ->getDefaultSubject()
                ->getSlug();
        }

        return $array;
    }
}
