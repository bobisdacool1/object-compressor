<?php

namespace Bobisdaccol1\ObjectCompressor\Traits;

use Throwable;

trait ArrayInstantiatable
{
    public static function fromArray(array $array): static
    {
        $object = new static();

        foreach ($array as $key => $value) {
            try {
                $object->$key = $value;
            } catch (Throwable) {
                continue;
            }
        }

        return $object;
    }
}