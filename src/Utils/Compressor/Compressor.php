<?php

namespace Bobisdaccol1\ObjectCompressor\Utils\Compressor;


use Bobisdaccol1\ObjectCompressor\Interfaces\ArrayableInterface;

class Compressor
{
    private array $keyAliases;

    public function __construct()
    {
        $aliasesFetcher = new AliasesRepository();
        $this->keyAliases = $aliasesFetcher->fetchAliases();
    }

    public function compress(ArrayableInterface $arrayableObject): int
    {
        $compressedObject = '';
        $fields = $arrayableObject->toArray();

        foreach ($fields as $key => $field) {
            $key = $this->searchInAliasesByKey($key)->keyAlias;
            $value = (int)$field;
            $compressedObject .= $key . $value;
        }


        return bindec($compressedObject);
    }

    public function uncompress(int $compressedObject): array
    {
        $binaryFields = decbin($compressedObject);

        $trueFields = [];

        $lengthOfKey = 0;
        $lengthOfValue = 1;

        $binaryKey = '';

        for ($i = 0; $i < strlen($binaryFields); $i += $lengthOfKey + $lengthOfValue) {
            $lengthOfKey += $this->shouldIncrement($binaryKey);

            $binaryKey = '';
            for ($j = 0; $j < $lengthOfKey; $j++) {
                $binaryKey .= $binaryFields[$j + $i];
            }
            $realKey = $this->searchInAliasesByAlias($binaryKey)->key;

            $binaryValue = $binaryFields[$i + $lengthOfKey];
            $realValue = (bool)$binaryValue;

            $trueFields[$realKey] = $realValue;
        }
        return $trueFields;
    }

    private function shouldIncrement(string $binaryObjectKey): bool
    {
        return str_replace('1', '', $binaryObjectKey) === "";
    }

    private function searchInAliasesByAlias(string $binaryKey): ?CompressorAlias
    {
        foreach ($this->keyAliases as $alias) {
            if ($alias->keyAlias === $binaryKey) {
                return $alias;
            }
        }

        return null;
    }

    private function searchInAliasesByKey(string $key): ?CompressorAlias
    {
        foreach ($this->keyAliases as $alias) {
            if ($alias->key === $key) {
                return $alias;
            }
        }

        return null;
    }
}
