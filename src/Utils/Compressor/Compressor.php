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

    public function compress(ArrayableInterface|array $array): int
    {
        if ($array instanceof ArrayableInterface) {
            $array = $array->toArray();
        }

        $compressedField = '1';
        foreach ($array as $key => $value) {
            $protocol = $this->protocol->getProtocolItemByKey($key);

            $binaryValue = $this->getBinaryValueByValue($value);
            $binaryValue = str_split($binaryValue);

            foreach ($binaryValue as $index => $binary) {
                $compressedField[$index + $protocol->binaryValuePosition] = $this->getBinaryValueByValue($value);
            }
        }

        return (int)$compressedField;
    }

    public function uncompress(int $compressed): array
    {
        $compressed = (string)$compressed;

        $protocolItems = $this->protocol->getItems();
        $outArray = [];
        foreach ($protocolItems as $protocolItem) {
            $realKey = $protocolItem->realKey;
            $binaryValue = substr($compressed, $protocolItem->binaryValuePosition, $protocolItem->binaryValueLength);
            $realValue = $this->getRealValue($protocolItem, $binaryValue);

            $outArray[$realKey] = $realValue;
        }

        return $outArray;
    }

    private function getBinaryValueByValue(mixed $value): string
    {
        return match (gettype($value)) {
            'boolean' => (string)(int)$value,
            'integer', 'double' => (string)decbin($value),
            default => $value,
        };
    }

    private function getRealValue(ProtocolItem $protocolItem, string $binaryValue): mixed
    {
        return match ($protocolItem->binaryValueType) {
            CompressorVariableTypes::Bool => (bool) $binaryValue,
            default => null,
        };
    }
}
