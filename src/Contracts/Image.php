<?php

namespace Lukaswhite\MetaTags\Contracts;

/**
 * Interface Image
 *
 * By implementing this interface, you can pass an instance of a class that includes
 * information about an image, and pass it to the addImage( ) method.
 *
 * https://developers.facebook.com/docs/sharing/opengraph/object-properties
 *
 * @package Lukaswhite\MetaTags\Contracts
 */
interface Image extends Media
{
    /**
     * Get the alt text
     *
     * @return string
     */
    public function getAlt( );
}