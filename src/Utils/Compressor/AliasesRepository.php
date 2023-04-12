<?php

namespace Bobisdaccol1\ObjectCompressor\Utils\Compressor;

class AliasesRepository
{
    /**
     * @return CompressorAlias[]
     */
    public function fetchAliases(): array
    {
        $keys = [
            'isAdmin',
            'isModerator',
            'isEmailConfirmed',
            'isPhoneConfirmed',
            'isAllowedAdultContent',
            'isArmored',
            'hasSmokeGrenade',
            'canFly',
        ];


        $aliases = [];
        $index = 1;
        foreach ($keys as $key) {
            $aliases[] = new CompressorAlias($key, decbin($index));
            $index++;
        }

        return $aliases;
    }
}