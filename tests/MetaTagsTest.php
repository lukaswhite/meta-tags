<?php

namespace Lukaswhite\MetaTags\Tests;

use Lukaswhite\MetaTags\Entities\Geopoint;
use Lukaswhite\MetaTags\Tests\TestCase;
use Lukaswhite\MetaTags\MetaTags;

class MetaTagsTest extends TestCase
{
    public function testCreatingInstance( )
    {
        $meta = new MetaTags( );
        $this->assertInstanceOf( \Lukaswhite\MetaTags\MetaTags::class, $meta );
    }

    public function testCreatingInstanceWithConfig( )
    {
        $meta = new MetaTags( [ 'basic' => [ 'title' ] ] );
        $this->assertInstanceOf( \Lukaswhite\MetaTags\MetaTags::class, $meta );
        $this->assertEquals( [ 'title' ], $meta->config( 'basic' ) );
    }

    public function testThatDefaultOgTypeIsAdded( )
    {
        $meta = new MetaTags( );

        $this->assertTrue(
            $this->hasMetaTag(
                $meta->render( ),
                'og:type'
            )
        );

        $this->assertEquals(
            'website',
            $this->getContentOfMetaTag(
                $meta->render( ),
                'og:type'
            )
        );
    }

    public function testCharSetAddedAutomatically( )
    {
        $meta = new MetaTags( );
        $this->assertTrue(
            $this->strContains(
                $meta->render( ),
                '<meta charset="utf-8" />'
            )
        );
    }

    public function testCanSetCharSet( )
    {
        $meta = new MetaTags( );
        $meta->charSet( 'ISO-8859-1' );
        $this->assertTrue(
            $this->strContains(
                $meta->render( ),
                '<meta charset="ISO-8859-1" />'
            )
        );
    }

    public function testCanUnsetCharSet( )
    {
        $meta = new MetaTags( );
        $meta->charSet( null );
        $this->assertFalse(
            $this->hasMetaTag(
                $meta->render( ),
                'charset'
            )
        );
    }

    public function testTitle( )
    {
        $meta = new MetaTags( );
        $meta->title( 'Page Title' );

        $this->assertTrue(
            $this->strContains(
                $meta->render( ),
                '<title>Page Title</title>'
            )
        );

        $this->assertTrue(
            $this->hasMetaTag(
                $meta->render( ),
                'og:title'
            )
        );

        $this->assertEquals(
            'Page Title',
            $this->getContentOfMetaTag(
                $meta->render( ),
                'og:title'
            )
        );

        $this->assertTrue(
            $this->hasMetaTag(
                $meta->render( ),
                'twitter:title',
                'name'
            )
        );

        $this->assertEquals(
            'Page Title',
            $this->getContentOfMetaTag(
                $meta->render( ),
                'twitter:title',
                'name'
            )
        );

        $this->assertFalse(
            $this->strContains(
                $meta->render( ),
                '<meta itemprop="name" content="Page Title" />'
            )
        );

    }

    public function testUrl( )
    {
        $meta = new MetaTags( );
        $meta->url( 'http://example.com' );

        $this->assertTrue(
            $this->hasMetaTag(
                $meta->render( ),
                'url',
                'name'
            )
        );

        $this->assertEquals(
            'http://example.com',
            $this->getContentOfMetaTag(
                $meta->render( ),
                'url',
                'name'
            )
        );

        $this->assertTrue(
            $this->hasMetaTag(
                $meta->render( ),
                'og:url'
            )
        );

        $this->assertEquals(
            'http://example.com',
            $this->getContentOfMetaTag(
                $meta->render( ),
                'og:url'
            )
        );

    }

    public function testEnablingSchemaOrg( )
    {
        $meta = new MetaTags( );
        $meta->title( 'Page Title' );
        $meta->includeSchemaOrg( );
        $this->assertTrue(
            $this->strContains(
                $meta->render( ),
                '<meta itemprop="name" content="Page Title" />'
            )
        );
    }

    public function testNewlines( )
    {
        $meta = new MetaTags( );
        $meta->title( 'Page Title' );

        $this->assertTrue(
            $this->strContains(
                $meta->render( ),
                "\n"
            )
        );
    }

    public function testEmptySuffix( )
    {
        $meta = new MetaTags( );
        $meta->tagSuffix( '' );
        $meta->title( 'Page Title' );

        $this->assertFalse(
            $this->strContains(
                $meta->render( ),
                "\n"
            )
        );
    }

    public function testSettingPrefix( )
    {
        $meta = new MetaTags( );
        $meta->tagPrefix( '' );
        $meta->title( 'Page Title' );

        $this->assertTrue(
            $this->strContains(
                $meta->render( ),
                "\n"
            )
        );
    }

    public function testVerbose( )
    {
        $meta = new MetaTags( );
        $meta->verbose( );
        $meta->title( 'Page Title' );

        $this->assertFalse(
            $this->strContains(
                $meta->render( ),
                "\n"
            )
        );
    }

    public function testTwitterTitleIsTruncated( )
    {
        $meta = new MetaTags( );
        $meta->title( 'Page Title that is too long and should be truncated to 70 characters, because this is too long' );

        $this->assertEquals(
            'Page Title that is too long and should be truncated to 70 character...',
            $this->getContentOfMetaTag(
                $meta->render( ),
                'twitter:title',
                'name'
            )
        );

    }

    public function testTwitterTitle( )
    {
        $meta = new MetaTags( );
        $meta->title( 'Page Title' );
        $meta->twitterTitle( 'Twitter Page Title' );
        $this->assertTrue(
            $this->hasMetaTag(
                $meta->render( ),
                'twitter:title',
                'name'
            )
        );

        $this->assertEquals(
            'Twitter Page Title',
            $this->getContentOfMetaTag(
                $meta->render( ),
                'twitter:title',
                'name'
            )
        );


    }

    public function testTwitterSite( )
    {
        $meta = new MetaTags( );

        $meta->twitterSite( '@nytimesbits' );

        $this->assertTrue(
            $this->hasMetaTag(
                $meta->render( ),
                'twitter:site',
                'name'
            )
        );

        $this->assertEquals(
            '@nytimesbits',
            $this->getContentOfMetaTag(
                $meta->render( ),
                'twitter:site',
                'name'
            )
        );
    }

    public function testTwitterCreator( )
    {
        $meta = new MetaTags( );

        $meta->twitterCreator( '@nickbilton' );

        $this->assertTrue(
            $this->hasMetaTag(
                $meta->render( ),
                'twitter:creator',
                'name'
            )
        );

        $this->assertEquals(
            '@nickbilton',
            $this->getContentOfMetaTag(
                $meta->render( ),
                'twitter:creator',
                'name'
            )
        );
    }


    public function testDescription( )
    {
        $meta = new MetaTags( );
        $meta->description( 'This is the description' );

        $this->assertEquals(
            'This is the description',
            $this->getContentOfMetaTag(
                $meta->render( ),
                'description',
                'name'
            )
        );

        $this->assertTrue(
            $this->hasMetaTag(
                $meta->render( ),
                'og:description'
            )
        );

        $this->assertEquals(
            'This is the description',
            $this->getContentOfMetaTag(
                $meta->render( ),
                'og:description'
            )
        );

        $this->assertFalse(
            $this->strContains(
                $meta->render( ),
                '<meta itemprop="description" content="This is the description" />'
            )
        );
    }

    public function testDescriptionWithSchemaOrgEnabled( )
    {
        $meta = new MetaTags( );
        $meta->description( 'This is the description' );
        $meta->includeSchemaOrg( );
        $this->assertTrue(
            $this->strContains(
                $meta->render( ),
                '<meta itemprop="description" content="This is the description" />'
            )
        );
    }

    public function testDescriptionForTwitter( )
    {
        $meta = new MetaTags( );
        $meta->description( 'This is the description' );
        $meta->twitterDescription( 'This is the description for Twitter' );

        $this->assertTrue(
            $this->hasMetaTag(
                $meta->render( ),
                'twitter:description',
                'name'
            )
        );

        $this->assertEquals(
            'This is the description for Twitter',
            $this->getContentOfMetaTag(
                $meta->render( ),
                'twitter:description',
                'name'
            )
        );
    }

    public function testDescriptionIsTruncated( )
    {
        $meta = new MetaTags( );
        $meta->description( 'is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.' );

        $this->assertEquals(
            160,
            strlen(
                $this->getContentOfMetaTag(
                    $meta->render( ),
                    'description',
                    'name'
                )
            )
        );

        $this->assertEquals(
            200,
            strlen(
                $this->getContentOfMetaTag(
                    $meta->render( ),
                    'og:description'
                )
            )
        );
    }

    public function testSettingKeywords( )
    {
        $one = new MetaTags( );
        $one->keywords( 'PHP, library, meta' );

        $this->assertTrue(
            $this->strContains(
                $one->render( ),
                '<meta name="keywords" content="PHP, library, meta" />'
            )
        );

        $two = new MetaTags( );
        $two->keywords( 'PHP', 'library', 'meta' );

        $this->assertTrue(
            $this->strContains(
                $one->render( ),
                '<meta name="keywords" content="PHP, library, meta" />'
            )
        );
    }

    public function testType( )
    {
        $meta = new MetaTags( );
        $meta->type( 'article' );

        $this->assertEquals(
            'article',
            $this->getContentOfMetaTag(
                $meta->render( ),
                'og:type'
            )
        );
    }

    public function testTypeWithAttributes( )
    {
        $meta = new MetaTags( );
        $meta->type(
            MetaTags::OG_TYPE_BOOK,
            [
                'isbn'  =>  'ISBN-123-1234',
            ]
        );

        $this->assertEquals(
            'ISBN-123-1234',
            $this->getContentOfMetaTag(
                $meta->render( ),
                'og:book:isbn'
            )
        );
    }

    public function testCanonical( )
    {
        $meta = new MetaTags( );
        $meta->canonical( 'http://example.com' );

        $this->assertTrue( $this->hasLinkWithRel( $meta->render( ), 'canonical' ) );
        $this->assertEquals(
            'http://example.com',
            $this->getContentOfLinkTag(
                $meta->render( ),
                'canonical'
            )
        );

    }

    public function testPagination( )
    {
        $meta = new MetaTags( );
        $meta
            ->firstPage( 'http://example.com/articles/page/1' )
            ->previousPage( 'http://example.com/articles/page/3' )
            ->nextPage( 'http://example.com/articles/page/5' )
            ->lastPage( 'http://example.com/articles/page/10' );

        $this->assertTrue( $this->hasLinkWithRel( $meta->render( ), 'prev' ) );
        $this->assertEquals(
            'http://example.com/articles/page/3',
            $this->getContentOfLinkTag(
                $meta->render( ),
                'prev'
            )
        );

        $this->assertTrue( $this->hasLinkWithRel( $meta->render( ), 'next' ) );
        $this->assertEquals(
            'http://example.com/articles/page/5',
            $this->getContentOfLinkTag(
                $meta->render( ),
                'next'
            )
        );

        $this->assertTrue( $this->hasLinkWithRel( $meta->render( ), 'first' ) );
        $this->assertEquals(
            'http://example.com/articles/page/1',
            $this->getContentOfLinkTag(
                $meta->render( ),
                'first'
            )
        );

        $this->assertTrue( $this->hasLinkWithRel( $meta->render( ), 'last' ) );
        $this->assertEquals(
            'http://example.com/articles/page/10',
            $this->getContentOfLinkTag(
                $meta->render( ),
                'last'
            )
        );
    }

    public function testManifest( )
    {
        $meta = new MetaTags( );
        $meta->manifest( 'http://example.com/manifest.json' );

        $this->assertTrue( $this->hasLinkWithRel( $meta->render( ), 'manifest' ) );
        $this->assertEquals(
            'http://example.com/manifest.json',
            $this->getContentOfLinkTag(
                $meta->render( ),
                'manifest'
            )
        );
    }

    public function testAddLinks( )
    {
        $meta = new MetaTags( );
        $meta->addLink( '/assets/styles.min.css', 'stylesheet' );

        $this->assertTrue( $this->hasLinkWithRel( $meta->render( ), 'stylesheet' ) );
        $this->assertEquals(
            '/assets/styles.min.css',
            $this->getContentOfLinkTag(
                $meta->render( ),
                'stylesheet'
            )
        );
    }

    public function testRobots( )
    {
        $one = new MetaTags( );
        $one->robotsShouldFollowButNotIndex( );

        //var_dump( $one->tags );
        //var_dump( $one->render( ) );

        $this->assertTrue(
            $this->strContains(
                $one->render( ),
                '<meta name="robots" content="noindex, follow" />'
            )
        );

        $two = new MetaTags( );
        $two->robotsShouldIndexButNotFollow( );

        $this->assertTrue(
            $this->strContains(
                $two->render( ),
                '<meta name="robots" content="index, nofollow" />'
            )
        );

        $three = new MetaTags( );
        $three->robotsShouldNotIndexNorFollow( );

        $this->assertTrue(
            $this->strContains(
                $three->render( ),
                '<meta name="robots" content="noindex, nofollow" />'
            )
        );

    }

    public function testInstructGoogleBots( )
    {
        $meta = new MetaTags( );
        $meta->robotsShouldFollowButNotIndex( );


        $this->assertTrue(
            $this->strContains(
                $meta->render( ),
                '<meta name="robots" content="noindex, follow" />'
            )
        );

        $this->assertTrue(
            $this->strContains(
                $meta->render( ),
                '<meta name="googlebot" content="noindex, follow" />'
            )
        );

        $date = new \DateTime( 'Sunday, July 18th, 2010, 5:15 pm' );

        $meta->googleShouldNotIncludeSnippets()
            ->googleShouldNotShowCachedLinks()
            ->googleShouldNotShowPageAsReferringPageForImageSearchResults( );

        $directions = explode( ', ', $this->getContentOfMetaTag(
            $meta->render( ),
            'googlebot',
            'name'
        ) );

        $this->assertTrue( in_array( 'noindex', $directions ) );
        $this->assertTrue( in_array( 'follow', $directions ) );
        $this->assertTrue( in_array( 'nosnippet', $directions ) );
        $this->assertTrue( in_array( 'noarchive', $directions ) );
        $this->assertTrue( in_array( 'nopageindex', $directions ) );
    }

    public function testTellingGoogleNotToCrawlAfterDate( )
    {
        $date = new \DateTime( 'Sunday, July 18th, 2010, 5:15 pm' );
        $meta = new MetaTags( );
        $meta->googleShouldStopCrawlingAfter( $date );
        $directions = explode( ', ', $this->getContentOfMetaTag(
            $meta->render( ),
            'googlebot',
            'name'
        ) );
        $dateStr = substr( $directions[ 0 ], 18 );
        $this->assertEquals(
            $date->format( 'Y-m-d H:i' ),
            ( new \DateTime( $dateStr ) )->format( 'Y-m-d H:i' )
        );
    }

    public function testAddingRssFeed( )
    {
        $meta = new MetaTags( );
        $meta->addRssFeed( 'http://example.com/feed.rss', 'RSS Feed' );

        //var_dump( $meta->render( ) );

        $this->assertTrue(
            $this->strContains(
                $meta->render( ),
                '<link rel="alternate" href="http://example.com/feed.rss" type="application/rss+xml" title="RSS Feed" />'
            )
        );

        $meta->addRssFeed( 'http://example.com/feed2.rss' );

        //var_dump( $meta->render( ) );

        $this->assertTrue(
            $this->strContains(
                $meta->render( ),
                '<link rel="alternate" href="http://example.com/feed2.rss" type="application/rss+xml" />'
            )
        );
    }

    public function testAddingAtomFeed( )
    {
        $meta = new MetaTags( );
        $meta->addAtomFeed( 'http://example.com/feed.xml', 'Atom Feed' );

        //var_dump( $meta->render( ) );

        $this->assertTrue(
            $this->strContains(
                $meta->render( ),
                '<link rel="alternate" href="http://example.com/feed.xml" type="application/atom+xml" title="Atom Feed" />'
            )
        );

        $meta->addAtomFeed('http://example.com/feed2.xml' );

        //var_dump( $meta->render( ) );

        $this->assertTrue(
            $this->strContains(
                $meta->render( ),
                '<link rel="alternate" href="http://example.com/feed2.xml" type="application/atom+xml" />'
            )
        );
    }

    public function testHttpEquiv( )
    {
        $meta = new MetaTags( );
        $meta->httpEquiv( 'pragma', 'no-cache' );
        $this->assertTrue(
            $this->strContains(
                $meta->render( ),
                '<meta http-equiv="pragma" content="no-cache"'
            )
        );
    }

    public function testContentType( )
    {
        $meta = new MetaTags( );
        $meta->contentType( 'text/html; charset=ISO-8859-1' );
        $this->assertTrue(
            $this->strContains(
                $meta->render( ),
                '<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1"'
            )
        );
    }

    public function testNoCache( )
    {
        $meta = new MetaTags( );

        $meta->tellBrowsersNotToCache( );

        $this->assertTrue(
            $this->strContains(
                $meta->render( ),
                '<meta http-equiv="pragma" content="no-cache" />'
            )
        );

        $this->assertTrue(
            $this->strContains(
                $meta->render( ),
                '<meta http-equiv="cache-control" content="max-age=0" />'
            )
        );

        $this->assertTrue(
            $this->strContains(
                $meta->render( ),
                '<meta http-equiv="cache-control" content="no-cache" />'
            )
        );

        $this->assertTrue(
            $this->strContains(
                $meta->render( ),
                '<meta http-equiv="cache-control" content="no-store" />'
            )
        );

    }

    public function testConvertsDates( )
    {
        $meta = new MetaTags( );
        $meta->set( 'revised', new \DateTime( 'Sunday, July 18th, 2010, 5:15 pm' ) );

        //var_dump( $meta->render());

        $this->assertTrue(
            $this->strContains(
                $meta->render( ),
                '<meta name="revised" content="2010-07-18T17:15:00' //+0000" />'
            )
        );
    }

    public function testCustom( )
    {
        $meta = new MetaTags( );

        $meta->custom(
            'mixpanel',
            'your-project-id'
        );

        $this->assertTrue(
            $this->strContains(
                $meta->render( ),
                '<meta name="mixpanel" content="your-project-id" />'
            )
        );

        $meta->custom(
            'foo',
            'bar',
            'property'
        );

        $this->assertTrue(
            $this->strContains(
                $meta->render( ),
                '<meta property="foo" content="bar" />'
            )
        );
    }

    public function testOpenGraphSingle( )
    {
        $meta = new MetaTags( );
        $meta->openGraph(
            'latitude',
            37.416343
        );
        $meta->openGraph(
            'longitude',
            -122.153013
        );

        $this->assertTrue(
            $this->hasMetaTag(
                $meta->render( ),
                'og:latitude'
            )
        );

        $this->assertEquals(
            '37.416343',
            $this->getContentOfMetaTag(
                $meta->render( ),
                'og:latitude'
            )
        );

        $this->assertTrue(
            $this->hasMetaTag(
                $meta->render( ),
                'og:longitude'
            )
        );

        $this->assertEquals(
            '-122.153013',
            $this->getContentOfMetaTag(
                $meta->render( ),
                'og:longitude'
            )
        );
    }

    public function testSettingLocale( )
    {
        $meta = new MetaTags( );
        $meta->locale( 'en_GB' );

        $this->assertTrue(
            $this->hasMetaTag(
                $meta->render( ),
                'og:locale'
            )
        );

        $this->assertEquals(
            'en_GB',
            $this->getContentOfMetaTag(
                $meta->render( ),
                'og:locale'
            )
        );
    }

    public function testSettingOtherOpenGraphTags( )
    {
        $meta = new MetaTags( );
        $date = new \DateTime( '2018-10-12' );
        $meta->openGraph( 'determiner', 'an' );
        $meta->openGraph( 'updated_time', $date );
        $meta->openGraph( 'restrictions:age', 18 );

        $this->assertTrue(
            $this->hasMetaTag(
                $meta->render( ),
                'og:determiner'
            )
        );

        $this->assertEquals(
            'an',
            $this->getContentOfMetaTag(
                $meta->render( ),
                'og:determiner'
            )
        );

        $this->assertTrue(
            $this->hasMetaTag(
                $meta->render( ),
                'og:updated_time'
            )
        );

        $this->assertEquals(
            $date->format( 'Y-m-d' ),
            ( new \DateTime( $this->getContentOfMetaTag(
                $meta->render( ),
                'og:updated_time'
            ) ) )->format( 'Y-m-d' )
        );

        $this->assertTrue(
            $this->hasMetaTag(
                $meta->render( ),
                'og:restrictions:age'
            )
        );

        $this->assertEquals(
            '18',
            $this->getContentOfMetaTag(
                $meta->render( ),
                'og:restrictions:age'
            )
        );
    }

    public function testAddingGeopoint( )
    {
        $meta = new MetaTags( );
        $meta->addGeopoint( new Geopoint( 37.416343, -122.153013 ) );

        $this->assertTrue(
            $this->hasMetaTag(
                $meta->render( ),
                'og:latitude'
            )
        );

        $this->assertEquals(
            '37.416343',
            $this->getContentOfMetaTag(
                $meta->render( ),
                'og:latitude'
            )
        );

        $this->assertTrue(
            $this->hasMetaTag(
                $meta->render( ),
                'og:longitude'
            )
        );

        $this->assertEquals(
            '-122.153013',
            $this->getContentOfMetaTag(
                $meta->render( ),
                'og:longitude'
            )
        );
    }

    public function testAddingGeopointAltitude( )
    {
        $meta = new MetaTags( );
        $meta->addGeopoint(
            ( new Geopoint( ) )
                ->setLatitude( 37.416343 )
            ->setLongitude( -122.153013 )
            ->setAltitude( 42 )
        );

        $this->assertTrue(
            $this->hasMetaTag(
                $meta->render( ),
                'og:altitude'
            )
        );

        $this->assertEquals(
            '42',
            $this->getContentOfMetaTag(
                $meta->render( ),
                'og:altitude'
            )
        );

        $this->assertTrue(
            $this->hasMetaTag(
                $meta->render( ),
                'og:longitude'
            )
        );

    }

    public function testOpenGraphMultiple( )
    {
        $meta = new MetaTags( );
        $meta->openGraph(
            [
                'latitude' => 37.416343,
                'longitude' =>  -122.153013,
            ]
        );

        $this->assertTrue(
            $this->hasMetaTag(
                $meta->render( ),
                'og:latitude'
            )
        );

        $this->assertEquals(
            '37.416343',
            $this->getContentOfMetaTag(
                $meta->render( ),
                'og:latitude'
            )
        );

        $this->assertTrue(
            $this->hasMetaTag(
                $meta->render( ),
                'og:longitude'
            )
        );

        $this->assertEquals(
            '-122.153013',
            $this->getContentOfMetaTag(
                $meta->render( ),
                'og:longitude'
            )
        );
    }

    public function testSettingSiteName( )
    {
        $meta = new MetaTags( );
        $meta->siteName( 'My Website' );
        $this->assertTrue(
            $this->hasMetaTag(
                $meta->render( ),
                'og:site_name'
            )
        );

        $this->assertEquals(
            'My Website',
            $this->getContentOfMetaTag(
                $meta->render( ),
                'og:site_name'
            )
        );
    }

    public function testSettingAddressTags( )
    {
        $meta = new MetaTags( );

        $meta->streetAddress( '1601 S California Ave' )
            ->locality( 'Palo Alto' )
            ->region( 'CA' )
            ->postalCode( '94304' )
            ->country( 'USA' );

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

    }

    public function testSettingContactTags( )
    {
        $meta = new MetaTags();

        $meta->email('me@example.com')
            ->phone('650-123-4567')
            ->fax('+1-415-123-4567')
            ->website( 'http://example.com' );

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

    public function testIsArticle( )
    {
        $meta = new MetaTags();

        $meta->isArticle( );

        $this->assertTrue(
            $this->hasMetaTag(
                $meta->render( ),
                'og:type'
            )
        );

        $this->assertEquals(
            'article',
            $this->getContentOfMetaTag(
                $meta->render( ),
                'og:type'
            )
        );
    }

    public function testIsBusiness( )
    {
        $meta = new MetaTags();

        $meta->isBusiness( );

        $this->assertTrue(
            $this->hasMetaTag(
                $meta->render( ),
                'og:type'
            )
        );

        $this->assertEquals(
            'business.business',
            $this->getContentOfMetaTag(
                $meta->render( ),
                'og:type'
            )
        );
    }

    public function testIsBook( )
    {
        $meta = new MetaTags();

        $meta->isBook( );

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
    }

    public function testIsProfile( )
    {
        $meta = new MetaTags();

        $meta->isProfile( );

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
    }

    public function testTwitterCard( )
    {
        $meta = new MetaTags( );
        $meta->twitterCard( MetaTags::TWITTER_CARD_SUMMARY );

        $this->assertTrue(
            $this->hasMetaTag(
                $meta->render( ),
                'twitter:card',
                'name'
            )
        );

        $this->assertEquals(
            'summary',
            $this->getContentOfMetaTag(
                $meta->render( ),
                'twitter:card',
                'name'
            )
        );
    }

    public function testTwitterCardDefault( )
    {
        $meta = new MetaTags( );
        $meta->twitterCard(  );

        $this->assertTrue(
            $this->hasMetaTag(
                $meta->render( ),
                'twitter:card',
                'name'
            )
        );

        $this->assertEquals(
            'summary',
            $this->getContentOfMetaTag(
                $meta->render( ),
                'twitter:card',
                'name'
            )
        );
    }

    public function testFacebookAppId( )
    {
        $meta = new MetaTags( );

        $meta->facebookAppId( '12345678910' );

        $this->assertTrue(
            $this->hasMetaTag(
                $meta->render( ),
                'fb:app_id'
            )
        );

        $this->assertEquals(
            '12345678910',
            $this->getContentOfMetaTag(
                $meta->render( ),
                'fb:app_id'
            )
        );
    }

    public function testFacebookProfileId( )
    {
        $meta = new MetaTags( );

        $meta->facebookProfileId( '78676576576' );

        $this->assertTrue(
            $this->hasMetaTag(
                $meta->render( ),
                'fb:profile_id'
            )
        );

        $this->assertEquals(
            '78676576576',
            $this->getContentOfMetaTag(
                $meta->render( ),
                'fb:profile_id'
            )
        );
    }

    public function testFacebookAdmins( )
    {
        $meta = new MetaTags( );

        $meta->facebookAdmins(
            '12345',
            '678910'
        );

        $this->assertTrue(
            $this->hasMetaTag(
                $meta->render( ),
                'fb:admins'
            )
        );

        $this->assertEquals(
            '12345, 678910',
            $this->getContentOfMetaTag(
                $meta->render( ),
                'fb:admins'
            )
        );
    }

    public function testFacebookPages( )
    {
        $meta = new MetaTags( );

        $meta->facebookPages(
            '76236783',
            '67873687'
        );

        $this->assertTrue(
            $this->hasMetaTag(
                $meta->render( ),
                'fb:pages'
            )
        );

        $this->assertEquals(
            '76236783, 67873687',
            $this->getContentOfMetaTag(
                $meta->render( ),
                'fb:pages'
            )
        );
    }

    public function testDisablingOpenGraph( )
    {
        $meta = new MetaTags( );
        $meta->title( 'The Title' )
            ->description( 'The description' )
            ->url( 'http://example.com' );

        $meta->includeOpenGraph( false );

        $this->assertFalse(
            $this->hasMetaTag(
                $meta->render( ),
                'og:title'
            )
        );

        $this->assertFalse(
            $this->hasMetaTag(
                $meta->render( ),
                'og:description'
            )
        );

        $this->assertFalse(
            $this->hasMetaTag(
                $meta->render( ),
                'og:url'
            )
        );
    }

    public function testViewport( )
    {
        $meta = new MetaTags();
        $meta->viewport( 'width=device-width, initial-scale=1' );

        $this->assertTrue(
            $this->hasMetaTag(
                $meta->render( ),
                'viewport',
                'name'
            )
        );

        $this->assertEquals(
            'width=device-width, initial-scale=1',
            $this->getContentOfMetaTag(
                $meta->render( ),
                'viewport',
                'name'
            )
        );
    }

    public function testForgetting( )
    {
        $meta = new MetaTags( );
        $meta->url( 'http://example.com' );

        $meta->forget( 'url' );

        $this->assertFalse(
            $this->hasMetaTag(
                $meta->render( ),
                'url',
                'name'
            )
        );
    }

    public function __testSettingFacebookPageId( )
    {
        $meta = new MetaTags();

        $meta->set( 'fb_page_id', 12345678910 );

        $meta->isBusiness();

        var_dump( $meta->render( ) );

        $this->assertTrue(
            $this->strContains(
                $meta->render(),
                '<meta name="fb:page_id" content="12345678910" />'
            )
        );
    }

    public function testSkipsEmpties( )
    {
        $meta = new MetaTags( );
        $meta->title( 'Page Title' );
        $rendered = $meta->render( );
        $meta->set( 'csrf-token', null );
        $this->assertEquals( $rendered, $meta->render( ) );
    }

    public function testHasMagicToString( )
    {
        $meta = new MetaTags( );
        $meta->title( 'Test Title' );
        $this->assertEquals(
            $meta->render( ),
            ( string ) $meta
        );
    }

    //private function hasMetaTag( $html )

}