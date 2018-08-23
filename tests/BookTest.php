<?php

use Lukaswhite\MetaTags\Tests\TestCase;
use Lukaswhite\MetaTags\MetaTags;


class BookTest extends TestCase
{

    public function testBookInformation( )
    {
        $meta = new MetaTags( );

        $book = new \Lukaswhite\MetaTags\Entities\Book( );
        $book->setReleaseDate( new \DateTime( '2018-04-03 15:20' ) )
            ->setAuthor( [ 'Joe Bloggs' ] )
            ->setIsbn( 'ISBN-123-1234' )
            ->setTag( [ 'PHP' ] );

        $meta->book( $book );

        $this->assertTrue(
            $this->hasMetaTag(
                $meta->render( ),
                'og:type'
            )
        );

        $this->assertEquals(
            'book',
            $this->getContentOfMetaTag(
                $meta->render( ),
                'og:type'
            )
        );

        $this->assertTrue(
            $this->hasMetaTag(
                $meta->render( ),
                'og:book:release_date'
            )
        );

        $this->assertEquals(
            $book->getReleaseDate( ),
            new \DateTime( $this->getContentOfMetaTag(
                $meta->render( ),
                'og:book:release_date'
            ) )
        );

        $this->assertTrue(
            $this->hasMetaTag(
                $meta->render( ),
                'og:book:isbn'
            )
        );

        $this->assertEquals(
            'ISBN-123-1234',
            $this->getContentOfMetaTag(
                $meta->render( ),
                'og:book:isbn'
            )
        );

        $this->assertTrue(
            $this->hasMetaTag(
                $meta->render( ),
                'og:book:author'
            )
        );

        $this->assertEquals(
            'Joe Bloggs',
            $this->getContentOfMetaTag(
                $meta->render( ),
                'og:book:author'
            )
        );

        $this->assertTrue(
            $this->hasMetaTag(
                $meta->render( ),
                'og:book:tag'
            )
        );

        $this->assertEquals(
            'PHP',
            $this->getContentOfMetaTag(
                $meta->render( ),
                'og:book:tag'
            )
        );

    }

    public function testAddingArticleNoData( )
    {
        $meta = new MetaTags();

        $article = new \Lukaswhite\MetaTags\Entities\Article();

        $meta->addArticle($article);

        $this->assertFalse(
            $this->hasMetaTag(
                $meta->render(),
                'og:article:published_time'
            )
        );

        $this->assertFalse(
            $this->hasMetaTag(
                $meta->render(),
                'og:article:modified_time'
            )
        );

        $this->assertFalse(
            $this->hasMetaTag(
                $meta->render(),
                'og:article:expiration_time'
            )
        );
    }

}