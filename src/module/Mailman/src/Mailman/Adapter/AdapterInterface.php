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
namespace Mailman\Adapter;

interface AdapterInterface
{
    /**
     * 
     * @param string $to
     * @param string $from
     * @param string $subject
     * @param string $body
     * @return $this
     */
    public function addMail($to, $from, $subject, $body);
    
    /**
     * sends all mail in the queue
     * 
     * @return $this
     */
    public function flush();
}