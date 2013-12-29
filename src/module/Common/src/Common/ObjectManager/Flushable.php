<?php
/**
 * 
 * 
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author	    Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license	    LGPL-3.0
 * @license	    http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link		https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright   Copyright (c) 2013 Gesellschaft f√ºr freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Common\ObjectManager;

interface Flushable
{

    /**
     * Persists an object
     *
     * @param object $object            
     * @return self
     */
    public function persist($object);

    /**
     * Flushes the objectmanager
     *
     * @return self
     */
    public function flush();
}