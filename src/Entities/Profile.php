<?php

namespace Lukaswhite\MetaTags\Entities;

/**
 * Class Profile
 *
 * Represents a profile, satisfying the corresponding contract in order that you can add
 * it to the meta tags via profile( ). You're free to use this, or alternatively simply
 * implement the contract in your own class.
 *
 * @package Lukaswhite\MetaTags\Entities
 */
class Profile implements \Lukaswhite\MetaTags\Contracts\Profile
{
    /**
     * The first name
     *
     * @var string
     */
    protected $firstName;

    /**
     * The last name
     *
     * @var string
     */
    protected $lastName;

    /**
     * The username
     *
     * @var string
     */
    protected $username;

    /**
     * The gender
     *
     * @var string
     */
    protected $gender;

    /**
     * Get the first name
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     * @return Profile
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * Get the last name
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     * @return Profile
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * Get the username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return Profile
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * Get the gender
     *
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param string $gender
     * @return Profile
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
        return $this;
    }

}