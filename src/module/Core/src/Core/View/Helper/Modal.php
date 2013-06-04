<?php
namespace Core\View\Helper;

use Zend\View\Helper\AbstractHelper;

class Modal extends AbstractHelper
{

    public function delete ($text, $href, $class = "", $content = "")
    {
        $id = 'modal_' . uniqid();
        $html = '
<div id="' . $id . '" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel" class="">' . $this->getView()->translate('Löschung durchführen?') . '</h3>
    </div>
    <div class="modal-body">
        <p>';
        if ($content) {
            $html .= $this->getView()->translate($content);
        } else {
            $html .= $this->getView()->translate('Du bist im Begriff, eine Löschaktion auszuführen. Bei der betreffenden URL handelt es sich um: ') . '<b>' . $href . '</b></p><p>';
            $html .= $this->getView()->translate('Willst du diese Aktion wirklich ausführen?');
        }
        $html .= '</p>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">' . $this->getView()->translate('Abbrechen') . '</button>
        <a href="' . $href . '" class="btn btn-danger">' . $this->getView()->translate('Löschen') . '</a>
    </div>
</div>
            
<a href="#' . $id . '" role="button" class="btn ' . $class . '" data-toggle="modal">' . $text . '</a>
		        ';
        return $html;
    }
}
