<?php

namespace Evaneos\Events;

interface EventProcessor
{
    /**
     * Processes the next available event and submits it to the given event dispatcher
     * @param EventDispatcher $dispatcher
     */
    public function processNext(EventDispatcher $dispatcher);
}
