<?php

require_once(__DIR__ . '\..\vendor\autoload.php');

use Bobisdaccol1\ObjectCompressor\Models\User;
use Bobisdaccol1\ObjectCompressor\Utils\Compressor;


$user = new User();
$compressor = new Compressor();

$compressedUser = $compressor->compress($user);
$uncompressedUser = $compressor->uncompress($compressedUser);




function dd(...$vars)
{
    foreach ($vars as $var) {
        var_dump($var);
    }
    die();
}