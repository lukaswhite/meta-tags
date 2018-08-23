<?php

namespace Lukaswhite\MetaTags\Entities;

/**
 * Class ContactData
 *
 * Represents contact data, satisfying the corresponding contract in order that you can add
 * it to the meta tags via contactData( ). You're free to use this, or alternatively simply
 * implement the contract in your own class.
 *
 * @package Lukaswhite\MetaTags\Entities
 */
class ContactData implements \Lukaswhite\MetaTags\Contracts\ContactData
{
    /**
     * The street address
     *
     * @var string
     */
    protected $streetAddress;

    /**
     * The locality
     *
     * @var string
     */
    protected $locality;

    /**
     * The region
     *
     * @var string
     */
    protected $region;

    /**
     * The postal code
     *
     * @var string
     */
    protected $postalCode;

    /**
     * The name of the country
     *
     * @var string
     */
    protected $countryName;

    /**
     * The email address
     *
     * @var string
     */
    protected $email;

    /**
     * The phone number
     *
     * @var string
     */
    protected $phone;

    /**
     * The fax number
     *
     * @var string
     */
    protected $faxNumber;

    /**
     * The website
     *
     * @var string
     */
    protected $website;

    /**
     * @return string
     */
    public function getStreetAddress()
    {
        return $this->streetAddress;
    }

    /**
     * @param string $streetAddress
     * @return $this
     */
    public function setStreetAddress($streetAddress)
    {
        $this->streetAddress = $streetAddress;
        return $this;
    }

    /**
     * @return string
     */
    public function getLocality()
    {
        return $this->locality;
    }

    /**
     * @param string $locality
     * @return $this
     */
    public function setLocality($locality)
    {
        $this->locality = $locality;
        return $this;
    }

    /**
     * @return string
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @param string $region
     * @return $this
     */
    public function setRegion($region)
    {
        $this->region = $region;
        return $this;
    }

    /**
     * @return string
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * @param string $postalCode
     * @return $this
     */
    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getCountryName()
    {
        return $this->countryName;
    }

    /**
     * @param string $countryName
     * @return $this
     */
    public function setCountryName($countryName)
    {
        $this->countryName = $countryName;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     * @return $this
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * @return string
     */
    public function getFaxNumber()
    {
        return $this->faxNumber;
    }

    /**
     * @param string $faxNumber
     * @return $this
     */
    public function setFaxNumber($faxNumber)
    {
        $this->faxNumber = $faxNumber;
        return $this;
    }

    /**
     * @return string
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * @param string $website
     * @return $this
     */
    public function setWebsite($website)
    {
        $this->website = $website;
        return $this;
    }

}