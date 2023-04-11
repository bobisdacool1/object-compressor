<?php

namespace Bobisdaccol1\ObjectCompressor\Utils;


use Bobisdaccol1\ObjectCompressor\Models\User;

class Compressor
{
    private array $keyAliases = [
        'isAdmin' => '0000',
        'isModerator' => '0001',
        'isEmailConfirmed' => '0010',
        'isPhoneConfirmed' => '0011',
        'isAllowedAdultContent' => '0100',
        'isArmored' => '0101',
        'hasSmokeGrenade' => '0110',
        'canFly' => '0111',
    ];

    public function compress(User $user): int
    {
        $userFields = $user->toArray();
        $compressedUser = '1';

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
        $binaryFields = substr($binaryFields, 1);
        $trueFields = [];

        for ($i = 0, $iMax = strlen($binaryFields); $i < $iMax; $i += 5) {
            $binaryObjectKey = '';
            for ($j = 0; $j < 4; $j++) {
                $binaryObjectKey .= $binaryFields[$j + $i];
            }
            $binaryValue = $binaryFields[$i + 4];

            $trueKey = array_search($binaryObjectKey, $this->keyAliases, true);
            $trueFields[$trueKey] = $binaryValue;
        }

        return $trueFields;
    }
}
