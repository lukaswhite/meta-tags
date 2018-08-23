<?php

namespace Lukaswhite\MetaTags\Entities;

/**
 * Class Article
 *
 * Represents an article, satisfying the corresponding contract in order that you can add
 * it to the meta tags via article( ). You're free to use this, or alternatively simply
 * implement the contract in your own class.
 *
 * @package Lukaswhite\MetaTags\Entities
 */
class Article extends WrittenWork implements \Lukaswhite\MetaTags\Contracts\Article
{
    /**
     * The time published
     *
     * @var \DateTime|null
     */
    protected $publishedTime;

    /**
     * The time the article was last modified
     *
     * @var \DateTime
     */
    protected $modifiedTime;

    /**
     * The time the article expires, if applicable
     *
     * @var \DateTime
     */
    protected $expirationTime;

    /**
     * The section
     *
     * @var string
     */
    protected $section;

    /**
     * Get the time published
     *
     * @return \DateTime|null
     */
    public function getPublishedTime( )
    {
        return $this->publishedTime;
    }

    /**
     * Set the time published
     *
     * @param \DateTime $publishedTime
     * @return $this
     */
    public function setPublishedTime( $publishedTime )
    {
        $this->publishedTime = $publishedTime;
        return $this;
    }

    /**
     * Get the time the article was last modified
     *
     * @return \DateTime|null
     */
    public function getModifiedTime( )
    {
        return $this->modifiedTime;
    }

    /**
     * Set the time the article was last modified
     *
     * @param \DateTime|null $modifiedTime
     * @return $this
     */
    public function setModifiedTime( $modifiedTime )
    {
        $this->modifiedTime = $modifiedTime;
        return $this;
    }

    /**
     * Get the time the article expires, if applicable
     *
     * @return \DateTime|null
     */
    public function getExpirationTime( )
    {
        return $this->expirationTime;
    }

    /**
     * Set the time the article expires
     *
     * @param \DateTime $expirationTime
     * @return $this
     */
    public function setExpirationTime( $expirationTime )
    {
        $this->expirationTime = $expirationTime;
        return $this;
    }

    /**
     * Get the section
     *
     * @return string
     */
    public function getSection( )
    {
        return $this->section;
    }

    /**
     * Set the section
     *
     * @param string $section
     * @return $this
     */
    public function setSection( $section )
    {
        $this->section = $section;
        return $this;
    }

}