<?php

namespace EXS\LanderTrackingCambuilderBundle\Service\TrackingParameterManager;

use Symfony\Component\HttpFoundation\ParameterBag;
use EXS\LanderTrackingHouseBundle\Service\TrackingParameterManager\TrackingParameterFormatterInterface;
use EXS\LanderTrackingHouseBundle\Service\TrackingParameterManager\TrackingParameterInitializerInterface;
use EXS\LanderTrackingHouseBundle\Service\TrackingParameterManager\TrackingParameterQueryExtracterInterface;

/**
 * Class CambuilderTrackingParameterManager
 *
 * @package EXS\LanderTrackingCambuilderBundle\Service\TrackingParameterManager
 */
class CambuilderTrackingParameterManager implements TrackingParameterQueryExtracterInterface, TrackingParameterFormatterInterface, TrackingParameterInitializerInterface
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
    public function extractFromQuery(ParameterBag $query)
    {
        $trackingParameters = [];

        if (
            (null !== $afno = $query->get('AFNO'))
            && (preg_match('`^1-(?<cmp>[a-z0-9]+)-(?<u>[a-z0-9]+)$`i', $afno, $matches))
        ) {
            /** Get 'cmp' and 'u' from 'AFNO' query parameter. */
            $trackingParameters['cmp'] = $matches['cmp'];
            $trackingParameters['u'] = $matches['u'];
        }

        return $trackingParameters;
    }

    /**
     * {@inheritdoc}
     */
    public function format(ParameterBag $trackingParameters)
    {
        $afno = null;

        if ($trackingParameters->has('u')) {
            $afno = sprintf(
                '1-%s-%s',
                $trackingParameters->get('cmp'),
                $trackingParameters->get('u')
            );
        }

        return [
            'AFNO' => $afno,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function initialize()
    {
        return [
            'cmp' => $this->defaultCmp,
        ];
    }
}
