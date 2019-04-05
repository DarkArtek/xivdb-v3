<?php

namespace App\Entity;

use JMS\Serializer\SerializerBuilder;

trait EntityTrait
{
    /**
     * Converts the current entity into an array
     */
    public function toArray(): array
    {
        $serializer = SerializerBuilder::create()->build();
        return $serializer->toArray($this);
    }

    /**
     * Converts the current entity into json
     */
    public function toJson(): string
    {
        $serializer = SerializerBuilder::create()->build();
        return $serializer->serialize($this, 'json');
    }

    /**
     * Converts the current class into a std class
     */
    public function toObject(): \stdClass
    {
        return json_decode($this->toJson());
    }
}
