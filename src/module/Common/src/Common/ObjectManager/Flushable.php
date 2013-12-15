<?php
namespace Common\ObjectManager;

interface Flushable
{

    /**
     * Flushes the objectmanager
     *
     * @return self
     */
    public function flush();
}