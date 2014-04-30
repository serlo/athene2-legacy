<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 */
namespace Mailman\Adapter;

use Mailman\Exception;
use Zend\Mail\Message;
use Zend\Mail\Transport\Smtp;
use Zend\Mail\Transport\SmtpOptions;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;

class ZendMailAdapter implements AdapterInterface
{
    private static $instance;
    /**
     * @var SmtpOptions
     */
    protected $smtpOptions;
    protected $queue;

    public function __construct(SmtpOptions $smtpOptions)
    {
        if (self::$instance) {
            throw new Exception\RuntimeException('ZendMailAdapter does not allow multiple instances');
        }

        self::$instance    = $this;
        $this->smtpOptions = $smtpOptions;
        $this->queue       = [];
    }

    public function addMail($to, $from, $subject, $body)
    {
        $message              = new Message();
        $bodyPart             = new MimeMessage();
        $bodyMessage          = new MimePart($body);
        $bodyMessage->type    = 'text/html';
        $bodyMessage->charset = 'UTF-8';
        $bodyPart->setParts([$bodyMessage]);
        $message->setFrom($from);
        $message->addTo($to);
        $message->setEncoding("UTF-8");
        $message->setSubject($subject);
        $message->setBody($bodyPart);
        $message->type = 'text/html';
        $this->queue[] = $message;
    }

    /*
     * (non-PHPdoc) @see \Mailman\Adapter\AdapterInterface::addMail()
     */

    public function flush()
    {
        $transport = new Smtp();
        $transport->setOptions($this->getSmtpOptions());
        foreach ($this->queue as $message) {
            $transport->send($message);
        }
        $this->queue = [];
    }

    /*
     * (non-PHPdoc) @see \Mailman\Adapter\AdapterInterface::flush()
     */

    /**
     * @return \Zend\Mail\Transport\SmtpOptions $smtpOptions
     */
    public function getSmtpOptions()
    {
        return $this->smtpOptions;
    }
}
