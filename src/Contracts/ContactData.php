<?php

namespace Lukaswhite\MetaTags\Contracts;

/**
 * Interface ContactData
 *
 * By implementing this interface, you can pass an instance of a class that includes
 * contact data to the contactData( ) method on the MetaTags class, rather than setting each
 * address component separately.
 *
 * https://developers.facebook.com/docs/sharing/opengraph/object-properties#contactdata
 *
 * @package Lukaswhite\MetaTags\Contracts
 */
interface ContactData
{
    /**
     * Get the street address
     *
     * @return string
     */
    public function getStreetAddress( );

    /**
     * Get the locality
     *
     * @return string
     */
    public function getLocality( );

    /**
     * Get the region
     *
     * @return string
     */
    public function getRegion( );

    /**
     * Get the postal code
     *
     * @return string
     */
    public function getPostalCode( );

    /**
     * Get the country name
     *
     * @return string
     */
    public function getCountryName( );

    /**
     * Get the e-mail address
     *
     * @return string
     */
    public function getEmail( );

    /**
     * Get the phone number
     *
     * @return string
     */
    public function getPhone( );

    /**
     * Get the fax number
     *
     * @return string
     */
    public function getFaxNumber( );

    /**
     * Get the website
     *
     * @return string
     */
    public function getWebsite( );

}