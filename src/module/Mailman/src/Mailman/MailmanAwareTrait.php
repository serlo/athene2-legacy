<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 */
namespace Mailman;

trait MailmanAwareTrait
{

    /**
     * @var MailmanInterface
     */
    protected $mailman;

    /**
     * @return MailmanInterface $mailman
     */
    public function getMailman()
    {
        return $this->mailman;
    }

    /**
     * @param MailmanInterface $mailman
     * @return self
     */
    public function setMailman(MailmanInterface $mailman)
    {
        $this->mailman = $mailman;
    }
}