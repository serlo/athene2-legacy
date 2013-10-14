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
namespace Mailman\Listener;

use Zend\EventManager\SharedListenerAggregateInterface;
use Zend\View\Renderer\PhpRenderer;

abstract class AbstractListener implements SharedListenerAggregateInterface
{
    use\Mailman\MailmanAwareTrait;

    /**
     *
     * @var PhpRenderer
     */
    protected $renderer;

    /**
     *
     * @return PhpRenderer $renderer
     */
    public function getRenderer()
    {
        return $this->renderer;
    }

    /**
     *
     * @param PhpRenderer $renderer            
     * @return $this
     */
    public function setRenderer(PhpRenderer $renderer)
    {
        $this->renderer = $renderer;
        return $this;
    }
}