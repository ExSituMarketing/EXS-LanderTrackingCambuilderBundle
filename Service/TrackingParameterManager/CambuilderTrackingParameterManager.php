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

        if ($query->has('AFNO')) {
            $query = $query->get('AFNO');
            $parts = explode('-', $query);
            switch (count($parts)) {
                case 2:
                    $trackingParameters['c'] = $parts[1];
                    break;
                case 3:
                    $trackingParameters['c'] = $parts[1];
                    $trackingParameters['u'] = $parts[2];
                    break;
            }
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
                $trackingParameters->get('c'),
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
            'c' => $this->defaultCmp,
        ];
    }
}
