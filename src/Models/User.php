<?php

namespace Bobisdaccol1\ObjectCompressor\Models;


use Bobisdaccol1\ObjectCompressor\Interfaces\ArrayableInterface;
use Bobisdaccol1\ObjectCompressor\Interfaces\ArrayInstantiatableInterface;
use Throwable;

class User implements ArrayInstantiatableInterface, ArrayableInterface
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


    public function toArray(): array
    {
        $objectInArray = [];
        foreach ($this as $key => $value) {
            $objectInArray[$key] = $value;
        }
        return $objectInArray;
    }

    public static function fromArray(array $array): static
    {
        $object = new static();

        foreach ($array as $key => $value) {
            try {
                $object->$key = $value;
            } catch (Throwable) {
                continue;
            }
        }

        return $object;
    }
}