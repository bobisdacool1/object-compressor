<?php

namespace Bobisdaccol1\ObjectCompressor\Models;

class User extends Model
{
    protected bool $isAdmin = true;
    protected bool $isModerator = true;
    protected bool $isEmailConfirmed = false;
    protected bool $isPhoneConfirmed = false;
    protected bool $isAllowedAdultContent = false;
    protected bool $isArmored = true;
    protected bool $hasSmokeGrenade = false;
    protected bool $canFly = true;

//    public int $gender = 0;
//    public int $age = 18;
//    public int $credit = 123456;
}