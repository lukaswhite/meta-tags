<?php

use Lukaswhite\MetaTags\Tests\TestCase;
use Lukaswhite\MetaTags\MetaTags;

class VideoTest extends TestCase
{

    public function testAddingVideo( )
    {
        $meta = new MetaTags( );

        $meta->addVideo(
            ( new \Lukaswhite\MetaTags\Entities\Video( ) )
                ->setUrl( 'http://example.com/movie.swf' )
                ->setSecureUrl( 'https://example.com/movie.swf' )
                ->setType( 'application/x-shockwave-flash' )
                ->setWidth( 500 )
                ->setHeight( 300 )
                ->setImage( 'http://example.com/movie-image.jpg' )
        );

        $this->assertTrue(
            $this->hasMetaTag(
                $meta->render( ),
                'og:video'
            )
        );

        $this->assertEquals(
            'http://example.com/movie.swf',
            $this->getContentOfMetaTag(
                $meta->render( ),
                'og:video'
            )
        );

        $this->assertTrue(
            $this->hasMetaTag(
                $meta->render( ),
                'og:video:secure_url'
            )
        );

        $this->assertEquals(
            'https://example.com/movie.swf',
            $this->getContentOfMetaTag(
                $meta->render( ),
                'og:video:secure_url'
            )
        );

        $this->assertTrue(
            $this->hasMetaTag(
                $meta->render( ),
                'og:video:type'
            )
        );

        $this->assertEquals(
            'application/x-shockwave-flash',
            $this->getContentOfMetaTag(
                $meta->render( ),
                'og:video:type'
            )
        );

        $this->assertTrue(
            $this->hasMetaTag(
                $meta->render( ),
                'og:video:width'
            )
        );

        $this->assertEquals(
            '500',
            $this->getContentOfMetaTag(
                $meta->render( ),
                'og:video:width'
            )
        );

        $this->assertTrue(
            $this->hasMetaTag(
                $meta->render( ),
                'og:video:height'
            )
        );

        $this->assertEquals(
            '300',
            $this->getContentOfMetaTag(
                $meta->render( ),
                'og:video:height'
            )
        );

        $this->assertTrue(
            $this->hasMetaTag(
                $meta->render( ),
                'og:video:image'
            )
        );

        $this->assertEquals(
            'http://example.com/movie-image.jpg',
            $this->getContentOfMetaTag(
                $meta->render( ),
                'og:video:image'
            )
        );

    }

}