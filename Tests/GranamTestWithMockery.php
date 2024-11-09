<?php

declare(strict_types=1);

namespace MauticPlugin\GranamCzechVocativeBundle\Tests;

use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

class GranamTestWithMockery extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
    }

    protected function mockery(string $className): MockInterface
    {
        return Mockery::mock($className);
    }
}
