<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author    Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license   LGPL-3.0
 * @license   http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link      https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013-2014 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Navigation\View\Helper;

use Zend\Json\Json as ZendJson;
use Zend\Navigation\Page\AbstractPage;
use Zend\View\Helper\Navigation\Menu;

class Json extends Menu
{
    public function render($container = null)
    {
        $this->parseContainer($container);
        if (null === $container) {
            $container = $this->getContainer();
        }

        $data = $this->process($container);
        $json = new ZendJson;
        return $json->encode($data);
    }

    protected function process($container, $currentDepth = 0, $activeDepth = null)
    {
        if (!$activeDepth) {
            $foundActive = $this->findActive($container, 0, 9999);
            $activeDepth = 99999;
            if (!empty($foundActive)) {
                $activeDepth = $foundActive['depth'];
            }
        }

        $start         = $this->getMinDepth();
        $end           = $start + $this->getMaxDepth();
        $pages         = [];
        $pagePrototype = [
            'label'    => null,
            'class'    => null,
            'href'     => null,
            'elements' => 0,
            'icon'     => null,
            'children' => []
        ];

        /* @var $page AbstractPage */
        foreach ($container as $page) {
            if (!($page->isVisible()
                && $this->accept(
                $page
                )
                && $currentDepth < $end
                && ($currentDepth > $activeDepth || $page->isActive(true)))
            ) {
                continue;
            }
            if ($currentDepth >= $start) {
                if ($page->getLabel() == 'divider') {
                    $addPage          = $pagePrototype;
                    $addPage['class'] = 'divider';
                    $pages[]          = $addPage;
                } else {
                    $addPage             = $pagePrototype;
                    $addPage['label']    = $page->getLabel();
                    $addPage['elements'] = $page->get('elements') ? : 0;
                    $addPage['icon']     = $page->get('icon');
                    $addPage['class']    = $page->getClass();
                    $addPage['href']     = $page->getHref();
                    if (count($page->getPages())) {
                        $addPage['children'] = $this->process($page->getPages(), $currentDepth + 1, $activeDepth);
                    }
                    $pages[] = $addPage;
                }
            } else {
                if (count($page->getPages())) {
                    $addPages = $this->process($page->getPages(), $currentDepth + 1, $activeDepth);
                    $pages[]  = $this->removeWrapping($addPages);
                }
            }
        }

        return $pages;
    }

    protected function removeWrapping(array $data)
    {
        if (is_array($data) && count($data) == 1 && is_numeric(array_keys($data)[0])) {
            return $data[0];
        }
        return $data;
    }
}
 