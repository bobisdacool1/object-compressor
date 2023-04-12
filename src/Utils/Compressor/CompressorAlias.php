<?php

namespace Bobisdaccol1\ObjectCompressor\Utils\Compressor;

class CompressorAlias
{
    public readonly string $key;
    public readonly string $keyAlias;

    public function __construct(string $key, string $alias)
    {
        $this->key = $key;
        $this->keyAlias = $alias;
    }
}