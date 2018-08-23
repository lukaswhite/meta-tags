<?php

use Lukaswhite\MetaTags\Tests\TestCase;
use Lukaswhite\MetaTags\MetaTags;


class AudioTest extends TestCase
{

    public function testAddingAudio( )
    {
        $meta = new MetaTags( );

        $meta->addAudio(
            ( new \Lukaswhite\MetaTags\Entities\Audio( ) )
                ->setUrl( 'http://example.com/audio.mp3' )
                ->setSecureUrl( 'https://example.com/audio.mp3' )
                ->setType( 'audio/mp3' )
        );

        $this->assertTrue(
            $this->hasMetaTag(
                $meta->render( ),
                'og:audio'
            )
        );

        $this->assertEquals(
            'http://example.com/audio.mp3',
            $this->getContentOfMetaTag(
                $meta->render( ),
                'og:audio'
            )
        );

        $this->assertTrue(
            $this->hasMetaTag(
                $meta->render( ),
                'og:audio:secure_url'
            )
        );

        $this->assertEquals(
            'https://example.com/audio.mp3',
            $this->getContentOfMetaTag(
                $meta->render( ),
                'og:audio:secure_url'
            )
        );

        $this->assertTrue(
            $this->hasMetaTag(
                $meta->render( ),
                'og:audio:type'
            )
        );

        $this->assertEquals(
            'audio/mp3',
            $this->getContentOfMetaTag(
                $meta->render( ),
                'og:audio:type'
            )
        );

    }

}