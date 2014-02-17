<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author    Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license   LGPL-3.0
 * @license   http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link      https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2014 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Discussion\Hydrator;

use Common\Hydrator\AbstractKeyHydrator;
use Discussion\Entity\CommentInterface;

class CommentHydrator extends AbstractKeyHydrator
{
    /**
     * @return array
     */
    protected function getKeys()
    {
        return ['object', 'instance', 'author', 'content', 'archived', 'parent', 'title'];
    }

    /**
     * @param mixed $object
     * @return bool
     */
    protected function isValid($object)
    {
        return $object instanceof CommentInterface;
    }
} 