<?php
/*
 * This file is part of the StfalconApiBundle.
 *
 * (c) Stfalcon LLC <stfalcon.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace StfalconStudio\ApiBundle\Serializer\Normalizer;

use JsonSchema\Validator as JsonSchemaValidator;
use StfalconStudio\ApiBundle\Exception\RuntimeException;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * JsonSchemaErrorNormalizer.
 */
class JsonSchemaErrorNormalizer implements NormalizerInterface
{
    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, string $format = null): bool
    {
        return $data instanceof JsonSchemaValidator;
    }

    /**
     * @param JsonSchemaValidator|mixed $object
     * @param string|null               $format
     * @param array                     $context
     *
     * @return array
     */
    public function normalize($object, string $format = null, array $context = []): array
    {
        if (!$object instanceof JsonSchemaValidator) {
            throw new RuntimeException(sprintf('Object of class %s is not instance of %s', \get_class($object), JsonSchemaValidator::class));
        }

        $data = [];

        foreach ($object->getErrors() as ['constraint' => $constraint, 'property' => $property, 'message' => $message]) {
            $data[$constraint][] = [
                $property => $message,
            ];
        }

        return $data;
    }
}