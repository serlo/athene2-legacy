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

class PostConverter extends AbstractConverter
{

    protected $map = [];

    public function convert($text, $map = null)
    {
        $this->map = $map;
        $text      = $this->parse_exercise($text);
        $text      = $this->parse_folder($text);
        $text      = $this->parse_standalone_wiki($text);
        $text      = $this->parse_wiki($text);

        return $text;
    }

    public function parse_exercise($content)
    {
        // setting the regexp string
        $reg_exercise = '@\[exercise\](.*)\[\\\/exercise\]@iU';

        // take all matches
        preg_match_all($reg_exercise, $content, $matches, PREG_SET_ORDER);
        foreach ($matches as $match) {

            if(!isset($this->map['exercise'][$match[1]])){
                $content = str_replace($match[0], " **Exercise $match[1] not found** ", $content);
                continue;
            }

            $id   = $this->map['exercise'][$match[1]]->getId();
            $view = " >[Übungsaufgabe](/ref/$id) ";

            // replace the match with the view content
            $content = str_replace($match[0], $view, $content);
        }

        return $content;
    }

    public function parse_folder($content)
    {
        // regexp string
        $reg_folder = '@\[folder\](.*)\[\\\/folder\]@isU';

        // take all matches
        preg_match_all($reg_folder, $content, $matches, PREG_SET_ORDER);
        foreach ($matches as $match) {
            // setting the "view" content

            if(!isset($this->map['folder'][$match[1]])){
                $content = str_replace($match[0], " **Folder $match[1] not found** ", $content);
                continue;
            }

            $id   = $this->map['folder'][$match[1]]->getId();
            $view = " [Zum Thema](/ref/$id) ";

            // replacing the match with the view
            $content = str_replace($match[0], $view, $content);
        }

        return $content;
    }

    public function parse_standalone_wiki($content)
    {
        $reg_exercise = '@\[wikilink\](.*)\[\\\/wikilink\]@iU';

        // take all matches
        preg_match_all($reg_exercise, $content, $matches, PREG_SET_ORDER);
        foreach ($matches as $match) {

            if(!isset($this->map['article'][$match[1]])){
                $content = str_replace($match[0], " **Article $match[1] not found** ", $content);
                continue;
            }

            $id   = $this->map['article'][$match[1]]->getId();
            $view = " [Artikel zum Thema](/ref/$id) ";

            // replacing the match with the view
            $content = str_replace($match[0], $view, $content);
        }

        return $content;
    }

    public function parse_wiki($content)
    {
        // regexp string
        $reg_exercise = '@\[wiki\=(.*)\](.*)\[\\\/wiki\]@isU';

        // take all matches
        preg_match_all($reg_exercise, $content, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {

            if (strlen($match[2])) {
                if(!isset($this->map['article'][$match[1]])){
                    $content = str_replace($match[0], " **Article $match[2] ($match[1]) not found** ", $content);
                    continue;
                }
                $id   = $this->map['article'][$match[1]]->getId();
                $view = " [$match[2]](/ref/$id) ";

                // replacing the match with the view
                $content = str_replace($match[0], $view, $content);
            }
        }

        return $content;
    }
}
 