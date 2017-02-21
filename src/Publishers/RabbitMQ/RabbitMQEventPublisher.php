<?php
namespace Evaneos\Events\Publishers\RabbitMQ;

use Evaneos\Events\EventPublisher;
use Evaneos\Events\Event;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;
use Evaneos\Events\Serializer;
use Ramsey\Uuid\Uuid;

class RabbitMQEventPublisher implements EventPublisher
{

    /**
     *
     * @var \PhpAmqpLib\Channel\AMQPChannel
     */
    private $channel;

    /**
     *
     * @var \Evaneos\Events\Serializer
     */
    private $serializer;

    /**
     *
     * @param \PhpAmqpLib\Channel\AMQPChannel $channel
     * @param string $exchange
     * @param \Evaneos\Events\Serializer $serializer
     */
    public function __construct(AMQPChannel $channel, $exchange, Serializer $serializer)
    {
        $this->channel = $channel;
        $this->exchange = $exchange;
        $this->serializer = $serializer;
    }

    /**
     * {@inheritDoc}
     */
    public function publish(Event $event)
    {
        $serializedEvent = $this->serializer->serialize($event);

        $message = new AMQPMessage($serializedEvent, array(
            'delivery_mode' => 2,
            'correlation_id' => Uuid::uuid4()
        ));

        $this->channel->basic_publish($message, $this->exchange, $event->getCategory());
    }
}
