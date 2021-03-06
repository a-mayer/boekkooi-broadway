<?php
namespace Boekkooi\Broadway\Serializer\Normalizer;

use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * A normalizer for DateTime and DateTimeImmutable objects.
 */
class DateTimeNormalizer implements NormalizerInterface, DenormalizerInterface
{
    const NORMALIZE_FORMAT = 'Y-m-d\TH:i:s.uP';

    /**
     * @inheritdoc
     */
    public function normalize($object, $format = null, array $context = [])
    {
        return $object->format(static::NORMALIZE_FORMAT);
    }

    /**
     * @inheritdoc
     */
    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof \DateTimeInterface;
    }

    /**
     * @inheritdoc
     */
    public function denormalize($data, $class, $format = null, array $context = [])
    {
        if ($data === null) {
            return null;
        }

        $create = ($class === \DateTime::class ? '\DateTime::createFromFormat' : '\DateTimeImmutable::createFromFormat');

        return call_user_func($create, static::NORMALIZE_FORMAT, $data);
    }

    /**
     * @inheritdoc
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        return
            (\DateTime::class === $type || \DateTimeImmutable::class === $type) &&
            ($data === null || is_string($data))
        ;
    }
}
