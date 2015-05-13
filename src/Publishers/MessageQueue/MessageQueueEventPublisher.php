<?php
namespace Evaneos\Events\Publishers\MessageQueue;

use Evaneos\Events\EventPublisher;
use Evaneos\Events\Event;
use Evaneos\Events\Serializer;
use Burrow\QueuePublisher;

class MessageQueueEventPublisher implements EventPublisher
{

    /**
     * @var QueuePublisher
     */
    private $queuePublisher;
    
    /**
     * @var Serializer
     */
    private $serializer;
    
    /**
     * Constructor
     *
     * @param QueuePublisher $queuePublisher
     */
    public function __construct(QueuePublisher $queuePublisher, Serializer $serializer)
    {
        $this->queuePublisher = $queuePublisher;
        $this->serializer = $serializer;
    }

    /**
     * {@inheritDoc}
     */
    public function publish(Event $event)
    {
        $serializedEvent = $this->serializer->serialize($event);
        $this->queuePublisher->publish($serializedEvent, $event->getCategory());
    }
}
