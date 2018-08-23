<?php

namespace Lukaswhite\MetaTags\Entities;

/**
 * Class Image
 *
 * Represents an image, satisfying the corresponding contract in order that you can add
 * it to the meta tags via addImage( ). You're free to use this, or alternatively simply
 * implement the contract in your own class.
 *
 * @package Lukaswhite\MetaTags\Entities
 */
class Image extends Media implements \Lukaswhite\MetaTags\Contracts\Image
{
    /**
     * The alt text
     *
     * @var string
     */
    protected $alt;

    /**
     * Get the alternative text
     *
     * @return string
     */
    public function getAlt()
    {
        return $this->alt;
    }

    /**
     * Set the alternative text
     *
     * @param string $alt
     * @return $this
     */
    public function setAlt($alt)
    {
        $this->alt = $alt;
        return $this;
    }

}