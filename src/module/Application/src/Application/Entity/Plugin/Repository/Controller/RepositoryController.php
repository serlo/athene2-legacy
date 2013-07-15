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
namespace Application\Plugin\Provider\Repository\Controller;

use Versioning\Exception\RevisionNotFoundException;
use Zend\View\Model\ViewModel;
use Entity\Plugin\Controller\AbstractController;

class RepositoryController extends AbstractController {
	
	public function createRevisionAction() {
		$entity = $this->getEntity ();
		if ($this->getRequest ()->isPost ()) {
			$this->commitRevision ( $entity );
			if ($entity->isCheckedOut ()) {
				$this->redirect ()->toRoute ( $entity->getRoute (), array (
						'action' => 'show',
						'id' => $entity->getId () 
				) );
			} else {
				$this->redirect ()->toRoute ( $entity->getRoute (), array (
						'action' => 'history',
						'id' => $entity->getId () 
				) );
			}
		}
		$view = new ViewModel ( array (
				'entity' => $entity 
		) );
		
		$view->setTemplate ( 'entity/provider/repository/update-revision' );
		$view->setVariable ( 'form', $entity->getForm () );
		
		return $view;
	}
	
	public function compareAction() {
		$entity = $this->getEntity ();
		$repository = $entity->getRepository ();
		$revision = $this->_getRevision ( $entity, $this->getParam ( 'revision' ), false );
		$currentRevision = $this->_getRevision ( $entity );
		
		$view = new ViewModel ( array (
				'currentRevision' => $currentRevision,
				'revision' => $revision,
				'entity' => $entity 
		) );
		
		$view->setTemplate ( 'entity/provider/repository/compare-revision' );
		
		$revisionView = $this->getRevision ( $entity, $revision );
		$currentRevisionView = $this->getRevision ( $entity );
		
		$view->addChild ( $revisionView, 'revisionView' );
		
		if ($currentRevisionView) {
			$view->addChild ( $currentRevisionView, 'currentRevisionView' );
		}
		
		return $view;
	}
	
	public function historyAction() {
		$entity = $this->getEntity ();
		try {
			$currentRevision = $entity->getCurrentRevision ();
		} catch ( RevisionNotFoundException $e ) {
			$currentRevision = NULL;
		}
		$repository = new ViewModel ( array (
				'entity' => $entity,
				'currentRevision' => $currentRevision 
		) );
		$revisions = array ();
		
		$repository->setTemplate ( 'entity/provider/repository/history' );
		$repository->setVariable ( 'revisions', $entity->getAllRevisions () );
		
		$repository->setVariable ( 'trashedRevisions', $entity->getTrashedRevisions () );
		return $repository;
	}
	
	protected function _getRevision($entity, $id = NULL, $catch = TRUE) {
		$repository = $entity;
		if ($catch) {
			try {
				if ($id === NULL) {
					return $repository->getCurrentRevision ();
				} else {
					return $repository->getRevision ( $id );
				}
			} catch ( RevisionNotFoundException $e ) {
				return NULL;
			}
		} else {
			if ($id === NULL) {
				return $repository->getCurrentRevision ();
			} else {
				return $repository->getRevision ( $id );
			}
		}
	}
	
	public function checkoutAction() {
		$entity = $this->getEntity ();
		$entity = $this->getEntity ();
		$repository = $entity;
		$repository->checkout ( $this->getParam ( 'revision' ) );
		$this->redirect ()->toRoute ( $entity->getRoute (), array (
				'action' => 'history',
				'entity' => $entity->getId () 
		) );
	}
	
	public function purgeRevisionAction() {
		$entity = $this->getEntity ();
		$entity = $this->getEntity ();
		$entity->removeRevision ( $this->getParam ( 'revision' ) );
		$this->redirect ()->toRoute ( $entity->getRoute (), array (
				'action' => 'history',
				'entity' => $entity->getId () 
		) );
	}
	
	public function trashRevisionAction() {
		$entity = $this->getEntity ();
		$entity->trashRevision ( $this->getParam ( 'revision' ) );
		$this->redirect ()->toRoute ( $entity->getRoute (), array (
				'action' => 'history',
				'entity' => $entity->getId () 
		) );
	}
	
	public function getHeadAction() {
		$entity = $this->getEntity ();
		return $this->getRevision ( $entity );
	}
	
	public function revisionAction() {
		$entity = $this->getEntity ();
		return $this->getRevision ( $entity, $this->params ( 'revision' ) );
	}
	
	public function getRevision($entity, $revisionId = NULL) {
		$view = new ViewModel ( array (
				'entity' => $entity,
				'repository' => $entity,
				'revision' => $this->_getRevision ( $entity, $revisionId ) 
		) );
		$view->setTemplate ( 'entity/provider/repository/revision' );
		return $view;
	}
	
	protected function commitRevision($entity) {
		$form = $entity->getForm ();
		$form->setData ( $this->getRequest ()->getPost () );
		if ($form->isValid ()) {
			$data = $form->getData ();
			$entity->commitRevision ( $data ['repository'] ['revision'] );
			$this->flashMessenger ()->addSuccessMessage ( 'Deine Bearbeitung wurde gespeichert. Du erhälst eine Benachrichtigung, sobald deine Bearbeitung geprüft wird.' );
		}
		return $entity;
	}
}