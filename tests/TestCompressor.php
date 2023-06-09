<?php
namespace Bobisdaccol1\Tests;

use Bobisdaccol1\ObjectCompressor\Models\Dog;
use Bobisdaccol1\ObjectCompressor\Models\User;
use Bobisdaccol1\ObjectCompressor\Utils\Compressor\Compressor;
use Bobisdaccol1\ObjectCompressor\Utils\Compressor\Protocol;
use PHPUnit\Framework\TestCase;

class TestCompressor extends TestCase
{
    private const MODELS_TO_TEST = [
        Dog::class,
        User::class,
    ];

    public function test_compress_and_uncompress(): void
    {
        foreach (self::MODELS_TO_TEST as $modelClass) {
            $model = new $modelClass();

            $compressProtocol = new Protocol($model);
            $compressor = new Compressor($compressProtocol);

            $compressedModel = $compressor->compress($model);
            $uncompressedModel = $compressor->uncompress($compressedModel);

            $this->assertEquals($model->toArray(), $uncompressedModel);
        }
    }
}
