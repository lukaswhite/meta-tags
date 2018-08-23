<?php

namespace Lukaswhite\MetaTags\Contracts;

/**
 * Interface WrittenWork
 *
 * This is simply the basis of articles and books, which share a couple of properties.
 *
 * @package Lukaswhite\MetaTags\Contracts
 */
interface WrittenWork
{
    /**
     * Get the authors of the work.
     *
     * Note; it's named author for consistency with the spec, but a work can
     * have multiple authors.
     *
     * @return array
     */
    public function getAuthor( );

    /**
     * Get the tags that classify the article.
     *
     * Note; it's named tag for consistency with the spec, but an article can
     * have multiple tags.
     *
     * @return array
     */
    public function getTag( );

}