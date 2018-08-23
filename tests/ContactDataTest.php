<?php

use Lukaswhite\MetaTags\Tests\TestCase;
use Lukaswhite\MetaTags\MetaTags;


class ContactDataTest extends TestCase
{

    public function testSettingAddressTags( )
    {
        $meta = new MetaTags( );

        $meta->contactData(
            ( new \Lukaswhite\MetaTags\Entities\ContactData( ) )
                ->setStreetAddress( '1601 S California Ave' )
                ->setLocality( 'Palo Alto' )
                ->setRegion( 'CA' )
                ->setPostalCode( '94304' )
                ->setCountryName( 'USA' )
                ->setEmail( 'me@example.com' )
                ->setPhone('650-123-4567')
                ->setFaxNumber('+1-415-123-4567')
                ->setWebsite( 'http://example.com' )
        );

        $this->assertTrue(
            $this->hasMetaTag(
                $meta->render( ),
                'og:street-address'
            )
        );

        $this->assertEquals(
            '1601 S California Ave',
            $this->getContentOfMetaTag(
                $meta->render( ),
                'og:street-address'
            )
        );

        $this->assertTrue(
            $this->hasMetaTag(
                $meta->render( ),
                'og:locality'
            )
        );

        $this->assertEquals(
            'Palo Alto',
            $this->getContentOfMetaTag(
                $meta->render( ),
                'og:locality'
            )
        );

        $this->assertTrue(
            $this->hasMetaTag(
                $meta->render( ),
                'og:region'
            )
        );

        $this->assertEquals(
            'CA',
            $this->getContentOfMetaTag(
                $meta->render( ),
                'og:region'
            )
        );

        $this->assertTrue(
            $this->hasMetaTag(
                $meta->render( ),
                'og:postal-code'
            )
        );

        $this->assertEquals(
            '94304',
            $this->getContentOfMetaTag(
                $meta->render( ),
                'og:postal-code'
            )
        );

        $this->assertTrue(
            $this->hasMetaTag(
                $meta->render( ),
                'og:country-name'
            )
        );

        $this->assertEquals(
            'USA',
            $this->getContentOfMetaTag(
                $meta->render( ),
                'og:country-name'
            )
        );

        $this->assertTrue(
            $this->hasMetaTag(
                $meta->render( ),
                'og:email'
            )
        );

        $this->assertEquals(
            'me@example.com',
            $this->getContentOfMetaTag(
                $meta->render( ),
                'og:email'
            )
        );

        $this->assertTrue(
            $this->hasMetaTag(
                $meta->render( ),
                'og:phone_number'
            )
        );

        $this->assertEquals(
            '650-123-4567',
            $this->getContentOfMetaTag(
                $meta->render( ),
                'og:phone_number'
            )
        );

        $this->assertTrue(
            $this->hasMetaTag(
                $meta->render( ),
                'og:fax_number'
            )
        );

        $this->assertEquals(
            '+1-415-123-4567',
            $this->getContentOfMetaTag(
                $meta->render( ),
                'og:fax_number'
            )
        );

        $this->assertTrue(
            $this->hasMetaTag(
                $meta->render( ),
                'og:website'
            )
        );

        $this->assertEquals(
            'http://example.com',
            $this->getContentOfMetaTag(
                $meta->render( ),
                'og:website'
            )
        );

    }

}