<?php

namespace Bobisdaccol1\ObjectCompressor\Utils\Compressor;


use Bobisdaccol1\ObjectCompressor\Interfaces\ArrayableInterface;

class Compressor
{
    private Protocol $protocol;

    public function __construct(Protocol $protocol)
    {
        $this->protocol = $protocol;
    }

    public function compress(ArrayableInterface|array $array): string
    {
        if ($array instanceof ArrayableInterface) {
            $array = $array->toArray();
        }

        $compressedField = '1';

        foreach ($array as $key => $value) {
            $protocolItem = $this->protocol->getProtocolItemByKey($key);
            $binaryValue = $this->getBinaryByReal($value, $protocolItem);
            $compressedField .= $binaryValue;
        }

        return $compressedField;
    }

    public function uncompress(string $compressed): array
    {
        $protocolItems = $this->protocol->getItems();
        $outArray = [];

        foreach ($protocolItems as $protocolItem) {
            $realKey = $protocolItem->realKey;
            $binaryValue = substr($compressed, $protocolItem->binaryValuePosition, $protocolItem->binaryValueLength);
            $realValue = $this->getRealByBinary($binaryValue, $protocolItem);

            $outArray[$realKey] = $realValue;
        }

        return $outArray;
    }

    private function getBinaryByReal(mixed $realValue, ProtocolItem $protocolItem): string
    {
        return match ($protocolItem->binaryValueType) {
            CompressorVariableTypes::Bool => (string)(int)$realValue,
            CompressorVariableTypes::Int => $this->castToProtocolLength(decbin($realValue), $protocolItem),
            default => '0',
        };
    }

    private function getRealByBinary(string $binaryValue, ProtocolItem $protocolItem): int|bool|null
    {
        return match ($protocolItem->binaryValueType) {
            CompressorVariableTypes::Bool => (bool)$binaryValue,
            CompressorVariableTypes::Int => bindec($binaryValue),
            default => null,
        };
    }

    private function castToProtocolLength(string $binaryValue, ProtocolItem $protocolItem): string
    {
        $neededLength = $protocolItem->binaryValueLength;
        $actualLength = strlen($binaryValue);
        $neededZeroesQuantity = $neededLength - $actualLength;

        if ($neededZeroesQuantity > 0) {
            $binaryValue = str_repeat(0, $neededZeroesQuantity) . $binaryValue;
        }
        return $binaryValue;
    }
}
