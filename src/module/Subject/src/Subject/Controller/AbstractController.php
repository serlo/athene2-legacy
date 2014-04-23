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
namespace Subject\Controller;

use Instance\Manager\InstanceManagerAwareTrait;
use Subject\Manager\SubjectManagerAwareTrait;
use Taxonomy\Manager\TaxonomyManagerAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;

class AbstractController extends AbstractActionController
{
    use SubjectManagerAwareTrait, InstanceManagerAwareTrait, TaxonomyManagerAwareTrait;

    /**
     * @param null $id
     * @return \Taxonomy\Entity\TaxonomyTermInterface
     */
    public function getSubject($id = null)
    {
        $subject = $id ? : $this->params()->fromRoute('subject');

        if (is_numeric($subject)) {
            return $this->getSubjectManager()->getSubject($id);
        }

        return $this->getSubjectManager()->findSubjectByString(
            $subject,
            $this->getInstanceManager()->getInstanceFromRequest()
        );
    }

    public function getTerm($id = null)
    {
        $id = $this->params()->fromRoute('id', $id);
        return $this->taxonomyManager->getTerm($id);
    }
}