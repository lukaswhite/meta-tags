<?php

namespace Lukaswhite\MetaTags\Contracts;

/**
 * Interface Article
 *
 * By implementing this interface, you can pass an instance of a class that includes
 * information about an article, and pass it to the article( ) method.
 *
 * https://developers.facebook.com/docs/sharing/opengraph/object-properties
 *
 * @package Lukaswhite\MetaTags\Contracts
 */
interface Article extends WrittenWork
{
    /**
     * Get the time published
     *
     * @return \DateTime|null
     */
    public function getPublishedTime( );

    /**
     * Get the time the article was last modified
     *
     * @return \DateTime|null
     */
    public function getModifiedTime( );

    /**
     * Get the time the article expires, if applicable
     *
     * @return \DateTime|null
     */
    public function getExpirationTime( );

    /**
     * Get the section
     *
     * @return string
     */
    public function getSection( );

}