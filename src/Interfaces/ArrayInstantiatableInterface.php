<?php

namespace Bobisdaccol1\ObjectCompressor\Interfaces;

interface ArrayInstantiatableInterface
{
    public static function fromArray(array $array): static;
}