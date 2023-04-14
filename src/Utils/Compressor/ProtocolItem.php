<?php

namespace Bobisdaccol1\ObjectCompressor\Utils\Compressor;

use Bobisdaccol1\ObjectCompressor\Interfaces\ArrayableInterface;
use Exception;

class ProtocolItem
{
    public readonly string $realKey;

    public readonly int $binaryValuePosition;
    public readonly CompressorVariableTypes $binaryValueType;
    public readonly int $binaryValueLength;

    /**
     * @throws Exception
     */
    public function __construct(string $key, CompressorVariableTypes $binaryValueType, int $binaryValuePosition, int $binaryValueLength)
    {
        $this->realKey = $key;
        $this->binaryValuePosition = $binaryValuePosition;

        $this->binaryValueType = $binaryValueType;
        $this->binaryValueLength = $binaryValueLength;
    }
}