<?php
namespace Evaneos\Events\QueueConsumers\MessageQueue;

use Evaneos\Events\EventDispatcher;
use Evaneos\Events\Serializer;
use Evaneos;
use Burrow\QueueConsumer;

class MessageQueueConsumer implements QueueConsumer
{
    /**
     * @var EventDispatcher
     */
    private $eventDispatcher;
    
    /**
     *
     * @var Serializer
     */
    private $serializer;
    
    /**
     * Constructor
     *
     * @param EventDispatcher $eventDispatcher
     * @param Serializer $serializer
     */
    public function __construct(EventDispatcher $eventDispatcher, Serializer $serializer)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->serializer = $serializer;
    }
    
    /**
     * (non-PHPdoc)
     * @see \Burrow\QueueConsumer::consume()
     */
    public function consume($message)
    {
        $event = $this->serializer->deserialize($message);
        if ($event !== null && $event instanceof Evaneos\Events\Event) {
            $listened = $this->eventDispatcher->dispatch($event);
            
            if ($listened < 1) {
                throw new \DomainException('At least one listener must listen the event!');
            }
        } else {
            throw new \IllegalArgumentException('Can\'t process non-Event messages !');
        }
    }
}
