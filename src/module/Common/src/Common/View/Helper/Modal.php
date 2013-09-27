<?php
namespace Common\View\Helper;

use Zend\View\Helper\AbstractHelper;

class Modal extends AbstractHelper
{

    protected $html = '';

    public function delete ($text, $href, $class = "btn", $content = "")
    {
        $id = uniqid('modalDelete_');
        
        $this->html .= '
            <div id="' . $id . '" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 id="myModalLabel" class="">' . $this->getView()->translate('Löschung durchführen?') . '</h3>
                </div>
                <div class="modal-body">
                    <p>';
                    if ($content) {
                        $this->html .= $this->getView()->translate($content);
                    } else {
                        $this->html .= $this->getView()->translate('Du bist im Begriff, eine Löschaktion auszuführen. Bei der betreffenden URL handelt es sich um: ') . '<b>' . $href . '</b></p><p>';
                        $this->html .= $this->getView()->translate('Willst du diese Aktion wirklich ausführen?');
                    }
                    $this->html .= '</p>
                </div>
                <div class="modal-footer">
                    <a href="' . $href . '" class="btn btn-danger">' . $this->getView()->translate('Löschen') . '</a>
                    <button class="btn" data-dismiss="modal" aria-hidden="true">' . $this->getView()->translate('Abbrechen') . '</button>
                </div>
            </div>';
            
        $html = '<a href="#' . $id . '" role="button" class="' . $class . '" data-toggle="modal">' . $text . '</a>';
        return $html;
    }
    
    public function update($linktext, $href, $title, $content, $linkclass = 'btn'){
        $id = uniqid('modalUpdate_');
        
        $this->html .= '
            <div id="' . $id . '" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 id="myModalLabel" class="">' . $title . '</h3>
                </div>
                <div class="modal-body">
                    <p>'.$content.'</p>
                </div>
                <div class="modal-footer">
                    <a href="' . $href . '" class="btn btn-success">' . $this->getView()->translate('Speichern') . '</a>
                    <button class="btn" data-dismiss="modal" aria-hidden="true">' . $this->getView()->translate('Abbrechen') . '</button>
                </div>
            </div>';
            
        $html = '<a href="#' . $id . '" role="button" class="' . $linkclass . '" data-toggle="modal">' . $linktext . '</a>';
        return $html;
    }
    
    public function renderDialogues(){
        return $this->html;
    }
}
