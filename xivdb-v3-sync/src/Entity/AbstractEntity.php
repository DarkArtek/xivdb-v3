<?php

namespace App\Entity;

use JMS\Serializer\SerializerBuilder;

/**
 * @package App\Entity
 */
class AbstractEntity
{
    /**
     * Converts the current entity into an array
     *
     * @return array
     */
    public function toArray(): array
    {
        $serializer = SerializerBuilder::create()->build();
        return $serializer->toArray($this);
    }

    /**
     * Converts the current entity into json
     *
     * @return string
     */
    public function toJson(): string
    {
        $serializer = SerializerBuilder::create()->build();
        return $serializer->serialize($this, 'json');
    }

    /**
     * Converts the current class into a std class
     * @return \stdClass
     */
    public function toObject(): \stdClass
    {
        return json_decode($this->toJson());
    }
}
