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
namespace LearningResource\Plugin\Repository;

use Doctrine\Common\Collections\Criteria;
use Entity\Plugin\AbstractPlugin;
use Zend\Form\Form;

class RepositoryPlugin extends AbstractPlugin
{
    use\Common\Traits\ObjectManagerAwareTrait,\Versioning\RepositoryManagerAwareTrait,\Auth\Service\AuthServiceAwareTrait;

    public function getDefaultConfig ()
    {
        return array(
            'revision_form' => 'FormNotFound',
            'field_order' => array()
        );
    }

    /**
     *
     * @return RepositoryServiceInterface
     */
    public function getRepository ()
    {
        $repository = $this->getEntityService()->getEntity();
        $repository->setFieldOrder($this->getOption('field_order'));
        return $this->getRepositoryManager()
            ->addRepository($repository)
            ->getRepository($repository);
    }

    public function getRevisionForm ()
    {
        $form = $this->getOption('revision_form');
        if (! class_exists($form))
            throw new \Exception(sprintf('Class %s not found!', $form));
        $form = new $form();
        return $form;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Versioning\Service\RepositoryServiceInterface::getCurrentRevision()
     */
    public function hasHead ()
    {
        return $this->getRepository()->hasHead();
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Versioning\Service\RepositoryServiceInterface::getCurrentRevision()
     */
    public function getCurrentRevision ()
    {
        return $this->getRepository()->getCurrentRevision();
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Versioning\Service\RepositoryServiceInterface::getCurrentRevision()
     */
    public function hasCurrentRevision ()
    {
        return $this->getRepository()->hasCurrentRevision();
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Versioning\Service\RepositoryServiceInterface::getRevision()
     */
    public function getRevision ($id)
    {
        return $this->getRepository()->getRevision($id);
    }

    public function getAllRevisions ()
    {
        $criteria = Criteria::create()->where(Criteria::expr()->eq("trashed", false))
            ->orderBy(array(
            "id" => "desc"
        ));
        return $this->getRepository()
            ->getRevisions()
            ->matching($criteria);
    }

    public function getTrashedRevisions ()
    {
        $criteria = Criteria::create()->where(Criteria::expr()->eq("trashed", true))
            ->orderBy(array(
            "id" => "desc"
        ));
        return $this->getRepository()
            ->getRevisions()
            ->matching($criteria);
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Versioning\Service\RepositoryServiceInterface::checkoutRevision()
     */
    public function checkout ($revisionId)
    {
        $revision = $this->getRepository()->getRevision($revisionId);
        $this->getRepository()->checkoutRevision($revision);
        return $this->entityService;
    }

    public function commitRevision (Form $form)
    {
        $repository = $this->getRepository();
        
        $revision = $this->getEntityService()
            ->getEntity()
            ->newRevision();
        
        $revision->setAuthor($this->getAuthService()
            ->getUser()
            ->getEntity());
        
        $repository->addRevision($revision);
        $repository->persist();
        
        foreach ($form->getData() as $key => $value) {
            if ($key != 'submit' && $key != 'reset') // haxxy...
                $this->getObjectManager()->persist($revision->addField($key, $value));
        }
        
        $this->getObjectManager()->flush();
        
        return $this;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Versioning\Service\RepositoryServiceInterface::removeRevision()
     */
    public function removeRevision ($revisionId)
    {
        $revision = $this->getRepository()->getRevision($revisionId);
        $this->getRepository()->removeRevision($revision);
        return $this->entityService;
    }

    public function trashRevision ($revisionId)
    {
        $revision = $this->getRepository()->getRevision($revisionId);
        $revision->toggleTrashed();
        $this->getObjectManager()->persist($revision);
        $this->getObjectManager()->flush($revision);
        return $this->entityService;
    }

    public function isCheckedOut ()
    {
        try {
            $this->getCurrentRevision();
            return true;
        } catch (\Versioning\Exception\RevisionNotFoundException $e) {
            return null;
        }
    }

    public function isUnrevised ()
    {
        return $this->getRepository()->isUnrevised();
    }
}