<?php

namespace Lukaswhite\MetaTags\Entities;

/**
 * Class WrittenWork
 *
 * This is simply the basis of articles and books, which share a couple of properties and
 * methods.
 *
 * @package Lukaswhite\MetaTags\Entities
 */
class WrittenWork implements \Lukaswhite\MetaTags\Contracts\WrittenWork
{
    /**
     * The author(s)
     *
     * @var array
     */
    protected $author;

    /**
     * The tag(s)
     *
     * @var array
     */
    protected $tag;

    /**
     * Get the authors of the work.
     *
     * Note; it's named author for consistency with the spec, but an article can
     * have multiple authors.
     *
     * @return array
     */
    public function getAuthor( )
    {
        return $this->author;
    }

    /**
     * Set the authors of the article.
     *
     * Note; it's named author for consistency with the spec, but a work can
     * have multiple authors.
     *
     * @param array|string $author
     * @return $this
     */
    public function setAuthor( $author )
    {
        if ( is_array( $author ) ) {
            $this->author = $author;
        } else {
            $this->author[ ] = $author;
        }

        return $this;
    }

    /**
     * Get the tags that classify the work.
     *
     * Note; it's named tag for consistency with the spec, but an article can
     * have multiple tags.
     *
     * @return array
     */
    public function getTag( )
    {
        return $this->tag;
    }

    /**
     * Set the tags that classify the article.
     *
     * Note; it's named tag for consistency with the spec, but an article can
     * have multiple tags.
     *
     * @param array|string $tag
     * @return $this
     */
    public function setTag( $tag )
    {
        if ( is_array( $tag ) ) {
            $this->tag = $tag;
        } else {
            $this->tag[ ] = $tag;
        }
        return $this;
    }

}