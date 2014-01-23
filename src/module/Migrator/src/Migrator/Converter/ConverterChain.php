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
namespace Migrator\Converter;

class ConverterChain extends AbstractConverter
{
    protected $converters = [];

    public function convert($text)
    {
        foreach ($this->converters as & $converter) {
            if (!is_object($converter)) {
                $converter = new $converter();
            }
            $text = $converter->convert($text);
            if ($converter->needsFlagging()) {
                $this->needsFlagging = true;
            }
            $converter->clearFlag();
        }

        return $text;
    }
}
