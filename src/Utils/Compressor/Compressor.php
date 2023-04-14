<?php

namespace Bobisdaccol1\ObjectCompressor\Utils\Compressor;


use Bobisdaccol1\ObjectCompressor\Interfaces\ArrayableInterface;

class Compressor
{
    private CompressorProtocol $protocol;

    public function __construct(CompressorProtocol $protocol)
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

        if ($this->protocol->gzipCompressLevel !== 0) {
            $compressedField = gzcompress($compressedField, $this->protocol->gzipCompressLevel);
        }

        return $compressedField;
    }

    public function uncompress(string $compressed): array
    {
        if ($this->protocol->gzipCompressLevel > 0) {
            $compressed = gzuncompress($compressed);
        }

        $protocolItems = $this->protocol->getItems();
        $outArray = [];
        foreach ($protocolItems as $protocolItem) {
            $realKey = $protocolItem->realKey;
            $binaryValue = substr($compressed, $protocolItem->binaryValuePosition, $protocolItem->binaryValueLength);
            $realValue = $this->getRealByBinary($protocolItem, $binaryValue);

            $outArray[$realKey] = $realValue;
        }

        return $outArray;
    }

    private function getBinaryByReal(mixed $value, ProtocolItem $protocolItem): string
    {
        return match (gettype($value)) {
            'boolean' => (string)(int)$value,
            'integer', 'double' => $this->castToProtocolLength(decbin($value), $protocolItem),
            default => $value,
        };
    }

    private function getRealByBinary(ProtocolItem $protocolItem, string $binaryValue): mixed
    {
        return match ($protocolItem->binaryValueType) {
            CompressorVariableTypes::Bool => (bool)$binaryValue,

            CompressorVariableTypes::Int,
            CompressorVariableTypes::Float => bindec($binaryValue),
            default => null,
        };
    }

    private function castToProtocolLength(string $binaryValue, ProtocolItem $protocolItem): string
    {
        $neededLength = $protocolItem->binaryValueLength;
        $actualLength = strlen($binaryValue);
        $neededZeroesAmount = $neededLength - $actualLength;

        if ($neededZeroesAmount > 0) {
            $binaryValue = str_repeat(0, $neededZeroesAmount) . $binaryValue;
        }
        return $binaryValue;
    }
}
