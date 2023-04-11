<?php

namespace Bobisdaccol1\ObjectCompressor\Models;

use Bobisdaccol1\ObjectCompressor\Interfaces\ArrayableInterface;
use Bobisdaccol1\ObjectCompressor\Interfaces\ArrayInstantiatableInterface;
use Bobisdaccol1\ObjectCompressor\Traits\Arrayable;
use Bobisdaccol1\ObjectCompressor\Traits\ArrayInstantiatable;

class Model implements ArrayInstantiatableInterface, ArrayableInterface
{
    use Arrayable, ArrayInstantiatable;

}