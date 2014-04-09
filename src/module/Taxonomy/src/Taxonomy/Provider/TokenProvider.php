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
namespace Taxonomy\Provider;

use Taxonomy\Entity\TaxonomyTermInterface;
use Taxonomy\Exception;
use Token\Provider\AbstractProvider;
use Token\Provider\ProviderInterface;

class TokenProvider extends AbstractProvider implements ProviderInterface
{
    public function getData()
    {
        return [
            'id'    => $this->getObject()->getId()
        ];
    }

    protected function validObject($object)
    {
        if (!$object instanceof TaxonomyTermInterface) {
            throw new Exception\InvalidArgumentException(sprintf(
                'Expected PostInterface but got `%s`',
                get_class($object)
            ));
        }
    }
}
