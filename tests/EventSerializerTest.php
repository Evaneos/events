<?php

namespace Evaneos\Events\Tests;

use Evaneos\Events\EventSerializer;

class EventSerializerTest extends \PHPUnit_Framework_TestCase
{

    private $event;

    private $serializer;

    private $eventSerializer;

    private $serializationCallback;

    /**
     * @var \stdClass
     */
    private $deserializedObject;

    protected function setUp()
    {

        $this->deserializedObject = new \stdClass();
        $this->deserializedObject->category = 'test';
        $this->deserializedObject->object = json_encode($this->deserializedObject);

        $this->serializationCallback = function () {
            $obj = new \stdClass();
            $obj->category = 'test';

            return json_encode($obj);
        };

        $this->event = $this->getMock('\Evaneos\Events\Event');
        $this->event->expects($this->any())
            ->method('getCategory')
            ->will($this->returnValue('test'));

        $this->serializer = $this->getMock('\Evaneos\Events\Serializer');

        $this->serializer->expects($this->any())
            ->method('serialize')
            ->will($this->returnCallback($this->serializationCallback));

        $this->serializer->expects($this->any())
            ->method('deserialize')
            ->will($this->returnValue($this->event));

        $this->eventSerializer = new EventSerializer();
        $this->eventSerializer->bindSerializer('test', $this->serializer);
    }

    /**
     * @expectedException \OutOfBoundsException
     */
    public function testGetUnboundCategorySerializerThrowsException()
    {
        $serializer = new EventSerializer();

        $serializer->getSerializer('test');
    }

    public function testGetBoundCategorySerializerReturnsCorrectSerializer()
    {
        $serializer = new EventSerializer();
        $serializer->bindSerializer('test', $this->serializer);

        $this->assertSame($this->serializer, $serializer->getSerializer('test'));
    }

    /**
     * @expectedException \OutOfBoundsException
     */
    public function testSerializingUnboundCategoryThrowsException()
    {
        $serializer = new EventSerializer();

        $serializer->serialize($this->event);
    }


    /**
     * @expectedException \OutOfBoundsException
     */
    public function testDeserializingUnboundCategoryThrowsException()
    {
        $serializer = new EventSerializer();

        $serializer->deserialize($this->serializer->serialize($this->event));
    }

    public function testSerializeReturnsExpectedValue()
    {
        $actual = $this->eventSerializer->serialize($this->event);
        $expected = json_encode($this->deserializedObject);

        $this->assertEquals($expected, $actual);
    }


    public function testDeserializeReturnsExpectedValue()
    {
        $expected = json_encode($this->deserializedObject);

        $actual = $this->eventSerializer->deserialize($expected);

        $this->assertEquals($this->event, $actual);
    }
}
