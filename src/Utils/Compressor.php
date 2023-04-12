<?php

namespace Bobisdaccol1\ObjectCompressor\Utils;


use Bobisdaccol1\ObjectCompressor\Models\User;

class Compressor
{
    private array $keyAliases = [
        [
            'key' => 'isAdmin',
            'alias' => '1',
            'type' => 'boolean'
        ],
        [
            'key' => 'isModerator',
            'alias' => '10',
            'type' => 'boolean'
        ],
        [
            'key' => 'isEmailConfirmed',
            'alias' => '11',
            'type' => 'boolean'
        ],
        [
            'key' => 'isPhoneConfirmed',
            'alias' => '100',
            'type' => 'boolean'
        ],
        [
            'key' => 'isAllowedAdultContent',
            'alias' => '101',
            'type' => 'boolean'
        ],
        [
            'key' => 'isArmored',
            'alias' => '110',
            'type' => 'boolean'
        ],
        [
            'key' => 'hasSmokeGrenade',
            'alias' => '111',
            'type' => 'boolean'
        ],
        [
            'key' => 'canFly',
            'alias' => '1000',
            'type' => 'boolean'
        ],
    ];

    public function compress(User $user): int
    {
        $userFields = $user->toArray();
        $compressedUser = '';

        foreach ($userFields as $key => $field) {
            $key = $this->searchInAliasesByKey($key)['alias'];
            $value = (int)$field;
            $compressedUser .= $key . $value;
        }


        return bindec($compressedUser);
    }

    public function uncompress(int $compressedUser): array
    {
        $binaryFields = decbin($compressedUser);

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

            $fieldInformation = $this->searchInAliasesByAlias($binaryKey);
            $trueKey = $fieldInformation['key'];

            $binaryValue = $binaryFields[$i + $lengthOfKey];

            $trueFields[$trueKey] = (bool)$binaryValue;
        }
        return $trueFields;
    }

    private function shouldIncrement(string $binaryObjectKey): bool
    {
        return str_replace('1', '', $binaryObjectKey) === "";
    }

    private function searchInAliasesByAlias(string $binaryValue): array
    {
        foreach ($this->keyAliases as $alias) {
            if ($alias['alias'] === $binaryValue) {
                return $alias;
            }
        }

        return [];
    }

    private function searchInAliasesByKey(string $binary): array
    {
        foreach ($this->keyAliases as $alias) {
            if ($alias['key'] === $binary) {
                return $alias;
            }
        }

        return [];
    }
}
