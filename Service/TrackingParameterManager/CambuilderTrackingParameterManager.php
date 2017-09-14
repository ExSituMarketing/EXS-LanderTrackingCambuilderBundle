<?php

namespace EXS\LanderTrackingCambuilderBundle\Service\TrackingParameterManager;

use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use EXS\LanderTrackingHouseBundle\Service\TrackingParameterManager\TrackingParameterExtracterInterface;
use EXS\LanderTrackingHouseBundle\Service\TrackingParameterManager\TrackingParameterFormatterInterface;

/**
 * Class CambuilderTrackingParameterManager
 *
 * @package EXS\LanderTrackingCambuilderBundle\Service\TrackingParameterManager
 */
class CambuilderTrackingParameterManager implements TrackingParameterExtracterInterface, TrackingParameterFormatterInterface
{
    /**
     * {@inheritdoc}
     */
    public function extract(Request $request)
    {
        $trackingParameters = [];

        if (
            (null !== $afno = $request->query->get('AFNO'))
            && (preg_match('`^1-([a-z0-9]+)-([a-z0-9]+)$`i', $afno, $matches))
        ) {
            /** Get 'cmp' and 'exid' from 'AFNO' query parameter. */
            $trackingParameters['cmp'] = $matches[1];
            $trackingParameters['exid'] = $matches[2];
        } elseif (
            ($request->cookies->has('cmp'))
            && ($request->cookies->has('exid'))
        ) {
            $trackingParameters['cmp'] = $request->cookies->get('cmp');
            $trackingParameters['exid'] = $request->cookies->get('exid');
        }

        return $trackingParameters;
    }

    /**
     * {@inheritdoc}
     */
    public function format(ParameterBag $trackingParameters)
    {
        $afno = null;

        if (
            $trackingParameters->has('cmp')
            && $trackingParameters->has('exid')
        ) {
            $afno = sprintf(
                '1-%s-%s',
                $trackingParameters->get('cmp'),
                $trackingParameters->get('exid')
            );
        }

        return [
            'AFNO' => $afno,
        ];
    }
}
