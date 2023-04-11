<?php

namespace Bobisdaccol1\ObjectCompressor\Models;

class User
{
    public bool $isAdmin = true;
    public bool $isModerator = true;
    public bool $isEmailConfirmed = false;
    public bool $isPhoneConfirmed = false;
    public bool $isAllowedAdultContent = false;
    public bool $isArmored = true;
    public bool $hasSmokeGrenade = false;
    public bool $canFly = true;

//    public int $gender = 0;
//    public int $age = 18;
//    public int $credit = 123456;

    public function toArray(): array
    {
        $objectInArray = [];
        foreach ($this as $key => $value) {
            $objectInArray[$key] = $value;
        }
        return $objectInArray;
    }
}