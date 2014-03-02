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

use Zend\Filter\StripTags;
use Zend\View\Helper\AbstractHelper;

class PageHeader extends AbstractHelper
{
    use\Common\Traits\ConfigAwareTrait;

    protected function getDefaultConfig()
    {
        return [
            'template' => 'common/helper/page-header'
        ];
    }

    /**
     * @var string
     */
    protected $text = '';

    /**
     * @var string
     */
    protected $subtext = '';

    /**
     * @var string|null
     */
    protected $backLink = '';

    public function __invoke($text)
    {
        $this->text = $this->getView()->translate((string) $text);
        return $this;
    }

    public function setSubtitle($subtext)
    {
        $this->subtext = $this->getView()->translate((string) $subtext);
        return $this;
    }

    public function setBackLink($backLink){
        $this->backLink = $backLink;
        return $this;
    }

    public function render($setHeadTitle = true)
    {
        if ($setHeadTitle) {
            $delimiter = $this->getOption('delimiter');
            if (strlen($this->subtext) > 0) {
                $headTitle = $this->text . $delimiter . $this->subtext . $delimiter . $this->getView()->brand();
            } else {
                $headTitle = $this->text . $delimiter . $this->getOption('brand');
            }
            $filter = new StripTags();
            $this->getView()->headTitle($filter->filter($headTitle));
        }

        return $this->getView()->partial($this->getOption('template'), [
            'text' => $this->text,
            'subtext' => $this->subtext,
            'backLink' => $this->backLink
        ]);
    }
}