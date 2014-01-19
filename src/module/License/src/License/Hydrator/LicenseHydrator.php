<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright   Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace License\Hydrator;

use License\Entity;
use License\Exception;
use Zend\Stdlib\Hydrator\HydratorInterface;

class LicenseHydrator implements HydratorInterface
{

    public function extract($object)
    {
        /* @var $object Entity\LicenseInterface */
        if (!$object instanceof Entity\LicenseInterface) {
            throw new Exception\InvalidArgumentException(sprintf(
                'Expected parameter 1 to be an instance of LicenseInterface but got `%s`',
                get_class($object)
            ));
        }

        return array(
            'title'    => $object->getTitle(),
            'url'      => $object->getUrl(),
            'content'  => $object->getContent(),
            'iconHref' => $object->getIconHref()
        );
    }

    public function hydrate(array $data, $object)
    {
        /* @var $object Entity\LicenseInterface */
        if (!$object instanceof Entity\LicenseInterface) {
            throw new Exception\InvalidArgumentException(sprintf(
                'Expected parameter 1 to be an instance of LicenseInterface but got `%s`',
                get_class($object)
            ));
        }

        $object->setContent($data['content']);
        $object->setTitle($data['title']);
        $object->setUrl($data['url']);
        $object->setIconHref($data['iconHref']);

        return $object;
    }
}