<?php

namespace Evaneos\Events\Publishers;

use Evaneos\Events\Event;
use Evaneos\Events\EventPublisher;

/**
 * Class ProxyEventPublisher
 *
 * @package Evaneos\Events\Publishers
 **/
class ProxyEventPublisher implements EventPublisher
{

    /**
     * @var EventPublisher[]
     */
    private $eventPublishers;

    /**
     * ProxyPublisher constructor.
     *
     * @param array $eventPublishers
     */
    public function __construct(array $eventPublishers) {

        $this->eventPublishers = $eventPublishers;
    }

    /**
     * @param \Evaneos\Events\Event $event
     */
    public function publish(Event $event)
    {
        foreach ($this->eventPublishers as $eventPublisher) {
            $eventPublisher->publish($event);
        }
    }
}