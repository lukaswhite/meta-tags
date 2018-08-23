<?php

namespace Lukaswhite\MetaTags\Contracts;

/**
 * Interface Audio
 *
 * By implementing this interface, you can pass an instance of a class that includes
 * information about audio to the addAudio( ) method.
 *
 * https://developers.facebook.com/docs/sharing/opengraph/object-properties
 *
 * @package Lukaswhite\MetaTags\Contracts
 */
interface Audio
{
    /**
     * Get the URL
     *
     * @return string
     */
    public function getUrl( );

    /**
     * Get the secure URL
     *
     * @return string
     */
    public function getSecureUrl( );

    /**
     * Get the (mime) type
     *
     * @return string
     */
    public function getType( );

}