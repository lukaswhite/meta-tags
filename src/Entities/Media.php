<?php

namespace Lukaswhite\MetaTags\Entities;

/**
 * Class Media
 *
 * This is simply the basis of the Image and Video classes, which share the same
 * attributes and methods with each other, and with audio items.
 *
 * @package Lukaswhite\MetaTags\Entities
 */
abstract class Media extends Audio implements \Lukaswhite\MetaTags\Contracts\Media
{
    /**
     * The width
     *
     * @var int
     */
    protected $width;

    /**
     * The height
     *
     * @var int
     */
    protected $height;

    /**
     * Get the width
     *
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Set the width
     *
     * @param int $width
     * @return $this
     */
    public function setWidth($width)
    {
        $this->width = $width;
        return $this;
    }

    /**
     * Get the height
     *
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Set the height
     *
     * @param int $height
     * @return $this
     */
    public function setHeight($height)
    {
        $this->height = $height;
        return $this;
    }

}