<?php
/**
 * 
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author	    Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license	    LGPL-3.0
 * @license	    http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link		https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright	Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Markdown\Service;

use Markdown\Entity\CacheableInterface;
use Common\ObjectManager\Flushable;

interface CacheServiceInterface extends Flushable
{

    /**
     *
     * @param CacheableInterface $object            
     * @return self
     */
    public function getCache(CacheableInterface $object, $field);

    /**
     *
     * @param CacheableInterface $object            
     * @param string $content                   
     * @param string $field            
     * @return \Markdown\Entity\CacheInterface
     */
    public function setCache(CacheableInterface $object, $field, $content);
}