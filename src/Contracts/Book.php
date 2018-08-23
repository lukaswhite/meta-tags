<?php

namespace Lukaswhite\MetaTags\Contracts;

/**
 * Interface Book
 *
 * By implementing this interface, you can pass an instance of a class that includes
 * information about a book, and pass it to the book( ) method.
 *
 * https://developers.facebook.com/docs/sharing/opengraph/object-properties
 *
 * @package Lukaswhite\MetaTags\Contracts
 */
interface Book extends WrittenWork
{
    /**
     * Get the ISBN
     *
     * @return string
     */
    public function getIsbn( );

    /**
     * Get the release date
     *
     * @return \DateTime
     */
    public function getReleaseDate( );

}