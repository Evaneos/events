<?php

namespace Evaneos\Events\Tests;

use Evaneos\Events\Event;
use Evaneos\Events\Publishers\MessageQueue\MessageQueueEventPublisher;
use Evaneos\Events\Publishers\ProxyEventPublisher;
use Evaneos\Events\Publishers\SynchronousEventPublisher;


/**
 * Class ProxyEventPublisherTest
 *
 * @package Evaneos\Events\Tests
 **/
class ProxyEventPublisherTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var ProxyEventPublisher
     */
    private $serviceUnderTest;

    /**
     * @var MessageQueueEventPublisher
     */
    private $messageQueueEventPublisher;

    /**
     * @var SynchronousEventPublisher
     */
    private $synchronousEventPublisher;

    /**
     * @var Event
     */
    private $event;

    protected function setUp()
    {
        $this->initMocks();
        $this->initSUT();
    }

    /**
     * @test
     */
    public function it_should_publish_events_on_all_event_publishers()
    {
        $this->givenSynchronousEventIsPublished();
        $this->givenMessageQueueEventIsPublished();

        $this->serviceUnderTest->publish($this->event);
    }

    /**
     * @access private
     */
    private function initMocks()
    {
        $this->synchronousEventPublisher = $this->getMock(
            'Evaneos\Events\Publishers\SynchronousEventPublisher',
            [],
            [
                $this->getMock('\Evaneos\Events\EventDispatcher'),
            ]
        );
        $this->messageQueueEventPublisher = $this->getMock(
            'Evaneos\Events\Publishers\MessageQueue\MessageQueueEventPublisher',
            [],
            [
                $this->getMock('\Burrow\QueuePublisher'),
                $this->getMock('\Evaneos\Events\Serializer'),
            ]
        );
        $this->event = $this->getMock('\Evaneos\Events\Event');
    }

    /**
     * @access private
     */
    private function initSUT()
    {
        $this->serviceUnderTest = new ProxyEventPublisher(
            [$this->synchronousEventPublisher, $this->messageQueueEventPublisher]
        );
    }

    /**
     * @access private
     */
    private function givenSynchronousEventIsPublished()
    {
        $this->synchronousEventPublisher->expects($this->once())
            ->method('publish')
            ->with($this->equalTo($this->event));
    }

    /**
     * @access private
     */
    private function givenMessageQueueEventIsPublished()
    {
        $this->messageQueueEventPublisher->expects($this->once())
            ->method('publish')
            ->with($this->equalTo($this->event));
    }
}