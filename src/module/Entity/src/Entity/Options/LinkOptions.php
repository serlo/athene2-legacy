<?php
/**
 * 
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author	Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license	LGPL-3.0
 * @license	http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link		https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Entity\Options;

use Zend\Stdlib\AbstractOptions;
use Entity\Exception;

class LinkOptions extends AbstractOptions implements ComponentOptionsInterface
{

    protected $links = [];

    public function getAllowedTypes($linkType)
    {
        if (! $this->isLinkTypeAllowed($linkType)) {
            throw new Exception\RuntimeException(sprintf('Link type "%s" not found.', $linkType));
        }
        return array_keys($this->links[$linkType]);
    }

    public function isTypeAllowed($linkType, $type)
    {
        return in_array($type, $this->getAllowedTypes($linkType));
    }

    public function allowsMany($linkType, $type)
    {
        if (! $this->isTypeAllowed($linkType, $type)) {
            throw new Exception\RuntimeException(sprintf('Link type "%s" with element type "%s" not allowed.', $linkType, $type));
        }
        
        return array_key_exists('allowsMany', $this->links[$linkType][$type]) ? $this->links[$linkType][$type] : false;
    }

    public function isLinkTypeAllowed($linkType)
    {
        return array_key_exists($linkType, $this->links);
    }

    public function isValid($key)
    {
        return $key == 'links';
    }
    
    public function setLinks($links)
    {
        $this->links = $links;
        return $this;
    }
}