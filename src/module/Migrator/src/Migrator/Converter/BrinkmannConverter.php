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

class BrinkmannConverter extends AbstractConverter
{
    public function convert($text)
    {
        return $this->parse_brinkmann($text);
    }

    protected function parse_brinkmann($content)
    {
        // regexp string
        $reg_brinkmann = '@\[brinkmann\](.*)\[/brinkmann\]@isU';

        // take all matches
        preg_match_all($reg_brinkmann, $content, $matches, PREG_SET_ORDER);
        foreach ($matches as $match) {
            // setting the "view" content
            $view = 'Mehr zum Thema auf unserer Partnerseite <a href="' . $match[1] . '">www.brinkmann-du.de</a>';
            // replacing the match with the view
            $content = str_replace($match[0], $view, $content);
        }

        return $content;
    }
}
