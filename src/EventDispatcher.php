<?php
namespace Evaneos\Events;

interface EventDispatcher
{
    /**
     * Register a listener
     *
     * @param string $category
     * @param EventSubscriber $subscriber
     */
    public function addListener($category, EventSubscriber $subscriber);

    /**
     * Dispatch an event to the registered listeners
     *
     * @param Event $event
     *
     * @return int the number of listener called by the dispatch of the event
     */
    public function dispatch(Event $event);
}
