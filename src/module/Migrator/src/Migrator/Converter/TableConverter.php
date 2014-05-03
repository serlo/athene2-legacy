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
        $input = trim($input);

        if (!$input) {
            return '';
        }

        $layout = [];

        $subpattern = [];

        // process <table></table>
        $pattern = "~(.*)(?:<table(?:.*)>)(.*)(?:</table(?:.*)>)~isU";

        preg_match_all($pattern, $input, $tables, PREG_SET_ORDER);

        if (!count($tables)) {
            $layout[][] = [
                'col'     => $this->maxcols,
                'content' => !$input ? ' ' : $input
            ];
            return json_encode($layout);
        }

        $i = 0;
        foreach ($tables as $table) {

            $tableRows = [];

            // remove <table>, </table>, <tbody> </tbody>
            $pattern  = "~(<table[^>]*>)|(<tbody[^>]*>)|(</tbody[^>]*>)|(</table[^>]*>)~isU";
            $table[2] = preg_replace($pattern, '', $table[2]);

            // text vor tabelle
            if (strlen(trim($table[1])) > 0) {
                $layout[][] = [
                    'col'     => $this->maxcols,
                    'content' => $table[1]
                ];
            }

            // <tr></tr> == new row
            $pattern = "~(?:.*)(?:<tr(?:.*)>)(.*)(?:</tr(?:.*)>)~isU";
            preg_match_all($pattern, $table[2], $tableRows, PREG_SET_ORDER);

            $fallback = '';

            foreach ($tableRows as $tableRow) {

                $columns      = [];
                $tableColumns = [];

                // remove <tr>, </tr>
                $pattern     = "~(<tr[^>]*>)|(</tr[^>]*>)~isU";
                $tableRow[0] = preg_replace($pattern, '', $tableRow[0]);

                // <td></td> == new column
                $pattern = "~(?:.*)(?:<td(?:.*)>)(.*)(?:</td(?:.*)>)~isU";
                preg_match_all($pattern, $tableRow[0], $tableColumns, PREG_SET_ORDER);

                $count = count($tableColumns);
                if ($count < 4) {
                    foreach ($tableColumns as $tableColumn) {

                        // remove <td>, </td>
                        $pattern        = "~(<td[^>]*>)|(</td[^>]*>)~isU";
                        $tableColumn[0] = preg_replace($pattern, '', $tableColumn[0]);

                        $columns[] = [
                            'col'     => $this->maxcols / $count,
                            'content' => implode("\n", array_map('trim', explode("\n", $tableColumn[0])))
                        ];
                    }
                } else {
                    $fallback .= PHP_EOL . "<tr>" . $tableRow[0] . '</tr>' . PHP_EOL;
                    $this->needsFlagging = true;
                }

                if (!empty($columns)) {
                    $layout[] = $columns;
                }

                if (strlen($fallback)) {
                    $layout[][] = [
                        'col'     => $this->maxcols,
                        'content' => '<table>' . $fallback . '</table>'
                    ];

                    $fallback = '';
                }
            }
        }

        // process non-tables behind last </table>
        $pos        = strpos($input, $table[0]);
        $garbage    = substr($input, $pos);
        $layout[][] = [
            'col'     => $this->maxcols,
            'content' => str_replace($table[0], '', $garbage)
        ];

        $content = json_encode($layout);
        if (!$content) {
            throw new \Exception(json_last_error());
        }

        return $content;
    }
}
