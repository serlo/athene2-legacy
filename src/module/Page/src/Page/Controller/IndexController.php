<?php
namespace Page\Controller;
use Stdlib\Model\Registry;
use Page\Entity\Post;
use Page\Form\PostForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Page\Service\PageServiceInterface;
use Page\Service\PageService;
use Doctrine\ORM\EntityManager;


class IndexController extends AbstractActionController
{

	private $pageService;

	public function indexAction ()
	{
		$posts=$this->pageService->read();
		
		
		
		foreach ($posts as $post) : 
		
		
		endforeach;
		
		return new ViewModel(array(
				'posts' => $posts
		));
	}


	public function addAction()
	{
	}

	public function editAction()
	{
	}

	public function deleteAction()
	{
	}

	public function articleAction()
	{
		$slug = $this->getEvent()->getRouteMatch()->getParam('slug');
		$pages=$this->pageService->getPage($slug);
		
		foreach ($pages as $page) :
		$revisionid = (int) $page->__get('current_revision_id');
		$revision = $this->pageService->getRevision($revisionid);
		$content = $revision->getContent();
		$title = $revision->getTitle();
		endforeach;
		
		
		return new ViewModel(array(
				'content' => $content,
				'title' => $title
		));
	}


	public function setPageService (PageServiceInterface $service)
	{
		$this->pageService = $service;
	}
}
