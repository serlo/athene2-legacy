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

class TableConverter extends AbstractConverter
{
    protected $maxcols = 24;

    public function convert($input)
    {
        if (!stristr($input, '<table')) {
            return $input;
        }

        $layout = array();

        $subpattern = array();

        // process <table></table>
        $pattern = "~(.*)(?:<table(?:.*)>)(.*)(?:</table(?:.*)>)~isU";

        preg_match_all($pattern, $input, $tables, PREG_SET_ORDER);

        $i = 0;
        foreach ($tables as $table) {

            $tableRows = array();

            // remove <table>, </table>, <tbody> </tbody>
            $pattern  = "~(<table[^>]*>)|(<tbody[^>]*>)|(</tbody[^>]*>)|(</table[^>]*>)~isU";
            $table[2] = preg_replace($pattern, '', $table[2]);

            // text vor tabelle
            if (strlen(trim($table[1])) > 0) {
                $layout[][] = array(
                    'col'     => $this->maxcols,
                    'content' => $table[1]
                );
            }

            // <tr></tr> == new row
            $pattern = "~(?:.*)(?:<tr(?:.*)>)(.*)(?:</tr(?:.*)>)~isU";
            preg_match_all($pattern, $table[2], $tableRows, PREG_SET_ORDER);

            foreach ($tableRows as $tableRow) {
                $tableColumns = array();

                // remove <tr>, </tr>
                $pattern     = "~(<tr[^>]*>)|(</tr[^>]*>)~isU";
                $tableRow[0] = preg_replace($pattern, '', $tableRow[0]);

                // <td></td> == new column
                $pattern = "~(?:.*)(?:<td(?:.*)>)(.*)(?:</td(?:.*)>)~isU";
                preg_match_all($pattern, $tableRow[0], $tableColumns, PREG_SET_ORDER);

                $columns = array();

                $count = count($tableColumns);
                if ($count < 4) {
                    foreach ($tableColumns as $tableColumn) {

                        // remove <td>, </td>
                        $pattern        = "~(<td[^>]*>)|(</td[^>]*>)~isU";
                        $tableColumn[0] = preg_replace($pattern, '', $tableColumn[0]);

                        $columns[] = array(
                            'col'     => $this->maxcols / $count,
                            'content' => $tableColumn[0]
                        );
                    }
                } else {
                    $columns[] = array(
                        'col'     => $this->maxcols,
                        'content' => '<table class="table"><tr>' . $tableRow[0] . '</tr></table>'
                    );
                    $this->needsFlagging = true;
                }

                $layout[] = $columns;
            }
            $lastTable = $table;
        }

        // process non-tables behind last </table>
        $pos                   = strpos($input, $table[0]);
        $garbage               = substr($input, $pos);
        $layout[][] = array(
            'col'     => $this->maxcols,
            'content' => str_replace($table[0], '', $garbage)
        );

        $content = json_encode($layout);
        if(!$content){
            throw new \Exception( json_last_error());
        }

        return $content;
    }
}
