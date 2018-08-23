<?php

namespace Lukaswhite\MetaTags\Entities;

/**
 * Class Geopoint
 *
 * Represents a geopoint.
 *
 * @package Lukaswhite\MetaTags\Entities
 */
class Geopoint implements \Lukaswhite\MetaTags\Contracts\Geopoint
{
    /**
     * The latitude
     *
     * @var float
     */
    protected $latitude;

    /**
     * The longitude
     *
     * @var float
     */
    protected $longitude;

    /**
     * The altitude
     *
     * @var float
     */
    protected $altitude;

    /**
     * Geopoint constructor.
     *
     * @param float $latitude
     * @param float $longitude
     * @param int $altitude
     */
    public function __construct( $latitude = null, $longitude = null, $altitude = null )
    {
        $this->latitude     =   $latitude;
        $this->longitude    =   $longitude;
        $this->altitude     =   $altitude;
    }

    /**
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param float $latitude
     * @return Geopoint
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
        return $this;
    }

    /**
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param float $longitude
     * @return Geopoint
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
        return $this;
    }

    /**
     * @return float
     */
    public function getAltitude()
    {
        return $this->altitude;
    }

    /**
     * @param float $altitude
     * @return Geopoint
     */
    public function setAltitude($altitude)
    {
        $this->altitude = $altitude;
        return $this;
    }


}