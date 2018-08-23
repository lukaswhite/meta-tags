<?php

use Lukaswhite\MetaTags\Tests\TestCase;
use Lukaswhite\MetaTags\MetaTags;


class ImageTest extends TestCase
{

    public function testAddingImage( )
    {
        $meta = new MetaTags( );

        $meta->addImage(
            ( new \Lukaswhite\MetaTags\Entities\Image( ) )
                ->setUrl( 'http://example.com/image.jpeg' )
                ->setSecureUrl( 'https://example.com/image.jpeg' )
                ->setType( 'image/jpeg' )
                ->setWidth( 500 )
                ->setHeight( 300 )
                ->setAlt( 'Example image' )
        );

        $this->assertTrue(
            $this->hasMetaTag(
                $meta->render( ),
                'og:image'
            )
        );

        $this->assertEquals(
            'http://example.com/image.jpeg',
            $this->getContentOfMetaTag(
                $meta->render( ),
                'og:image'
            )
        );

        $this->assertTrue(
            $this->hasMetaTag(
                $meta->render( ),
                'og:image:secure_url'
            )
        );

        $this->assertEquals(
            'https://example.com/image.jpeg',
            $this->getContentOfMetaTag(
                $meta->render( ),
                'og:image:secure_url'
            )
        );

        $this->assertTrue(
            $this->hasMetaTag(
                $meta->render( ),
                'og:image:type'
            )
        );

        $this->assertEquals(
            'image/jpeg',
            $this->getContentOfMetaTag(
                $meta->render( ),
                'og:image:type'
            )
        );

        $this->assertTrue(
            $this->hasMetaTag(
                $meta->render( ),
                'og:image:width'
            )
        );

        $this->assertEquals(
            '500',
            $this->getContentOfMetaTag(
                $meta->render( ),
                'og:image:width'
            )
        );

        $this->assertTrue(
            $this->hasMetaTag(
                $meta->render( ),
                'og:image:height'
            )
        );

        $this->assertEquals(
            '300',
            $this->getContentOfMetaTag(
                $meta->render( ),
                'og:image:height'
            )
        );

        $this->assertTrue(
            $this->hasMetaTag(
                $meta->render( ),
                'og:image:alt'
            )
        );

        $this->assertEquals(
            'Example image',
            $this->getContentOfMetaTag(
                $meta->render( ),
                'og:image:alt'
            )
        );


    }

    public function testAddingMultipleImages( )
    {
        $meta = new MetaTags( );

        $image1 = new \Lukaswhite\MetaTags\Entities\Image( );
        $image1->setUrl( 'http://example.com/image.jpeg' )
            ->setSecureUrl( 'https://example.com/image.jpeg' )
            ->setType( 'image/jpeg' )
            ->setWidth( 500 )
            ->setHeight( 300 )
            ->setAlt( 'Example image' );

        $meta->addImage( $image1 );

        $image2 = new \Lukaswhite\MetaTags\Entities\Image( );
        $image2->setUrl( 'http://example.com/image2.jpeg' )
            ->setSecureUrl( 'https://example.com/image2.jpeg' )
            ->setType( 'image/jpeg' )
            ->setWidth( 600 )
            ->setHeight( 400 )
            ->setAlt( 'Example image 2' );

        $meta->addImage( $image2 );

        $images = $this->getContentOfMultipleMetaTags( $meta->render( ), 'og:image' );

        $this->assertEquals( 2, count( $images ) );
        $this->assertEquals( 'http://example.com/image.jpeg', $images[ 0 ] );
        $this->assertEquals( 'http://example.com/image2.jpeg', $images[ 1 ] );

        $secureUrls = $this->getContentOfMultipleMetaTags( $meta->render( ), 'og:image:secure_url' );
        $this->assertEquals( 2, count( $secureUrls ) );
        $this->assertEquals( 'https://example.com/image.jpeg', $secureUrls[ 0 ] );
        $this->assertEquals( 'https://example.com/image2.jpeg', $secureUrls[ 1 ] );


    }

}