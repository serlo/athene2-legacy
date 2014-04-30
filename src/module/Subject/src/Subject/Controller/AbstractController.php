<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 */
namespace Subject\Controller;

use Instance\Manager\InstanceManagerAwareTrait;
use Instance\Manager\InstanceManagerInterface;
use Subject\Manager\SubjectManagerAwareTrait;
use Subject\Manager\SubjectManagerInterface;
use Taxonomy\Manager\TaxonomyManagerAwareTrait;
use Taxonomy\Manager\TaxonomyManagerInterface;
use Zend\Mvc\Controller\AbstractActionController;

class AbstractController extends AbstractActionController
{
    use SubjectManagerAwareTrait, InstanceManagerAwareTrait, TaxonomyManagerAwareTrait;

    public function __construct(
        InstanceManagerInterface $instanceManager,
        SubjectManagerInterface $subjectManager,
        TaxonomyManagerInterface $taxonomyManager
    ) {
        $this->instanceManager = $instanceManager;
        $this->subjectManager  = $subjectManager;
        $this->taxonomyManager = $taxonomyManager;
    }

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
