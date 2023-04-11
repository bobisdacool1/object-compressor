<?php

namespace Bobisdaccol1\ObjectCompressor\Models;


use Bobisdaccol1\ObjectCompressor\Traits\Arrayable;
use Bobisdaccol1\ObjectCompressor\Traits\ArrayInstantiatable;
use Throwable;

class User extends Model
{
    public bool $isAdmin = false;
    public bool $isModerator = true;
    public bool $isEmailConfirmed = false;
    public bool $isPhoneConfirmed = false;
    public bool $isAllowedAdultContent = false;
    public bool $isArmored = true;
    public bool $hasSmokeGrenade = false;
    public bool $canFly = true;

    public int $gender = 0;
    public int $age = 18;
    public int $credit = 123456;

}