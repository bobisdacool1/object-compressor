<?php
namespace Bobisdaccol1\Tests;

use Bobisdaccol1\ObjectCompressor\Models\User;
use Bobisdaccol1\ObjectCompressor\Utils\Compressor\Compressor;
use Bobisdaccol1\ObjectCompressor\Utils\Compressor\CompressorProtocol;
use PHPUnit\Framework\TestCase;

class TestCompressor extends TestCase
{
    public function test_compress_and_uncompress(): void
    {
        $user = new User();

        $compressProtocol = new CompressorProtocol($user);
        $compressor = new Compressor($compressProtocol);

        $compressedUser = $compressor->compress($user);
        $uncompressedUser = $compressor->uncompress($compressedUser);

        $this->assertEquals($user->toArray(), $uncompressedUser);
    }
}
