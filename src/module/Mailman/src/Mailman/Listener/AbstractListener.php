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
namespace Mailman\Listener;

use Common\Listener\AbstractSharedListenerAggregate;
use Mailman\MailmanAwareTrait;
use Mailman\MailmanInterface;
use Zend\I18n\Translator\Translator;
use Zend\I18n\Translator\TranslatorAwareTrait;
use Zend\View\Renderer\PhpRenderer;

abstract class AbstractListener extends AbstractSharedListenerAggregate
{
    use MailmanAwareTrait;
    use TranslatorAwareTrait;

    /**
     * @var PhpRenderer
     */
    protected $renderer;

    public function __construct(MailmanInterface $mailman, PhpRenderer $phpRenderer, Translator $translator)
    {
        $this->mailman    = $mailman;
        $this->translator = $translator;
        $this->renderer   = $phpRenderer;
    }

    /**
     * @return PhpRenderer $renderer
     */
    public function getRenderer()
    {
        return $this->renderer;
    }
}