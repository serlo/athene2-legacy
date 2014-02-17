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
namespace Mailman;

class Mailman implements MailmanInterface
{
    use\Common\Traits\ConfigAwareTrait, \Zend\ServiceManager\ServiceLocatorAwareTrait;

    /**
     * @return string $defaultSender
     */
    public function getDefaultSender()
    {
        return $this->getOption('default_sender');
    }

    protected function getDefaultConfig()
    {
        return [
            'adapters'       => [],
            'default_sender' => 'no-reply@serlo.org'
        ];
    }

    protected $adapters = [];

    public function send($to, $from, $subject, $body)
    {
        $this->loadAdapters();
        foreach ($this->adapters as $adapter) {
            /* @var $adapter Adapter\AdapterInterface */
            $adapter->addMail($to, $from, $subject, $body);
        }
        $this->flush();
    }

    public function flush()
    {
        $this->loadAdapters();
        foreach ($this->adapters as $adapter) {
            /* @var $adapter Adapter\AdapterInterface */
            $adapter->flush();
        }
    }

    protected function loadAdapters()
    {
        foreach ($this->getOption('adapters') as $adapter) {
            if (!array_key_exists($adapter, $this->adapters)) {
                $this->adapters[$adapter] = $this->getServiceLocator()->get($adapter);
                if (!$this->adapters[$adapter] instanceof Adapter\AdapterInterface) {
                    throw new Exception\RuntimeException(sprintf(
                        '%s does not implement AdapterInterface',
                        get_class($this->adapters[$adapter])
                    ));
                }
            }
        }
    }
}