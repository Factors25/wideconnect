<?php

namespace App\Service\Serializer;

use ArrayObject;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface;

readonly class SerializerService
{
    public function __construct(private SerializerInterface $serializer)
    {
    }

    /**
     * @throws ExceptionInterface
     */
    public function normalize(mixed $object, array|string $groups): float|int|ArrayObject|bool|array|string|null
    {
        if (is_array($groups)) {
            return $this->serializer->normalize($object, null, $groups);
        }
        return $this->serializer->normalize($object, null, [$groups]);
    }

    public function serialize(mixed $object, array|string $groups): string
    {
        if (is_array($groups)) {
            return $this->serializer->serialize($object, null, $groups);
        }
        return $this->serializer->serialize($object, null, [$groups]);
    }
}
