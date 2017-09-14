<?php

namespace EXS\LanderTrackingCambuilderBundle\Tests\Service\TrackingParameterManager;

use EXS\LanderTrackingCambuilderBundle\Service\TrackingParameterManager\CambuilderTrackingParameterManager;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

class CambuilderTrackingParameterManagerTest extends \PHPUnit_Framework_TestCase
{
    public function testExtractWithoutParametersNorCookies()
    {
        $request = $this->prophesize(Request::class);

        $query = $this->prophesize(ParameterBag::class);
        $query->get('AFNO')->willReturn(null)->shouldBeCalledTimes(1);

        $request->query = $query;

        $cookies = $this->prophesize(ParameterBag::class);
        $cookies->has('cmp')->willReturn(false)->shouldBeCalledTimes(1);

        $request->cookies = $cookies;

        $manager = new CambuilderTrackingParameterManager();

        $result = $manager->extract($request->reveal());

        $this->assertEmpty($result);
    }

    public function testExtractWithoutParametersButCookies()
    {
        $request = $this->prophesize(Request::class);

        $query = $this->prophesize(ParameterBag::class);
        $query->get('AFNO')->willReturn(null)->shouldBeCalledTimes(1);

        $request->query = $query;

        $cookies = $this->prophesize(ParameterBag::class);
        $cookies->has('cmp')->willReturn(true)->shouldBeCalledTimes(1);
        $cookies->get('cmp')->willReturn(123)->shouldBeCalledTimes(1);

        $cookies->has('exid')->willReturn(true)->shouldBeCalledTimes(1);
        $cookies->get('exid')->willReturn('UUID987654321')->shouldBeCalledTimes(1);

        $request->cookies = $cookies;

        $manager = new CambuilderTrackingParameterManager();

        $result = $manager->extract($request->reveal());

        $this->assertCount(2, $result);

        $this->assertArrayHasKey('cmp', $result);
        $this->assertEquals(123, $result['cmp']);

        $this->assertArrayHasKey('exid', $result);
        $this->assertEquals('UUID987654321', $result['exid']);
    }

    public function testExtractWithParameters()
    {
        $request = $this->prophesize(Request::class);

        $query = $this->prophesize(ParameterBag::class);
        $query->get('AFNO')->willReturn('1-123-UUID987654321')->shouldBeCalledTimes(1);

        $request->query = $query;

        $manager = new CambuilderTrackingParameterManager();

        $result = $manager->extract($request->reveal());

        $this->assertCount(2, $result);

        $this->assertArrayHasKey('cmp', $result);
        $this->assertEquals(123, $result['cmp']);

        $this->assertArrayHasKey('exid', $result);
        $this->assertEquals('UUID987654321', $result['exid']);
    }

    public function testFormatWithEmptyArray()
    {
        $trackingParameters = new ParameterBag([]);

        $formatter = new CambuilderTrackingParameterManager();

        $result = $formatter->format($trackingParameters);

        $this->assertCount(1, $result);
        $this->assertArrayHasKey('AFNO', $result);
        $this->assertNull($result['AFNO']);
    }

    public function testFormatWithProperParameters()
    {
        $trackingParameters = new ParameterBag([
            'cmp' => 123,
            'exid' => 'UUID987654321',
        ]);

        $formatter = new CambuilderTrackingParameterManager();

        $result = $formatter->format($trackingParameters);

        $this->assertCount(1, $result);
        $this->assertArrayHasKey('AFNO', $result);
        $this->assertEquals('1-123-UUID987654321', $result['AFNO']);
    }
}
