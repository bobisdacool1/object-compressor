<?php

namespace Bobisdaccol1\ObjectCompressor\Utils\Compressor;

use Bobisdaccol1\ObjectCompressor\Interfaces\ArrayableInterface;
use Exception;

class Protocol
{
    /**
     * @var ProtocolItem[]
     */
    private array $protocolItems = [];

    /**
     * @throws Exception
     */
    public function __construct(array|ArrayableInterface $array)
    {
        if ($array instanceof ArrayableInterface) {
            $array = $array->toArray();
        }

        $binaryValuePosition = 1;
        $binaryValueLength = 0;

        foreach ($array as $key => $value) {
            if (is_array($value)) {
                throw new Exception('Nested arrays are not implemented!');
            }
            if (is_string($value)) {
                throw new Exception('Strings are not implemented!');
            }

            $valueType = $this->getValueTypeByValue($value);
            $binaryValuePosition += $binaryValueLength;
            $binaryValueLength = $this->getBinaryValueLengthByType($valueType);

            $this->protocolItems[] = new ProtocolItem($key, $valueType, $binaryValuePosition, $binaryValueLength);
        }
    }

    /**
     * @throws Exception
     */
    public function getProtocolItemByKey(string $key): ProtocolItem
    {
        foreach ($this->protocolItems as $protocolItem) {
            if ($protocolItem->realKey === $key) {
                return $protocolItem;
            }
        }

        throw new Exception('No key found!');
    }

    private function getValueTypeByValue(mixed $value): CompressorVariableTypes
    {
        return match (gettype($value)) {
            'integer' => CompressorVariableTypes::Int,
            default => CompressorVariableTypes::Bool,
        };
    }

    private function getBinaryValueLengthByType(CompressorVariableTypes $type): int
    {
        return match($type) {
            CompressorVariableTypes::Int => 64,
            default => 1,
        };
    }

    /**
     * @return ProtocolItem[]
     */
    public function getItems(): array
    {
        return $this->protocolItems;
    }
}