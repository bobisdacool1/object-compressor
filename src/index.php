<?php

require_once(__DIR__ . '\..\vendor\autoload.php');

use Bobisdaccol1\ObjectCompressor\Models\User;
use Bobisdaccol1\ObjectCompressor\Utils\Compressor\Compressor;

$user = new User();
$compressor = new Compressor();

$compressedUser = $compressor->compress($user);
$uncompressedUser = $compressor->uncompress($compressedUser);

var_dump([
    'compressed user' => $compressedUser,
    'uncompressed user' => $uncompressedUser,
]);
