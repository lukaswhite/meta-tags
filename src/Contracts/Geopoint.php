<?php

namespace Lukaswhite\MetaTags\Contracts;

/**
 * Interface Geopoint
 *
 * By implementing this interface, you can pass an instance of a class that includes
 * a lat/lng and optionally an altitude, when associating a geographical place with a page.
 *
 * @package Lukaswhite\MetaTags\Contracts
 */
interface Geopoint
{
    /**
     * Get the latitude
     *
     * @return float
     */
    public function getLatitude( );

    /**
     * Get the longitude
     *
     * @return float
     */
    public function getLongitude( );

    /**
     * Get the altitude
     *
     * @return int
     */
    public function getAltitude( );
}