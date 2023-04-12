<?php

namespace Bobisdaccol1\ObjectCompressor\Models;

use Bobisdaccol1\ObjectCompressor\Interfaces\ArrayableInterface;

class Model implements ArrayableInterface
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