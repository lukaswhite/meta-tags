<?php

namespace Lukaswhite\MetaTags\Contracts;

/**
 * Interface Media
 *
 * By implementing this interface, you can pass an instance of a class that includes
 * information about media (image or video) to the addImage( ) or addVideo( ) methods.
 *
 * https://developers.facebook.com/docs/sharing/opengraph/object-properties
 *
 * @package Lukaswhite\MetaTags\Contracts
 */
interface Media extends Audio
{
    /**
     * Get the width
     *
     * @return integer
     */
    public function getWidth( );

    /**
     * Get the height
     *
     * @return integer
     */
    public function getHeight( );

}