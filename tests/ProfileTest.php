<?php

use Lukaswhite\MetaTags\Tests\TestCase;
use Lukaswhite\MetaTags\MetaTags;


class ProfileTest extends TestCase
{

    public function testAddingProfileInformation( )
    {
        $meta = new MetaTags( );

        $meta->profile( ( new \Lukaswhite\MetaTags\Entities\Profile( ) )
            ->setFirstName( 'Joe' )
            ->setLastName( 'Bloggs' )
            ->setUsername( 'joebloggs' )
            ->setGender( \Lukaswhite\MetaTags\Contracts\Profile::MALE ) );

        $this->assertTrue(
            $this->hasMetaTag(
                $meta->render( ),
                'og:type'
            )
        );

        $this->assertEquals(
            'profile',
            $this->getContentOfMetaTag(
                $meta->render( ),
                'og:type'
            )
        );

        $this->assertTrue(
            $this->hasMetaTag(
                $meta->render( ),
                'og:profile:first_name'
            )
        );

        $this->assertEquals(
            'Joe',
            $this->getContentOfMetaTag(
                $meta->render( ),
                'og:profile:first_name'
            )
        );

        $this->assertTrue(
            $this->hasMetaTag(
                $meta->render( ),
                'og:profile:last_name'
            )
        );

        $this->assertEquals(
            'Bloggs',
            $this->getContentOfMetaTag(
                $meta->render( ),
                'og:profile:last_name'
            )
        );

        $this->assertTrue(
            $this->hasMetaTag(
                $meta->render( ),
                'og:profile:username'
            )
        );

        $this->assertEquals(
            'joebloggs',
            $this->getContentOfMetaTag(
                $meta->render( ),
                'og:profile:username'
            )
        );

        $this->assertTrue(
            $this->hasMetaTag(
                $meta->render( ),
                'og:profile:gender'
            )
        );

        $this->assertEquals(
            'male',
            $this->getContentOfMetaTag(
                $meta->render( ),
                'og:profile:gender'
            )
        );
    }

}