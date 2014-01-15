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
namespace Entity\Controller;

use Entity\Result;
use Language\Manager\LanguageManagerAwareTrait;
use Zend\EventManager\ResponseCollection;

class EntityController extends AbstractController
{
    use LanguageManagerAwareTrait;

    public function createAction()
    {
        $this->assertGranted('entity.create');

        $type     = $this->params('type');
        $language = $this->getLanguageManager()->getLanguageFromRequest();
        $query    = $this->params()->fromQuery();
        $entity   = $this->getEntityManager()->createEntity(
            $type,
            $query,
            $language
        );

        $this->getEntityManager()->flush();

        $data     = [
            'entity' => $entity,
            'data'   => $query
        ];
        $response = $this->getEventManager()->trigger(
            'create.postFlush',
            $this,
            $data
        );

        $this->checkResponse($response);

        return false;
    }

    public function checkResponse(ResponseCollection $response)
    {
        $redirected = false;
        foreach ($response as $result) {
            if ($result instanceof Result\UrlResult) {
                $this->redirect()->toUrl($result->getResult());
                $redirected = true;
            }
        }

        if (!$redirected) {
            $this->redirect()->toReferer();
        }
    }
}
