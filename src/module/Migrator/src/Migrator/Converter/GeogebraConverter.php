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

class GeogebraConverter extends AbstractConverter
{
    public function convert($text)
    {
        $text = $this->parse_ggb($text, '#<img(?:[^>]+)(?:class="ggb_formula applet")(?:[^>]+)>#isU');
        $text = $this->parse_ggb($text, '#<img(?:[^>]+)(?:class="ggb_formula")(?:[^>]+)>#isU');
        return $text;
    }

    protected function parse_ggb($content, $reg)
    {
        $reg2 = '@(?:[^>]+)alt=\"([^ \"]+)\"(?:[^>]+)@is';
        $reg3 = '@(?:[^>]+)src=\"([^ \"]+)\"(?:[^>]+)@is';

        $altResult = preg_match_all($reg, $content, $matches, PREG_SET_ORDER);
        foreach ($matches as $match) {
            preg_match($reg2, $match[0], $altResult);
            preg_match($reg3, $match[0], $srcResult);
            $srcResult[1] = str_replace('/uploads/', '/uploads/legacy/', $srcResult[1]);
            $altResult[1] = '/uploads/legacy/'.$altResult[1];

            $replace = '<img src="'.$srcResult[1].'" alt="legacy geogebra formula"></img>';
            $replace .= PHP_EOL . PHP_EOL . '<a href="'.$altResult[1].'">Download original Geogebra file</a>';

            $this->needsFlagging = true;

            // replacing the match with the view
            $content = str_replace($match[0], $replace, $content);
        }

        return $content;
    }
}
