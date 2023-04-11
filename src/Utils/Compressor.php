<?php

namespace Bobisdaccol1\ObjectCompressor\Utils;


use Bobisdaccol1\ObjectCompressor\Interfaces\ArrayableInterface;

class Compressor
{
    private array $keyAliases = [
        'isAdmin' => 'a',
        'isModerator' => 'm',
        'isEmailConfirmed' => 'ec',
        'isPhoneConfirmed' => 'pc',
        'isAllowedAdultContent' => 'aac',
        'isArmored' => 'ar',
        'hasSmokeGrenade' => 'sg',
        'canFly' => 'cf',
        'gender' => 'g',
        'credit' => 'c',
        '123456' => '12'
    ];

    public bool $useAliases = true;
    public bool $lossless = false;
    public bool $shouldCompress = true;

    private const DIFFERENCE_BETWEEN_INT_AND_BOOL_SYMBOL = 2;
    private const DIFFERENCE_BETWEEN_FIELD_AND_ALIAS = '$';

    public function compressObject(ArrayableInterface $object): string
    {
        $objectArray = $object->toArray();
        $tightObjectArray = [];

        foreach ($objectArray as $key => $field) {
            $field = $this->tightObjectField($field);

            if ($this->useAliases) {
                $key = $this->tryEscapeAliases($key);
                $field = $this->tryEscapeAliases($field);
            }

            $tightObjectArray[$key] = $field;
        }

        $tightObjectJson = json_encode($tightObjectArray);
        $tightObjectJson = $this->escapeServiceSymbolsInJson($tightObjectJson);

        if ($this->shouldCompress) {
            return $this->compressString($tightObjectJson);
        }
        return $tightObjectJson;
    }

    public function uncompressObject(string $compressedJsonArray): string
    {
        if ($this->shouldCompress) {
            $tightObjectJson = $this->uncompressString($compressedJsonArray);
        } else {
            $tightObjectJson = $compressedJsonArray;
        }

        $tightObjectJson = $this->unescapeQuotesInJson($tightObjectJson);

        $tightObjectInArray = json_decode($tightObjectJson, true);
        $objectArray = [];

        foreach ($tightObjectInArray as $key => $field) {
            $field = $this->untightObjectField($field);

            if ($this->useAliases) {
                $field = $this->tryUnescapeAliases($field);
                $key = $this->tryUnescapeAliases($key);
            }

            $objectArray[$key] = $field;
        }

        return json_encode($objectArray);
    }

    private function compressString(string $data): string
    {
        return gzcompress($data, 9);
    }

    private function uncompressString(string $data): string
    {
        return gzuncompress($data);
    }

    protected function getAliases(): array
    {
        return $this->keyAliases;
    }

    private function escapeServiceSymbolsInJson(string $string): string
    {
        return preg_replace('%[{}"]%', '', $string);
    }

    private function unescapeQuotesInJson(string $string): string
    {
        $unescapedString = '"' . $string;
        $unescapedString = preg_replace('%:%', '":', $unescapedString);
        $unescapedString = preg_replace('%,%', '",', $unescapedString);

        $unescapedString = '{' . $unescapedString . '}';

        return $unescapedString;
    }

    private function tightObjectField(mixed $field): string
    {
        if ($this->lossless && is_int($field)) {
            $field = (int)(static::DIFFERENCE_BETWEEN_INT_AND_BOOL_SYMBOL . $field);
        }
        if (is_bool($field)) {
            $field = (int)$field;
        }

        return $field;
    }

    private function tryEscapeAliases(string $value): string
    {
        $aliases = $this->getAliases();

        if (array_key_exists($value, $aliases)) {
            $value = static::DIFFERENCE_BETWEEN_FIELD_AND_ALIAS . $aliases[$value];
        }

        return $value;
    }

    private function tryUnescapeAliases(string $value): string
    {
        $aliases = $this->getAliases();
        $value = strtok($value, static::DIFFERENCE_BETWEEN_FIELD_AND_ALIAS);

        if (in_array($value, array_values($aliases), true)) {
            $value = array_search($value, $aliases, true);
        }

        return $value;
    }

    private function untightObjectField(mixed $field): mixed
    {
        if ($field === 0 || $field === 1) {
            $field = (bool)$field;
        }

        if ($this->lossless && is_int($field)) {
            $field = (int)substr($field, 1);
        }

        return $field;
    }
}