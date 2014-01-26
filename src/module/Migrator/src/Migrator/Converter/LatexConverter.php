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

class LatexConverter extends AbstractConverter
{
    protected $lastResponse;

    public function convert($content)
    {
        $reg_exercise = '@<img(?:[^>]*)(?=(?:data-mathml="([^"]*)"|alt="([^"]*)")(?:[^>]*)(?:class="Wirisformula")|(?:class="Wirisformula")(?:[^>]*)(?:data-mathml="([^"]*)"|alt="([^"]*)"))(?:[^>]*)>@is';
        preg_match_all($reg_exercise, $content, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $replace = end($match);


            if (strlen($replace)) {
                $replace = str_replace('¨', '"', $replace);
                $replace = str_replace('«', '<', $replace);
                $replace = str_replace('»', '>', $replace);
                $replace = str_replace('§', '&', $replace);
                $replace = str_replace('&nbsp;', ' ', $replace);

                /*$url    = 'http://www.wiris.net/demo/editor/mathml2latex';
                $myvars = 'mml=' . urlencode($replace);

                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $myvars);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);*/

                //$response = " %%" . curl_exec($ch) . "%% ";

                $file = '/tmp/www-data/mml.xml';

                file_put_contents($file, $replace);

                ob_start();
                passthru('xsltproc /var/www/vagrant/xsltml_2.0/mmltex.xsl '.$file);
                $response = ob_get_contents();
                ob_end_clean(); //Use this instead of ob_flush()

                $response = trim($response);
                $response = substr($response, 1);
                $response = substr($response, 0, -1);
                $response = trim($response);
                $response = '%%'.$response.'%%';

                //var_dump($response);

                $this->flag($response);

                $content = str_replace($match[0], $response, $content);
            }
            $replace = '';
        }

        return $content;
    }

    protected function flag($response)
    {
        if (stristr('\color[rgb]', $response)) {
            $this->needsFlagging = true;
        }
    }
}

