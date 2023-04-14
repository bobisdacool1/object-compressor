<?php

namespace Bobisdaccol1\ObjectCompressor\Models;

class User extends Model
{
    protected bool $isAdmin = false;
    protected bool $isModerator = true;
    protected bool $isEmailConfirmed = false;
    protected bool $isPhoneConfirmed = true;
    protected bool $isAllowedAdultContent = false;
    protected bool $isArmored = true;
    protected bool $hasSmokeGrenade = false;
    protected bool $canFly = true;
    protected bool $isHomo = true;

    protected int $credit = 123456;
    protected int $gender = 0;
    protected int $age = 18;
}