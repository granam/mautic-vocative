<?php
namespace MauticPlugin\MauticVocativeBundle\Tests\Service;

use MauticPlugin\MauticVocativeBundle\CzechVocative\CzechName;
use MauticPlugin\MauticVocativeBundle\Service\NameFactory;

class NameFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function I_can_create_name_by_factory()
    {
        $czechName = NameFactory::createCzechName();
        self::assertInstanceOf(CzechName::class, $czechName);
    }
}
