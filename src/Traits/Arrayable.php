<?php

namespace Bobisdaccol1\ObjectCompressor\Traits;

trait Arrayable
{
    public function toArray(): array
    {
        $objectInArray = [];
        foreach ($this as $key => $value) {
            $objectInArray[$key] = $value;
        }
        return $objectInArray;
    }
}