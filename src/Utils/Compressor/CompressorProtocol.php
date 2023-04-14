<?php

namespace Bobisdaccol1\ObjectCompressor\Utils\Compressor;

use Bobisdaccol1\ObjectCompressor\Interfaces\ArrayableInterface;
use Exception;

class CompressorProtocol
{
    /**
     * @var ProtocolItem[]
     */
    private array $protocolItems = [];

    public function __construct(array|ArrayableInterface $array)
    {
        if ($array instanceof ArrayableInterface) {
            $array = $array->toArray();
        }

        $binaryValuePosition = 0;

        foreach ($array as $key => $value) {
            if (is_array($value)) {
                throw new Exception('Nested arrays not implemented!');
            }

            $valueType = $this->getValueTypeByValue($value);
            $binaryValueLength = $this->getBinaryValueLengthByType($valueType);
            $binaryValuePosition += $binaryValueLength;

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
            'string' => CompressorVariableTypes::String,
            'integer' => CompressorVariableTypes::Int,
            'double' => CompressorVariableTypes::Float,
            'boolean' => CompressorVariableTypes::Bool,
            default => CompressorVariableTypes::Null,
        };
    }

    private function getBinaryValueLengthByType(CompressorVariableTypes $type): int
    {
        return match($type) {
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