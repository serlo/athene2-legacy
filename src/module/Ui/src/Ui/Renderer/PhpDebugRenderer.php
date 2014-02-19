<?php
namespace Ui\Renderer;

use Zend\View\Exception;
use Zend\View\Model\ModelInterface as Model;
use Zend\View\Renderer\PhpRenderer;

class PhpDebugRenderer extends PhpRenderer
{
    public function render($nameOrModel, $values = null)
    {
        $template = $nameOrModel;
        if ($nameOrModel instanceof Model) {
            $template = $nameOrModel->getTemplate();
        }

        return '<!-- template-identity: ' .  $template . ' -->' . parent::render($nameOrModel, $values); // filter output
    }
}
