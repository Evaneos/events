<?php

namespace Evaneos\Events;

use Evaneos;

class EventSerializer implements Serializer
{
    private $serializationMap = array();

    public function bindSerializer($eventCategory, Serializer $serializer)
    {
        $this->serializationMap[$eventCategory] = $serializer;
    }

    public function getSerializer($category)
    {
        if (! array_key_exists($category, $this->serializationMap)) {
            throw new \OutOfBoundsException('Unknown serialization key : ' . $category);
        }

        return $this->serializationMap[$category];
    }

    public function serialize($object)
    {
        if (!($object instanceof Evaneos\Events\Event)) {
            throw new \BadMethodCallException('You can only serialize events!');
        }
        
        $serializer = $this->getSerializer($object->getCategory());

        $serialized = new \stdClass();
        $serialized->category = $object->getCategory();
        $serialized->object = $serializer->serialize($object);
        
        return json_encode($serialized);
    }

    public function deserialize($serializedObject)
    {
        $deserialized = json_decode($serializedObject);

        if (isset($deserialized->category)) {
            $serializer = $this->getSerializer($deserialized->category);
            return $serializer->deserialize($deserialized->object);
        }
        
        return null;
    }
}
