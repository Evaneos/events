<?php

namespace Evaneos\Events\Tests\Publishers\RabbitMQ;

use Evaneos\Events\Publishers\RabbitMQ\RabbitMQEventPublisher;

class RabbitMQEventDispatcherTest extends \PHPUnit_Framework_TestCase
{

    protected $serializer;

    protected $channel;

    protected function setUp()
    {
        $this->serializer = $this->getMockBuilder('\Evaneos\Events\EventSerializer')
            ->disableOriginalConstructor()
            ->getMock();

        $this->channel =$this->getMockBuilder('\PhpAmqpLib\Channel\AMQPChannel')
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testEventIsPublishedToQueue()
    {
        $event = $this->getMock('\Evaneos\Events\Event');
        $event->expects($this->atLeastOnce())
            ->method('getCategory')
            ->will($this->returnValue('event.category'));

        $this->channel->expects($this->once())
            ->method('basic_publish')
            ->with($this->anything(), $this->equalTo('exchange-name'), $this->equalTo('event.category'));

        $this->serializer->expects($this->any())
            ->method('serialize')
            ->will($this->returnValue('serialized-data'));

        $publisher = new RabbitMQEventPublisher($this->channel, 'exchange-name', $this->serializer);

        $publisher->publish($event);
    }
}
