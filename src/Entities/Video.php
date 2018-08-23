<?php

namespace Lukaswhite\MetaTags\Entities;

/**
 * Class Video
 *
 * Represents a video, satisfying the corresponding contract in order that you can add
 * it to the meta tags via addVideo( ). You're free to use this, or alternatively simply
 * implement the contract in your own class.
 *
 * @package Lukaswhite\MetaTags\Entities
 */
class Video extends Media implements \Lukaswhite\MetaTags\Contracts\Video
{
    /**
     * The image
     *
     * @var string
     */
    protected $image;

    /**
     * Get the image
     *
     * @return string
     */
    public function getImage( )
    {
        return $this->image;
    }

    /**
     * Set the image
     *
     * @param string $image
     * @return $this
     */
    public function setImage( $image )
    {
        $this->image = $image;
        return $this;
    }

}