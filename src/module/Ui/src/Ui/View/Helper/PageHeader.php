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
namespace Ui\View\Helper;

use Zend\View\Helper\AbstractHelper;

class PageHeader extends AbstractHelper
{
    use \Common\Traits\ConfigAwareTrait;

    protected function getDefaultConfig()
    {
        return array(
            'brand' => 'Athene2',
            'template' => 'common/helper/page-header'
        );
    }

    protected $text;

    protected $subtext;

    public function __invoke($text, $subtext = NULL)
    {
        $this->text = $text;
        $this->subtext = $subtext;
        return $this;
    }

    public function render($setHeadTitle = true)
    {
        if ($setHeadTitle) {
            $delimiter = $this->getOption('delimiter');
            if (strlen($this->subtext) > 0) {
                $headTitle = $this->text . $delimiter . $this->subtext . $delimiter . $this->getOption('brand');
            } else {
                $headTitle = $this->text . $delimiter . $this->getOption('brand');
            }
            $this->getView()->headTitle($headTitle);
        }
        return $this->getView()->partial($this->getOption('template'), array(
            'text' => $this->text,
            'subtext' => $this->subtext
        ));
    }
}