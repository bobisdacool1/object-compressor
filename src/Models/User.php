<?php

namespace Bobisdaccol1\ObjectCompressor\Models;


use Bobisdaccol1\ObjectCompressor\Traits\Arrayable;
use Bobisdaccol1\ObjectCompressor\Traits\ArrayInstantiatable;
use Throwable;

class User extends Model
{
    private bool $isAdmin = false;
    private bool $isModerator = true;
    private bool $isEmailConfirmed = false;
    private bool $isPhoneConfirmed = false;
    private bool $isAllowedAdultContent = false;
    private bool $isArmored = true;
    private bool $hasSmokeGrenade = false;
    private bool $canFly = true;

    private int $gender = 0;
    private int $age = 18;
    public int $credit = 123456;

}