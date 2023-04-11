<?php
namespace Bobisdaccol1\Tests;

use Bobisdaccol1\ObjectCompressor\Models\User;
use Bobisdaccol1\ObjectCompressor\Utils\Compressor;
use PHPUnit\Framework\TestCase;

class TestCompressor extends TestCase
{
    public function test_compress_and_uncompress(): void
    {
        $user = new User();
        $compressor = new Compressor();

        $compressedUserFields = $compressor->compressObject($user);

        $uncompressedUserFields = $compressor->uncompressObject($compressedUserFields);
        $uncompressedUser = User::fromArray(json_decode($uncompressedUserFields, true));

        $this->assertEquals($user, $uncompressedUser);
    }
}
