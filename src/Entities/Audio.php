<?php

namespace Lukaswhite\MetaTags\Entities;

/**
 * Class Audio
 *
 * Represents an audio item, satisfying the corresponding contract in order that you can add
 * it to the meta tags via addAudio( ). You're free to use this, or alternatively simply
 * implement the contract in your own class.
 *
 * @package Lukaswhite\MetaTags\Entities
 */
class Audio implements \Lukaswhite\MetaTags\Contracts\Audio
{
    /**
     * The URL
     *
     * @var string
     */
    protected $url;

    /**
     * The secure URL
     *
     * @var string
     */
    protected $secureUrl;

    /**
     * The (mime) type
     *
     * @var string
     */
    protected $type;

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return string
     */
    public function getSecureUrl()
    {
        return $this->secureUrl;
    }

    /**
     * @param string $secureUrl
     * @return $this
     */
    public function setSecureUrl($secureUrl)
    {
        $this->secureUrl = $secureUrl;
        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

}