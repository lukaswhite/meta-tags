<?php

namespace Lukaswhite\MetaTags\Contracts;

/**
 * Interface Profile
 *
 * By implementing this interface, you can pass an instance of a class that includes
 * information about a profile, and pass it to the profile( ) method.
 *
 * https://developers.facebook.com/docs/sharing/opengraph/object-properties
 *
 * @package Lukaswhite\MetaTags\Contracts
 */
interface Profile
{
    /**
     * Constants for the gender property
     */
    const MALE      =   'male';
    const FEMALE    =   'female';

    /**
     * Get the first name
     *
     * @return string
     */
    public function getFirstName( );

    /**
     * Get the last name
     *
     * @return string
     */
    public function getLastName( );

    /**
     * Get the username
     *
     * @return string
     */
    public function getUsername( );

    /**
     * Get the gender
     *
     * @return string
     */
    public function getGender( );

}