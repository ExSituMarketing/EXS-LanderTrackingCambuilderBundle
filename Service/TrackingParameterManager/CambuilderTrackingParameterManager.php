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
     * @var int
     */
    private $defaultCmp;

    /**
     * AweTrackingParameterManager constructor.
     *
     * @param $defaultCmp
     */
    public function __construct($defaultCmp)
    {
        $this->defaultCmp = $defaultCmp;
    }

    /**
     * {@inheritdoc}
     */
    public function extract(Request $request)
    {
        $trackingParameters = [];

        if (
            (null !== $afno = $request->query->get('AFNO'))
            && (preg_match('`^1-(?<cmp>[a-z0-9]+)-(?<exid>[a-z0-9]+)$`i', $afno, $matches))
        ) {
            /** Get 'cmp' and 'exid' from 'AFNO' query parameter. */
            $trackingParameters['cmp'] = $matches['cmp'];
            $trackingParameters['exid'] = $matches['exid'];
        } elseif ($request->cookies->has('exid')) {
            $trackingParameters['cmp'] = $request->cookies->get('cmp', $this->defaultCmp);
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

        if ($trackingParameters->has('exid')) {
            $afno = sprintf(
                '1-%s-%s',
                $trackingParameters->get('cmp', $this->defaultCmp),
                $trackingParameters->get('exid')
            );
        }

        return [
            'AFNO' => $afno,
        ];
    }
}
