<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author         Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license        LGPL-3.0
 * @license        http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link           https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright      Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Markdown\View\Helper;

use Markdown\Exception;
use Markdown\Service\CacheServiceAwareTrait;
use Markdown\Service\RenderServiceAwareTrait;
use Zend\View\Helper\AbstractHelper;

class MarkdownHelper extends AbstractHelper
{
    use RenderServiceAwareTrait;
    use CacheServiceAwareTrait;

    /**
     * @param string      $content
     * @param mixed       $object
     * @param string|null $field
     * @param bool        $catch
     * @return string
     */
    public function toHtml($content, $object = null, $field = null, $catch = true)
    {
        if ($object !== null && $field !== null) {
            try {
                $content = $this->getCacheService()->getCache($object, $field);
            } catch (Exception\RuntimeException $e) {
            }
        }

        if ($catch) {
            try {
                $content = $this->getRenderService()->render($content);
            } catch (Exception\RuntimeException $e) {
            }
        } else {
            $content = $this->getRenderService()->render($content);
        }

        return $content;
    }
}
