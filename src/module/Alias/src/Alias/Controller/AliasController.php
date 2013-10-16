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
namespace Alias\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Alias;
use Common\Traits;

class AliasController extends AbstractActionController
{
    use Alias\AliasManagerAwareTrait,\Language\Manager\LanguageManagerAwareTrait, Traits\RouterAwareTrait;

    public function forwardAction()
    {
        $source = $this->getAliasManager()->findSourceByAlias($this->params('alias'), $this->getLanguageManager()
            ->getLanguageFromRequest());
        $this->getRouter()->match($request);
        $this->forward($source);
    }
}