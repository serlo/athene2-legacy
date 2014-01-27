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

class PreConverterChain extends ConverterChain
{
    protected $converters = [
        'Migrator\Converter\YoutubeConverter',
        'Migrator\Converter\GeogebraConverter',
        'Migrator\Converter\BrinkmannConverter',
        'Migrator\Converter\LatexConverter',
        'Migrator\Converter\SpoilerConverter',
        'Migrator\Converter\Html2Markdown',
        'Migrator\Converter\TableConverter',
        'Migrator\Converter\UploadConverter'
    ];
}
