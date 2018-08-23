<?php

namespace Lukaswhite\MetaTags\Entities;

/**
 * Class Book
 *
 * Represents a book, satisfying the corresponding contract in order that you can add
 * it to the meta tags via book( ). You're free to use this, or alternatively simply
 * implement the contract in your own class.
 *
 * @package Lukaswhite\MetaTags\Entities
 */
class Book extends WrittenWork implements \Lukaswhite\MetaTags\Contracts\Book
{
    /**
     * The ISBN
     *
     * @var string
     */
    protected $isbn;

    /**
     * The release date
     *
     * @var \DateTime
     */
    protected $releaseDate;

    /**
     * @return string
     */
    public function getIsbn()
    {
        return $this->isbn;
    }

    /**
     * @param string $isbn
     * @return Book
     */
    public function setIsbn($isbn)
    {
        $this->isbn = $isbn;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getReleaseDate()
    {
        return $this->releaseDate;
    }

    /**
     * @param \DateTime $releaseDate
     * @return Book
     */
    public function setReleaseDate($releaseDate)
    {
        $this->releaseDate = $releaseDate;
        return $this;
    }


}