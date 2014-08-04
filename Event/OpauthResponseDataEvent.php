<?php

namespace DCS\OpauthFOSUBBundle\Event;

class OpauthResponseDataEvent extends AbstractResponseEvent
{
    /**
     * @var array
     */
    private $opauthResponseData;

    function __construct(array $opauthResponseData)
    {
        $this->opauthResponseData = $opauthResponseData;
    }

    /**
     * Get uid
     *
     * @return string
     */
    public function getUid()
    {
        return $this->opauthResponseData['auth']['uid'];
    }

    /**
     * Get provider name
     *
     * @return string
     */
    public function getProvider()
    {
        return $this->opauthResponseData['auth']['provider'];
    }

    /**
     * Get opauthResponseData
     *
     * @return array
     */
    public function getOpauthResponseData()
    {
        return $this->opauthResponseData;
    }
}