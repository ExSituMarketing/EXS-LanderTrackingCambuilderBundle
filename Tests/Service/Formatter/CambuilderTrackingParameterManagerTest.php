<?php

namespace EXS\LanderTrackingCambuilderBundle\Tests\Service\TrackingParameterManager;

use EXS\LanderTrackingCambuilderBundle\Service\TrackingParameterManager\CambuilderTrackingParameterManager;
use Symfony\Component\HttpFoundation\ParameterBag;

class CambuilderTrackingParameterManagerTest extends \PHPUnit_Framework_TestCase
{
    public function testExtractFromQuery()
    {
        $query = $this->prophesize(ParameterBag::class);
        $query->get('AFNO')->willReturn('1-123-UUID987654321')->shouldBeCalledTimes(1);

        $manager = new CambuilderTrackingParameterManager(1);

        $result = $manager->extractFromQuery($query->reveal());

        $this->assertCount(2, $result);

        $this->assertArrayHasKey('cmp', $result);
        $this->assertEquals(123, $result['cmp']);

        $this->assertArrayHasKey('u', $result);
        $this->assertEquals('UUID987654321', $result['u']);
    }

    public function testFormatWithEmptyArray()
    {
        $trackingParameters = new ParameterBag([]);

        $manager = new CambuilderTrackingParameterManager(1);

        $result = $manager->format($trackingParameters);

        $this->assertCount(1, $result);
        $this->assertArrayHasKey('AFNO', $result);
        $this->assertNull($result['AFNO']);
    }

    public function testFormatWithProperParameters()
    {
        $trackingParameters = new ParameterBag([
            'cmp' => 123,
            'u' => 'UUID987654321',
        ]);

        $manager = new CambuilderTrackingParameterManager(1);

        $result = $manager->format($trackingParameters);

        $this->assertCount(1, $result);
        $this->assertArrayHasKey('AFNO', $result);
        $this->assertEquals('1-123-UUID987654321', $result['AFNO']);
    }

    public function testInitialize()
    {
        $manager = new CambuilderTrackingParameterManager(1);

        $result = $manager->initialize();

        $this->assertCount(1, $result);
        $this->assertArrayHasKey('cmp', $result);
        $this->assertEquals(1, $result['cmp']);
    }
}
