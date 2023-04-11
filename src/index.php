<?php
require_once(__DIR__ . '\..\vendor\autoload.php');

use Bobisdaccol1\ObjectCompressor\Models\User;
use Bobisdaccol1\ObjectCompressor\Utils\Compressor;


$user = new User();
$compressor = new Compressor();

$compressedUserFields = $compressor->compressObject($user);

$uncompressedUserFields = $compressor->uncompressObject($compressedUserFields);
$uncompressedUser = User::fromArray(json_decode($uncompressedUserFields, true));

print_r([
    'length of simple json' => strlen(json_encode($user->toArray())),
    'compressed length' => strlen($compressedUserFields),
]);
var_dump($uncompressedUser);