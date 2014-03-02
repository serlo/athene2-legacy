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
namespace Mailman\Adapter;

use Zend\Mail\Message;
use Zend\Mail\Transport\Smtp;
use Zend\Mail\Transport\SmtpOptions;

class ZendMailAdapter implements AdapterInterface
{
    /**
     * @var SmtpOptions
     */
    protected $smtpOptions;

    private static $instance;

    /**
     * @return \Zend\Mail\Transport\SmtpOptions $smtpOptions
     */
    public function getSmtpOptions()
    {
        return $this->smtpOptions;
    }

    public function __construct(SmtpOptions $smtpOptions)
    {
        if (self::$instance) {
            throw new Exception\RuntimeException('ZendMailAdapter does not allow multiple instances');
        }

        self::$instance = $this;
        $this->smtpOptions = $smtpOptions;
        $this->queue    = [];
    }

    protected $queue;

    /*
     * (non-PHPdoc) @see \Mailman\Adapter\AdapterInterface::addMail()
     */
    public function addMail($to, $from, $subject, $body)
    {
        $message = new Message();
        $message->setBody($body);
        $message->setFrom($from);
        $message->addTo($to);
        $message->setEncoding("UTF-8");
        $message->setSubject($subject);
        $this->queue[] = $message;
    }

    /*
     * (non-PHPdoc) @see \Mailman\Adapter\AdapterInterface::flush()
     */
    public function flush()
    {
        $transport = new Smtp();
        $transport->setOptions($this->getSmtpOptions());
        foreach ($this->queue as $message) {
            $transport->send($message);
        }
    }
}
