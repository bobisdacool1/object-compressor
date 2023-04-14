<?php

require_once(__DIR__ . '\..\vendor\autoload.php');

use Bobisdaccol1\ObjectCompressor\Models\User;
use Bobisdaccol1\ObjectCompressor\Utils\Compressor\Compressor;
use Bobisdaccol1\ObjectCompressor\Utils\Compressor\Protocol;

$user = new User();

$compressProtocol = new Protocol($user);
$compressor = new Compressor($compressProtocol);

$compressedUser = $compressor->compress($user);
$uncompressedUser = $compressor->uncompress($compressedUser);

$compressedUserBits = strlen($compressedUser);
var_dump(['uncompressed user' => $uncompressedUser]);
echo "compressed user contains only $compressedUserBits bits!" . PHP_EOL;
