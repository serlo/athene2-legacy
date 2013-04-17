<?php
namespace Editor\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Auth\Service\AuthServiceInterface;

class Editor extends AbstractHelper
{
	/**
	 * @return AuthServiceInterface
	 */
	public function __invoke()
	{
		return "contenteditable=\"true\"";
	}
}
