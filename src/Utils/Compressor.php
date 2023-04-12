<?php

namespace Bobisdaccol1\ObjectCompressor\Utils;


use Bobisdaccol1\ObjectCompressor\Models\User;

class Compressor
{
    private array $keyAliases = [
        'isAdmin' => '1',
        'isModerator' => '10',
        'isEmailConfirmed' => '11',
        'isPhoneConfirmed' => '100',
        'isAllowedAdultContent' => '101',
        'isArmored' => '110',
        'hasSmokeGrenade' => '111',
        'canFly' => '1000',
    ];

    public function compress(User $user): int
    {
        $userFields = $user->toArray();
        $compressedUser = '';

        foreach ($userFields as $key => $field) {
            $key = $this->keyAliases[$key];
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

        $fieldBinaryKey = '';

        for ($i = 0; $i < strlen($binaryFields); $i += $lengthOfKey + $lengthOfValue) {
            $lengthOfKey += $this->shouldIncrement($fieldBinaryKey);

            $fieldBinaryKey = '';
            for ($j = 0; $j < $lengthOfKey; $j++) {
                $fieldBinaryKey .= $binaryFields[$j + $i];
            }
            $binaryValue = $binaryFields[$i + $lengthOfKey];

            $trueKey = array_search($fieldBinaryKey, $this->keyAliases, true);
            $trueFields[$trueKey] = (bool) $binaryValue;
        }
        return $trueFields;
    }

    private function shouldIncrement(string $binaryObjectKey): bool
    {
        return str_replace('1', '', $binaryObjectKey) === "";
    }
}
