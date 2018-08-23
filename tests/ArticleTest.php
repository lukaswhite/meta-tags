<?php

use Lukaswhite\MetaTags\Tests\TestCase;
use Lukaswhite\MetaTags\MetaTags;


class ArticleTest extends TestCase
{

    public function testAddingArticle( )
    {
        $meta = new MetaTags( );

        $article = new \Lukaswhite\MetaTags\Entities\Article( );
        $article->setPublishedTime( new \DateTime( '2018-10-03 15:20' ) )
            ->setModifiedTime( new \DateTime( '2018-10-12 17:32' ) )
            ->setExpirationTime( new \DateTime( '2018-12-21' ) )
            ->setAuthor( 'Joe Bloggs' )
            ->setSection( 'News' )
            ->setTag( [ 'finance', 'money' ] );

        $meta->addArticle( $article );

        $this->assertTrue(
            $this->hasMetaTag(
                $meta->render( ),
                'og:article:published_time'
            )
        );

        $this->assertEquals(
            $article->getPublishedTime( ),
            new \DateTime( $this->getContentOfMetaTag(
                $meta->render( ),
                'og:article:published_time'
            ) )
        );

        $this->assertTrue(
            $this->dateIsISO8601(
                $this->getContentOfMetaTag(
                    $meta->render( ),
                    'og:article:published_time'
                )
            )
        );

        $this->assertTrue(
            $this->hasMetaTag(
                $meta->render( ),
                'og:article:modified_time'
            )
        );

        $this->assertEquals(
            $article->getModifiedTime( ),
            new \DateTime( $this->getContentOfMetaTag(
                $meta->render( ),
                'og:article:modified_time'
            ) )
        );

        $this->assertTrue(
            $this->hasMetaTag(
                $meta->render( ),
                'og:article:expiration_time'
            )
        );

        $this->assertEquals(
            $article->getExpirationTime( ),
            new \DateTime( $this->getContentOfMetaTag(
                $meta->render( ),
                'og:article:expiration_time'
            ) )
        );

        $this->assertTrue(
            $this->hasMetaTag(
                $meta->render( ),
                'og:article:section'
            )
        );

        $this->assertEquals(
            'News',
            $this->getContentOfMetaTag(
                $meta->render( ),
                'og:article:section'
            )
        );

        $this->assertTrue(
            $this->hasMetaTag(
                $meta->render( ),
                'og:article:author'
            )
        );

        $this->assertEquals(
            'Joe Bloggs',
            $this->getContentOfMetaTag(
                $meta->render( ),
                'og:article:author'
            )
        );

        $tags = $this->getContentOfMultipleMetaTags( $meta->render( ), 'og:article:tag' );
        $this->assertEquals( 2, count( $tags ) );
        $this->assertTrue( in_array( 'finance', $tags ) );
        $this->assertTrue( in_array( 'money', $tags ) );

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

    public function testAddingArticleMultipleAuthors( )
    {
        $meta = new MetaTags();

        $article = new \Lukaswhite\MetaTags\Entities\Article();
        $article->setPublishedTime(new \DateTime('2018-10-03 15:20'))
            ->setModifiedTime(new \DateTime('2018-10-12 17:32'))
            ->setExpirationTime(new \DateTime('2018-12-21'))
            ->setAuthor( [ 'Joe Bloggs', 'Harry Black' ] )
            ->setSection('News')
            ->setTag( 'finance' );

        $meta->addArticle($article);

        $authors = $this->getContentOfMultipleMetaTags( $meta->render( ), 'og:article:author' );
        $this->assertEquals( 2, count( $authors ) );
        $this->assertTrue( in_array( 'Joe Bloggs', $authors ) );
        $this->assertTrue( in_array( 'Harry Black', $authors ) );

        $tags = $this->getContentOfMultipleMetaTags( $meta->render( ), 'og:article:tag' );
        $this->assertEquals( 1, count( $tags ) );
        $this->assertTrue( in_array( 'finance', $tags ) );

    }

}