<?php

namespace Lukaswhite\MetaTags\Contracts;

/**
 * Interface Video
 *
 * By implementing this interface, you can pass an instance of a class that includes
 * information about a video, and pass it to the video( ) method.
 *
 * https://developers.facebook.com/docs/sharing/opengraph/object-properties
 *
 * @package Lukaswhite\MetaTags\Contracts
 */
interface Video extends Media
{
    /**
     * Get the image for a high quality preview in News Feed
     *
     * @return string
     */
    public function getImage( );
}