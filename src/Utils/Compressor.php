<?php

namespace Bobisdaccol1\ObjectCompressor\Utils;


use Bobisdaccol1\ObjectCompressor\Interfaces\ArrayableInterface;

/**
 * Изначально я предполагаю, что данные нужны не фронту, а беку, скажем, какому нибудь внутреннему микросервису.
 *
 * Основная концепция - конвертировать объект в Json, сжать его по самые помидоры, отдать клиенту.
 * Когда клиенту будет нужны данные объекта, мы расжимаем его и отдаем красивый массивчик.
 *
 * Можно было бы распарсить и создавать объект, тогда бы в строку пришлось добавить имя класса, чтобы мы знали, какой
 * объект создавать.
 *
 */
class Compressor
{
    public array $keyAliases = [
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
    ];

    public bool $useAliases = true;
    public bool $lossless = false;
    public bool $shouldCompress = true;

    private const DIFFERENCE_BETWEEN_INT_AND_BOOL_SYMBOL = 2;

    /**
     * Помимо стандартной компресси с помощью gzip, я подумал что неплохо было бы поубавить длинну символов в словах true и false.
     * Самое лучшее как по мне решение - скасить их в int. Но тут появляется проблема, что если у нас есть int со значениями
     * 1 || 0, то мы не узнаем, какого типа поле. Поэтому пришлось добавить служебную цифру в начало числа, чтобы избежать такого конфуза.
     */
    public function compressObject(ArrayableInterface $object): string
    {
        $objectArray = $object->toArray();
        $tightObjectArray = [];

        foreach ($objectArray as $key => $value) {
            if ($this->lossless && is_int($value)) {
                $value = (int)(static::DIFFERENCE_BETWEEN_INT_AND_BOOL_SYMBOL . $value);
            }
            if (is_bool($value)) {
                $value = (int)$value;
            }

            if ($this->useAliases) {
                $aliases = $this->getAliases();

                if (array_key_exists($key, $aliases)) {
                    $key = '$' . $aliases[$key];
                }
            }

            $tightObjectArray[$key] = $value;
        }

        $objectJson = json_encode($tightObjectArray);

        if ($this->shouldCompress) {
            return $this->compressString($objectJson);
        }
        return $objectJson;
    }

    public function uncompressObject(string $compressedJsonArray): string
    {
        if ($this->shouldCompress) {
            $tightObjectInJson = $this->uncompressString($compressedJsonArray);
        } else {
            $tightObjectInJson = $compressedJsonArray;
        }

        $tightObjectInArray = json_decode($tightObjectInJson, true);
        $objectArray = [];

        foreach ($tightObjectInArray as $key => $value) {
            if ($value === 0 || $value === 1) {
                $value = (bool)$value;
            }

            if ($this->lossless && is_int($value)) {
                $value = (int)substr($value, 1);
            }

            if ($this->useAliases) {
                $aliases = $this->getAliases();
                $key = strtok($key, '$');

                if (in_array($key, array_values($aliases), true)) {
                    $key = array_search($key, $aliases, true);
                }
            }

            $objectArray[$key] = $value;
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
}