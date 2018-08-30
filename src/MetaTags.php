<?php

namespace Lukaswhite\MetaTags;

use DateTime;
use Lukaswhite\HtmlElement\HtmlElement;
use Lukaswhite\MetaTags\Contracts\Article;
use Lukaswhite\MetaTags\Contracts\Audio;
use Lukaswhite\MetaTags\Contracts\Book;
use Lukaswhite\MetaTags\Contracts\ContactData;
use Lukaswhite\MetaTags\Contracts\Geopoint;
use Lukaswhite\MetaTags\Contracts\Image;
use Lukaswhite\MetaTags\Contracts\Profile;
use Lukaswhite\MetaTags\Contracts\Video;
use Lukaswhite\MetaTags\Entities\BusinessDay;

/**
 * Class MetaTags
 *
 * This class is used to manage and then output page metadata, such as:
 *
 * - the page title
 * - the page description
 * - the canonical URL
 * - a link to the web manifest, if applicable
 * - pagination links (rel=prev, rel=next)
 * - Open Graph tags
 * - Twitter tags
 *
 * @package Jobyay\Core\Services\Seo\MetaTags
 */
class MetaTags
{
    /**
     * Class constants that represent Open Graph types; i.e. values that
     * you can give the og:type meta tag.
     */
    const OG_TYPE_WEBSITE                       =   'website'; // This is the default
    const OG_TYPE_ARTICLE                       =   'article';
    const OG_TYPE_BOOK                          =   'book';
    const OG_TYPE_PROFILE                       =   'profile';
    const OG_TYPE_BUSINESS                      =   'business.business';
    const OG_TYPE_MUSIC_SONG                    =   'music.song';
    const OG_TYPE_MUSIC_ALBUM                   =   'music.album';
    const OG_TYPE_MUSIC_PLAYLIST                =   'music.playlist';
    const OG_TYPE_MUSIC_RADIO_STATION           =   'music.radio_station';
    const OG_TYPE_VIDEO_MOVIE                   =   'video.movie';
    const OG_TYPE_VIDEO_EPISODE                 =   'video.episode';
    const OG_TYPE_VIDEO_TV_SHOW                 =   'video.tv_show';
    const OG_TYPE_VIDEO_OTHER                   =   'video.other';

    /**
     * Class constants that represent Twitter card types
     */
    const TWITTER_CARD_SUMMARY                  =   'summary'; // This is the default
    const TWITTER_CARD_SUMMARY_LARGE_IMAGE      =   'summary_large_image';
    const TWITTER_CARD_APP                      =   'app';
    const TWITTER_CARD_PLAYER                   =   'player';

    const FEED_RSS                              =   'application/rss+xml';
    const FEED_ATOM                             =   'application/atom+xml';

    /**
     * Package configuration values.
     *
     * @var array
     */
    protected $config = [
        'truncate' => [
            'description'           =>  160,
            'twitter:title'         =>  70,
            'og:description'        =>  200,
            'twitter:description'   =>  200,
        ],
        'basic' => [
            'keywords',
            'description',
            'subject',
            'copyright',
            'language',
            'robots',
            'revised',
            'topic',
            'summary',
            'classification',
            'author',
            'designer',
            'reply-to',
            'owner',
            'url',
            'identifier-URL',
            'directory',
            'category',
            'coverage',
            'distribution',
            'rating',
            'revisit-after',
            'viewport',
        ],
    ];

    /**
     * The "normal" (non-Open Graph) tags.
     *
     * @var array
     */
    public $tags = [
        'general'   =>  [
            // Include the og:type attribute by default, defaulting to website
            'type' => [
                'value'         => self::OG_TYPE_WEBSITE,
                'attributes'    => [ ],
            ],
        ],
        'twitter'   => [ ],
        'other'     => [ ],
    ];

    /**
     * The Open Graph tags
     *
     * @var array
     */
    protected $ogTags = [ ];

    /**
     * Whether to render Open Graph tags
     *
     * @var bool
     */
    protected $includeOg = true;

    /**
     * The Open Graph type
     *
     * @var string
     */
    protected $type = self::OG_TYPE_WEBSITE;

    /**
     * The character set (charset)
     *
     * @var string
     */
    protected $charSet = 'utf-8';

    /**
     * The canonical URL
     *
     * @var string
     */
    protected $canonical;

    /**
     * The manifest URL
     *
     * @var string
     */
    protected $manifest;

    /**
     * Any additional links
     *
     * @var array
     */
    protected $links = [ ];

    /**
     * Directions for the Google Bot
     *
     * @var array
     */
    protected $googleBotDirections = [ ];

    /**
     * The HTTP Equiv options
     *
     * @var array
     */
    protected $httpEquiv = [ ];

    /**
     * Pagination links
     *
     * @var array
     */
    protected $pagination;

    /**
     * Any custom meta tags
     *
     * @param array
     */
    protected $custom = [ ];

    /**
     * The tag prefix is a string inserted before every rendered tag.
     *
     * @var string
     */
    protected $tagPrefix = '';

    /**
     * The tag prefix is a string inserted after every rendered tag.
     *
     * @var string
     */
    protected $tagSuffix = "\n";

    /**
     * Whether to include Schema.org meta tags.
     *
     * IMPORTANT:
     * If you enable this, then you need to add an itemtype attribute to the <html> tag, e.g.:
     *
     * <html itemscope itemtype="http://schema.org/WebPage">
     */
    protected $includeSchemaOrg = false;

    /**
     * Create a new Manager instance.
     *
     * @param array $config
     */
    public function __construct( array $config = [ ] )
    {
        foreach( $config as $name => $value)
        {
            $this->config[$name] = $value;
        }
    }

    /**
     * Adds a type tag.
     *
     * @param string $type
     * @param array $attributes
     * @return self
     */
    public function type( $type, $attributes = [ ] )
    {
        $this->type = $type;

        if ( count( $attributes ) ) {
            foreach( $attributes as $key => $value ) {
                $this->openGraph(
                    sprintf( '%s:%s', $type, $key ),
                    $value
                );
            }
        }

        return $this;
    }

    /**
     * Set tag data.
     *
     * @param string $name
     * @param mixed  $value
     * @param array  $attributes
     * @param string $type
     *
     * @return self
     */
    public function set( $name, $value, array $attributes = [], $type = 'general' )
    {
        // Skip empties
        if ($value === null && empty($attributes)) {
            return $this;
        }

        // Validate attributes
        if ($this->config('validate', false) === true && empty($attributes) === false) {
            //$this->validation($name, $attributes);
        }

        // If the value is a date, convert it
        if ( is_a( $value, 'DateTime' ) ) {
            $value = ( string ) $value->format( \DateTime::ISO8601 );
        }

        // Remove all tags
        $value = strip_tags($value);

        // Remove non-ASCII printable characters
        $value = preg_replace('/[^\x20-\x7E]/', '', $value);

        // Remove all excess white space
        $value = trim(preg_replace('!\s+!', ' ', $value));

        // Consider all tags to be general unless the prefix
        // matches a special value.

        // Determine if the tag is Twitter specific
        if (substr($name, 0, 8) === 'twitter_') {
            $type = 'twitter';
            $name = str_replace('_', ':', substr($name, 8));

            // Ensure the tag is rendered
            $this->config['twitter'] = true;
        }

        // Set tag data
        $this->tags[$type][$name] = [
            'value' => $value,
            'attributes' => $attributes,
        ];

        return $this;
    }

    /**
     * Get a specific tag.
     *
     * @param string $key
     * @param string $type
     *
     * @return array|null
     */
    public function get($key, $type = 'general')
    {
        if ( array_key_exists( $key, $this->tags[ $type ] ) ) {
            return $this->tags[$type][$key];
        }
        return null;
    }

    /**
     * Remove a specific tag.
     *
     * @param string $key
     * @param string $type
     */
    public function forget($key, $type = 'general')
    {
        unset( $this->tags[ $type ][ $key ] );
    }

    /**
     * Converts a DateTime object to a string (ISO 8601)
     *
     * @param string|DateTime $date The date (string or DateTime)
     *
     * @return string
     */
    protected function convertDate( DateTime $date)
    {
        return ( string ) $date->format(DateTime::ISO8601 );
    }

    /**
     * Trim a string to a given number of characters.
     *
     * @param string $string
     * @param int    $limit
     *
     * @return string
     */
    protected function truncate( $string, $limit = 160 )
    {
        // Include the padding character count
        $limit = $limit - 3;

        return strlen( $string ) > $limit
            ? substr( $string, 0, $limit ) . '...'
            : $string;
    }

    /**
     * Get an item of config
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return array|null
     */
    public function config($key, $default = null)
    {
        if ( array_key_exists( $key, $this->config ) ) {
            return $this->config[$key];
        }

        $array = $this->config;

        foreach (explode('.', $key) as $segment) {
            if (array_key_exists($segment, $array)) {
                $array = $array[$segment];
            }
            else {
                return $default;
            }
        }

        return $array;
    }

    /**
     * Build a link
     *
     * @param string $url
     * @param string $rel
     * @param array $attributes
     * @return HtmlElement
     */
    protected function buildLink( $url, $rel, $attributes = [ ] )
    {
        return ( new HtmlElement( 'link' ) )->set( [
            'rel'   =>  $rel,
            'href'  =>  $url
        ] + $attributes );
    }

    /**
     * Builds the tags.
     *
     * @return array
     */
    public function build( )
    {
        $output = [];

        // Add the <meta charset="utf-8" /> tag
        $output[ ] = ( new HtmlElement( 'meta' ) )->set( 'charset', $this->charSet );

        // Render the og:type tag
        if ( $this->includeOg && $this->type ) {
            $output[ ] = ( new HtmlElement( 'meta' ) )->set( [
                'name'      =>  'og:type',
                'property'      =>  'og:type',
                'content'   =>  $this->type,
            ] );
        }

        // Render standard title tag
        if ( isset( $this->tags[ 'general' ][ 'title' ] ) ) {
            $output[ ] = new HtmlElement( 'title', $this->tags[ 'general' ][ 'title' ][ 'value' ] );
        }

        // Render standard description tag
        if ($tag = $this->get('description')) {

            $output[ ] = ( new HtmlElement( 'meta' ) )
                ->set( 'name', 'description' )
                ->set( 'content',
                    $this->truncate(
                        $tag[ 'value' ],
                        $this->config('truncate.description' )
                    )
                );
        }

        // Optionally add the schema.org tags for Google
        // The title is a special case, because it needs to be name
        if ( $this->includeSchemaOrg ) {

            if ($tag = $this->get('title')) {
                $output[] = (new HtmlElement('meta'))->set([
                    'itemprop' => 'name',
                    'content' => $tag['value']
                ] );
            }

            foreach ( [ 'description', 'image' ] as $prop) {
                if ($tag = $this->get($prop)) {
                    $output[] = ( new HtmlElement( 'meta' ) )->set( [
                        'itemprop' => $prop,
                        'content' => $tag['value']
                    ] );
                }
            }
        }

        // Go through the "basic" tags, where the meta tag uses the name attribute rather
        // than property; for example keywords, description
        foreach( $this->config[ 'basic' ] as $name ) {
            if ( $tag = $this->get($name ) ) {
                $output[] = (new HtmlElement('meta'))->set([
                    'name'      => $name,
                    'content'   => $tag[ 'value' ]
                ] );
            }
        }

        // Optionally add the canonical link
        if ( ! empty( $this->canonical ) ) {
            $output[ ] = $this->buildLink( $this->canonical, 'canonical' );
        }

        // Optionally add pagination links
        if ( ! empty( $this->pagination ) ) {
            foreach ( $this->pagination as $direction => $url ) {
                $output[ ] = $this->buildLink( $url, $direction );
            }
        }

        // Optionally link to the web manifest
        if ( ! empty( $this->manifest ) ) {
            $output[ ] = $this->buildLink( $this->manifest, 'manifest' );
        }

        // Render Open Graph tags
        if ( $this->includeOg && count( $this->ogTags ) ) {
            foreach( $this->ogTags as $og ) {
                if ( $limit = $this->config("truncate.og:{$og['name']}" ) ) {
                    $og[ 'value' ] = $this->truncate( $og[ 'value' ], $limit );
                }
                $output[ ] = ( new HtmlElement( 'meta' ) )->set( [
                    'name'      =>  sprintf( 'og:%s', $og[ 'name' ] ),
                    'property'  =>  sprintf( 'og:%s', $og[ 'name' ] ),
                    'content'   =>  $og[ 'value' ],
                ] );
            }
        }

        // Render all twitter tags
        if ( $this->config('twitter', true ) ) {
            foreach( [ 'title', 'description' ] as $name ) {
                if ( $tag = $this->get( $name ) ) {
                    if ( ! isset( $this->tags[ 'twitter' ][ $name ] ) ) {
                        if ( $limit = $this->config(sprintf( 'truncate.twitter:%s', $name ) )) {
                            $tag[ 'value' ] = $this->truncate( $tag[ 'value' ], $limit );
                        }
                        $output[] = ( new HtmlElement( 'meta' ) )->set( [
                            'name'      =>  sprintf( 'twitter:%s', $name ),
                            'content'   =>  $tag[ 'value' ],
                        ] );
                    }
                }
            }

            // Other Twitter specific tags
            foreach ( $this->tags[ 'twitter' ] as $name => $tag ) {
                $output[] = ( new HtmlElement( 'meta' ) )->set( [
                    'name'      =>  sprintf( 'twitter:%s', $name ),
                    'content'   =>  $tag[ 'value' ],
                ] );
            }
        }

        // Render the Googlebot meta tag, if required
        if ( count( $this->googleBotDirections ) ) {
            $output[ ] = ( new HtmlElement( 'meta' ) )->set( [
                'name'      =>  'googlebot',
                'content'   =>  implode( ', ', $this->googleBotDirections )
            ] );
        }

        // Render the http-equiv tags
        if ( count( $this->httpEquiv ) ) {
            foreach( $this->httpEquiv as $h ) {
                $output[ ] = ( new HtmlElement( 'meta' ) )->set( [
                    'http-equiv'        =>  $h[ 'property' ],
                    'content'           =>  $h[ 'value' ]
                ] );
            }
        }

        // Render any custom tags
        if ( count( $this->custom ) ) {
            foreach( $this->custom as $t ) {
                $output[ ] = ( new HtmlElement( 'meta' ) )
                    ->set( $t[ 'attr' ], $t[ 'name' ] )
                    ->set( 'content', $t[ 'value' ] );
            }
        }

        // Add any additional links
        if ( count( $this->links ) ) {
            foreach( $this->links as $link ) {
                $output[ ] = $this->buildLink(
                    $link[ 'url' ],
                    $link[ 'rel' ],
                    $link[ 'attributes' ]
                );
            }
        }

        // Finally, return the array of tags
        return $output;

    }

    /**
     * Render the tags
     *
     * @return string
     */
    public function render( )
    {
        $prefix = $this->tagPrefix;
        $suffix = $this->tagSuffix;

        return implode(
            '',
            array_map(
                function( HtmlElement $el ) use ( $prefix, $suffix ) {
                    return sprintf(
                        '%s%s%s',
                        $prefix,
                        $el->render( ),
                        $suffix
                    );
                },
                $this->build( )
            )
        );
    }

    /**
     * Magic __toString( ) method
     *
     * @return string
     */
    public function __toString()
    {
        return $this->render( );
    }

    /**
     * Set the title
     *
     * @param string $value
     * @return $this
     */
    public function title( $value )
    {
        $this->openGraph( 'title', $value );
        return $this->set( 'title', $value );
    }

    /**
     * Set the description
     *
     * @param string $value
     * @return $this
     */
    public function description( $value )
    {
        $this->openGraph( 'description', $value );
        return $this->set( 'description', $value );
    }

    /**
     * Set the site keywords
     *
     * @param string|array $value
     * @return $this
     */
    public function keywords( ...$values )
    {
        $this->set( 'keywords', implode( ',', func_get_args( ) ) );
        return $this;
    }

    /**
     * Set the character set (charset)
     *
     * @param string $charSet
     * @return $this
     */
    public function charSet( $charSet )
    {
        $this->charSet = $charSet;
        return $this;
    }

    /**
     * Set the URL
     *
     * @param string $url
     * @return $this
     */
    public function url( $url )
    {
        return $this->set( 'url', $url );
    }

    /**
     * Set the Open Graph URL
     *
     * @param string $url
     * @return $this
     */
    public function ogUrl( $url )
    {
        return $this->openGraph( 'url', $url );
    }

    /**
     * Add a <link> element
     *
     * @param string $url
     * @param string $rel
     * @param array $attributes
     * @return $this
     */
    public function addLink( $url, $rel, $attributes = [ ] )
    {
        $this->links[ ] = compact( 'url', 'rel', 'attributes' );
        return $this;
    }

    /**
     * Set the canonical URL
     *
     * @param string $url
     * @return $this
     */
    public function canonical( $url )
    {
        $this->canonical = $url;
        return $this;
    }

    /**
     * Add a link to the first page, if there is pagination.
     *
     * @param string $url
     * @return $this
     */
    public function firstPage( $url )
    {
        $this->pagination[ 'first' ] = $url;
        return $this;
    }

    /**
     * Add a link to the next page, if there is pagination.
     *
     * @param string $url
     * @return $this
     */
    public function nextPage( $url )
    {
        $this->pagination[ 'next' ] = $url;
        return $this;
    }

    /**
     * Add a link to the previous page, if there is pagination.
     *
     * @param string $url
     * @return $this
     */
    public function previousPage( $url )
    {
        $this->pagination[ 'prev' ] = $url;
        return $this;
    }

    /**
     * Add a link to the last page, if there is pagination.
     *
     * @param string $url
     * @return $this
     */
    public function lastPage( $url )
    {
        $this->pagination[ 'last' ] = $url;
        return $this;
    }

    /**
     * Set the manifest
     *
     * @param string $url
     * @return $this
     */
    public function manifest( $url )
    {
        $this->manifest = $url;
        return $this;
    }

    /**
     * Add a feed (e.g. RSS, Atom)
     *
     * @param string $type
     * @param string $url
     * @param string $title
     * @return MetaTags
     */
    public function addFeed( $type, $url, $title = null )
    {
        $attributes = [
            'type'      =>  $type,
        ];
        if ( $title ) {
            $attributes[ 'title' ] = $title;
        }
        return $this->addLink(
            $url,
            'alternate',
            $attributes
        );
    }

    /**
     * Add an RSS feed
     *
     * @param string $url
     * @param string $title
     * @return $this
     */
    public function addRssFeed( $url, $title = null )
    {
        return $this->addFeed( self::FEED_RSS, $url, $title );
    }

    /**
     * Add an atom feed
     *
     * @param string $url
     * @param string $title
     * @return $this
     */
    public function addAtomFeed( $url, $title = null )
    {
        return $this->addFeed( self::FEED_ATOM, $url, $title );
    }

    /**
     * Set the viewport meta tag
     *
     * @param string $value
     * @return $this
     */
    public function viewport( $value )
    {
        return $this->set( 'viewport', $value );
    }

    /**
     * Set the Twitter card meta tag
     *
     * @param string $card
     */
    public function twitterCard( $card = self::TWITTER_CARD_SUMMARY )
    {
        if ( in_array(
            $card,
            [
                self::TWITTER_CARD_SUMMARY,
                self::TWITTER_CARD_SUMMARY_LARGE_IMAGE,
                self::TWITTER_CARD_APP,
                self::TWITTER_CARD_PLAYER,
            ]
        ) ) {
            $this->set( 'twitter_card', $card );
        }
    }

    /**
     * Set the title for Twitter
     *
     * @param string $value
     * @return $this
     */
    public function twitterTitle( $value )
    {
        return $this->set( 'twitter_title', $value );
    }

    /**
     * Set the site for Twitter
     *
     * @param string $value
     * @return $this
     */
    public function twitterSite( $value )
    {
        return $this->set( 'twitter_site', $value );
    }

    /**
     * Set the creator for Twitter
     *
     * @param string $value
     * @return $this
     */
    public function twitterCreator( $value )
    {
        return $this->set( 'twitter_creator', $value );
    }

    /**
     * Set the description for Twitter
     *
     * @param string $value
     * @return $this
     */
    public function twitterDescription( $value )
    {
        return $this->set( 'twitter_description', $value );
    }

    /**
     * Specify that the entity is an article; basically this sets the og:type meta
     * tag to indicate that it's an article.
     *
     * @return $this
     */
    public function isArticle( )
    {
        return $this->type( self::OG_TYPE_ARTICLE );
    }

    /**
     * Specify that the entity is a book; basically this sets the og:type meta
     * tag to indicate that it's a book
     *
     * @return $this
     */
    public function isBook( )
    {
        return $this->type( self::OG_TYPE_BOOK );
    }

    /**
     * Specify that the entity is a profile; basically this sets the og:type meta
     * tag to indicate that it's a profile.
     *
     * @return $this
     */
    public function isProfile( )
    {
        return $this->type( self::OG_TYPE_PROFILE );
    }

    /**
     * Specify that the entity is a business; basically this sets the og:type meta
     * tag to indicate that it's a business.
     *
     * @return $this
     */
    public function isBusiness( )
    {
        return $this->type( self::OG_TYPE_BUSINESS );
    }

    /**
     * Tell robots to index, but NOT follow. This is the equivalent of:
     *
     * <meta name="robots" content="index, nofollow">
     *
     * @return $this
     */
    public function robotsShouldIndexButNotFollow( )
    {
        $this->googleBotDirections[ ] = 'index';
        $this->googleBotDirections[ ] = 'nofollow';
        return $this->set( 'robots', 'index, nofollow' );
    }

    /**
     * Tell robots to follow, but NOT to index. This is the equivalent of:
     *
     * <meta name="robots" content="index, nofollow">
     *
     * @return $this
     */
    public function robotsShouldFollowButNotIndex( )
    {
        $this->googleBotDirections[ ] = 'noindex';
        $this->googleBotDirections[ ] = 'follow';
        return $this->set( 'robots', 'noindex, follow' );
    }

    /**
     * Tell robots NOT to index, NOR to follow. This is the equivalent of:
     *
     * <meta name="robots" content="noindex, nofollow">
     *
     * @return $this
     */
    public function robotsShouldNotIndexNorFollow( )
    {
        $this->googleBotDirections[ ] = 'noindex';
        $this->googleBotDirections[ ] = 'nofollow';
        return $this->set( 'robots', 'noindex, nofollow', [ ] );
    }

    /**
     * Prevents a text snippet or video preview from being shown in the search results.
     * For video, a static image will be shown instead, if possible.
     *
     * @return $this
     */
    public function googleShouldNotIncludeSnippets( )
    {
        $this->googleBotDirections[ ] = 'nosnippet';
        return $this;
    }

    /**
     * Prevents Google from showing the Cached link for a page.
     *
     * @return $this
     */
    public function googleShouldNotShowCachedLinks( )
    {
        $this->googleBotDirections[ ] = 'noarchive';
        return $this;
    }

    /**
     * Lets you specify that you do not want your page to appear as the referring page for an image
     * that appears in Google search results.
     *
     * @return $this
     */
    public function googleShouldNotShowPageAsReferringPageForImageSearchResults( )
    {
        $this->googleBotDirections[ ] = 'nopageindex';
        return $this;
    }

    /**
     * Tell Google(bots) to stop crawling the page after the specified date.
     *
     * @param \DateTime $date
     * @return $this
     */
    public function googleShouldStopCrawlingAfter( \DateTime $date )
    {
        $this->googleBotDirections[ ] = sprintf(
            'unavailable_after:%s',
             $this->convertDate( $date )
        );
        return $this;
    }

    /**
     * Set an http-equiv meta tag
     *
     * e.g.
     * <meta http-equiv="Expires" content="0">
     * <meta http-equiv="Pragma" content="no-cache">
     * <meta http-equiv="Cache-Control" content="no-cache">
     *
     * @param string $property
     * @param mixed $value
     * @return $this
     */
    public function httpEquiv( $property, $value )
    {
        // This form of storage allows the same property to have multiple values;
        // see tellBrowsersNotToCache( )
        $this->httpEquiv[ ] = [
            'property'  =>  $property,
            'value'     =>  $value
        ];
        return $this;
    }

    /**
     * Set the content type.
     *
     * @param string $value
     * @return $this
     */
    public function contentType( $value )
    {
        return $this->httpEquiv( 'content-type', $value );
    }

    /**
     * Tell browsers not to cache the page.
     *
     * See the following page for an explanation of the way this works:
     * http://www.standardista.com/html5/http-equiv-the-meta-attribute-explained/
     *
     * <meta http-equiv="pragma" content="no-cache" />
     * <meta http-equiv="cache-control" content="max-age=0" />
     * <meta http-equiv="cache-control" content="no-cache" />
     * <meta http-equiv="cache-control" content="no-store" />
     *
     * @return $this;
     */
    public function tellBrowsersNotToCache( )
    {
        $this->httpEquiv( 'pragma', 'no-cache' );
        $this->httpEquiv( 'cache-control', 'max-age=0' );
        $this->httpEquiv( 'cache-control', 'no-cache' );
        $this->httpEquiv( 'cache-control', 'no-store' );
        return $this;
    }

    /**
     * Set a custom meta tag
     *
     * $attr is the name of the attribute to use; i.e. name (most common) or property
     *
     * e.g.
     * ->custom( 'mixpanel', 'your-project-id' )
     *
     * @param $name
     * @param $value
     * @param string $attr
     * @return $this
     */
    public function custom( $name, $value, $attr = 'name' )
    {
        $this->custom[ ] = [
            'name'      =>  $name,
            'value'     =>  $value,
            'attr'      =>  $attr,
        ];
        return $this;
    }

    /**
     * Specify whether to include Schema.org meta tags.
     *
     * IMPORTANT:
     * If you enable this, then you need to add an itemtype attribute to the <html> tag, e.g.:
     *
     * <html itemscope itemtype="http://schema.org/WebPage">
     *
     * @param bool $value
     * @return $this
     */
    public function includeSchemaOrg( $value = true )
    {
        $this->includeSchemaOrg = $value;
        return $this;
    }

    /**
     * Specify whether to include Open Graph tags.
     *
     * @param bool $include
     * @return $this
     */
    public function includeOpenGraph( $include = true )
    {
        $this->includeOg = $include;
        return $this;
    }

    /**
     * Set one or more Open Graph tags.
     *
     * @param string|array $name
     * @param mixed $value
     * @return $this
     */
    public function openGraph( $name, $value = null )
    {
        if ( is_array( $name ) ) {
            foreach( $name as $key => $value ) {
                $this->openGraph( $key, $value );
            }
        } else {

            if ( is_a( $value, 'DateTime' ) ) {
                $value = ( string ) $value->format( \DateTime::ISO8601 );
            }

            $this->set( $name, $value );
            $this->ogTags[ ] = [
                'name'      =>  $name,
                'value'     =>  $value,
            ];
        }
        return $this;
    }

    /**
     * Set the locale Open Graph meta tag
     *
     * @param string $locale
     * @return $this
     */
    public function locale( $locale )
    {
        return $this->openGraph( 'locale', $locale );
    }

    /**
     * Set the site name
     *
     * @param string $name
     * @return $this
     */
    public function siteName( $name )
    {
        return $this->openGraph( 'site_name', $name );
    }

    /**
     * Add a geopoint (lat/lng)
     *
     * @param Geopoint $point
     * @return $this
     */
    public function addGeopoint( Geopoint $point )
    {
        $this->openGraph( 'latitude', $point->getLatitude( ) );
        $this->openGraph( 'longitude', $point->getLongitude( ) );
        if ( $point->getAltitude( ) ) {
            $this->openGraph( 'altitude', $point->getAltitude( ) );
        }
        return $this;
    }

    /**
     * Set the street address Open Graph meta tag.
     *
     * @param string $address
     * @return $this
     */
    public function streetAddress( $address )
    {
        return $this->openGraph( 'street-address', $address );
    }

    /**
     * Set the locality Open Graph meta tag.
     *
     * @param string $locality
     * @return $this
     */
    public function locality( $locality )
    {
        return $this->openGraph( 'locality', $locality );
    }

    /**
     * Set the region Open Graph meta tag.
     *
     * @param string $region
     * @return $this
     */
    public function region( $region )
    {
        return $this->openGraph( 'region', $region );
    }

    /**
     * Set the postal code Open Graph meta tag.
     *
     * @param string $postalCode
     * @return $this
     */
    public function postalCode( $postalCode )
    {
        return $this->openGraph( 'postal-code', $postalCode );
    }

    /**
     * Set the country (name) Open Graph meta tag.
     *
     * @param string $country
     * @return $this
     */
    public function country( $country )
    {
        return $this->openGraph( 'country-name', $country );
    }

    /**
     * Set some contact data
     *
     * @param ContactData $data
     * @return $this
     */
    public function contactData( ContactData $data )
    {
        $this->streetAddress( $data->getStreetAddress( ) );
        $this->locality( $data->getLocality( ) );
        $this->region( $data->getRegion( ) );
        $this->postalCode( $data->getPostalCode( ) );
        $this->country( $data->getCountryName( ) );
        $this->email( $data->getEmail( ) );
        $this->phone( $data->getPhone( ) );
        $this->fax( $data->getFaxNumber( ) );
        $this->website( $data->getWebsite( ) );
        return $this;
    }

    /**
     * Set the email Open Graph meta tag
     *
     * @param string $email
     * @return MetaTags
     */
    public function email( $email )
    {
        return $this->openGraph( 'email', $email );
    }

    /**
     * Set the phone Open Graph meta tag
     *
     * @param string $phone
     * @return MetaTags
     */
    public function phone( $phone )
    {
        return $this->openGraph( 'phone_number', $phone );
    }

    /**
     * Set the fax Open Graph meta tag
     *
     * @param string $fax
     * @return MetaTags
     */
    public function fax( $fax )
    {
        return $this->openGraph( 'fax_number', $fax );
    }

    /**
     * Set the website Open Graph meta tag
     *
     * @param string $website
     * @return MetaTags
     */
    public function website( $website )
    {
        return $this->openGraph( 'website', $website );
    }

    /**
     * Specify a business' hours
     *
     * @return $this
     */
    public function businessHours( ...$values )
    {
        foreach( $values as $day ) {
            $this->addBusinessHoursForDay( $day );
        }
        return $this;
    }

    /**
     * Specify a business' hours for a given day
     *
     * @param BusinessDay $businessDay
     * @return $this
     */
    public function addBusinessHoursForDay( BusinessDay $businessDay )
    {
        $this->openGraph( 'business:hours:day',     $businessDay->getDay( ) );
        $this->openGraph( 'business:hours:start',   $businessDay->getStart( ) );
        $this->openGraph( 'business:hours:end',     $businessDay->getEnd( ) );
        return $this;
    }

    /**
     * Add an image to the Open Graph meta tags
     *
     * @param Image $image
     * @return $this
     */
    public function addImage( Image $image )
    {
        $this->openGraph( 'image',              $image->getUrl( ) );
        $this->openGraph( 'image:secure_url',   $image->getSecureUrl( ) );
        $this->openGraph( 'image:type',         $image->getType( ) );
        $this->openGraph( 'image:width',        $image->getWidth( ) );
        $this->openGraph( 'image:height',       $image->getHeight( ) );
        $this->openGraph( 'image:alt',          $image->getAlt( ) );
        return $this;
    }

    /**
     * Add a video to the Open Graph meta tags
     *
     * @param Video $video
     * @return $this
     */
    public function addVideo( Video $video )
    {
        $this->openGraph( 'video',              $video->getUrl( ) );
        $this->openGraph( 'video:secure_url',   $video->getSecureUrl( ) );
        $this->openGraph( 'video:type',         $video->getType( ) );
        $this->openGraph( 'video:width',        $video->getWidth( ) );
        $this->openGraph( 'video:height',       $video->getHeight( ) );
        $this->openGraph( 'video:image',        $video->getImage( ) );
        return $this;
    }

    /**
     * Add an audio item to the Open Graph meta tags
     *
     * @param Audio $audio
     * @return $this
     */
    public function addAudio( Audio $audio )
    {
        $this->openGraph( 'audio',              $audio->getUrl( ) );
        $this->openGraph( 'audio:secure_url',   $audio->getSecureUrl( ) );
        $this->openGraph( 'audio:type',         $audio->getType( ) );
        return $this;
    }

    /**
     * Add an article item to the Open Graph meta tags
     *
     * @param Article $article
     * @return $this
     */
    public function addArticle( Article $article )
    {
        if ( $article->getPublishedTime( ) ) {
            $this->openGraph(
                'article:published_time',
                $article->getPublishedTime( )->format( DateTime::ATOM )
            );
        }
        if ( $article->getModifiedTime( ) ) {
            $this->openGraph(
                'article:modified_time',
                $article->getModifiedTime( )->format( DateTime::ATOM )
            );
        }
        if ( $article->getExpirationTime( ) ) {
            $this->openGraph(
                'article:expiration_time',
                $article->getExpirationTime( )->format( DateTime::ATOM )
            );
        }
        if ( $article->getAuthor( ) && count( $article->getAuthor( ) ) ) {
            foreach( $article->getAuthor( ) as $author ) {
                $this->openGraph( 'article:author', $author );
            }
        }
        $this->openGraph( 'article:section', $article->getSection( ) );
        if ( $article->getTag( ) && count( $article->getTag( ) ) ) {
            foreach( $article->getTag( ) as $tag ) {
                $this->openGraph( 'article:tag', $tag );
            }
        }
        return $this;
    }

    /**
     * Set profile information
     *
     * @param Profile $profile
     * @return $this
     */
    public function profile( Profile $profile )
    {
        $this->isProfile( );
        $this->openGraph( 'profile:first_name',     $profile->getFirstName( ) );
        $this->openGraph( 'profile:last_name',      $profile->getLastName( ) );
        $this->openGraph( 'profile:username',       $profile->getUsername( ) );
        $this->openGraph( 'profile:gender',         $profile->getGender( ) );
        return $this;
    }

    /**
     * Set book information
     *
     * @param Book $book
     * @return $this
     */
    public function book( Book $book )
    {
        // First, set the type accordingly
        $this->type( self::OG_TYPE_BOOK );

        if ( $book->getAuthor( ) && count( $book->getAuthor( ) ) ) {
            foreach( $book->getAuthor( ) as $author ) {
                $this->openGraph( 'book:author', $author );
            }
        }

        $this->openGraph( 'book:isbn', $book->getIsbn( ) );

        if ( $book->getReleaseDate( ) ) {
            $this->openGraph(
                'book:release_date',
                $book->getReleaseDate( )->format( DateTime::ATOM )
            );
        }

        if ( $book->getTag( ) && count( $book->getTag( ) ) ) {
            foreach( $book->getTag( ) as $tag ) {
                $this->openGraph( 'book:tag', $tag );
            }
        }
        return $this;
    }

    /**
     * Set the Facebook app ID of the site's app.
     *
     * @param string $appId
     * @return $this
     */
    public function facebookAppId( $appId )
    {
        return $this->custom( 'fb:app_id', $appId, 'property' );
    }

    /**
     * Set the Facebook profile ID; that's to say, the Facebook ID of a user
     * that can be followed.
     *
     * @param string $profileId
     * @return $this
     */
    public function facebookProfileId( $profileId )
    {
        return $this->custom( 'fb:profile_id', $profileId, 'property' );
    }

    /**
     * Set one or more Facebook Page IDs that are associated with a URL in order
     * to enable link editing and instant article publishing.
     *
     * @param array $pages
     * @return $this
     */
    public function facebookPages( ...$pages )
    {
        return $this->custom( 'fb:pages', implode( ', ', $pages ), 'property' );
    }

    /**
     * The ID (or comma-separated list for properties that can accept multiple IDs)
     * of an app, person using the app, or Page Graph API object.
     *
     * @param array $admins
     * @return $this
     */
    public function facebookAdmins( ...$admins )
    {
        return $this->custom( 'fb:admins', implode( ', ', $admins ), 'property' );
    }

    /**
     * Sets the tag prefix. For example, you may wish to set this to a tab to help
     * keep your markup hierarchical.
     *
     * @param string $prefix
     * @return $this
     */
    public function tagPrefix( $prefix )
    {
        $this->tagPrefix = $prefix;
        return $this;
    }

    /**
     * Sets the tag suffix; for example, a newline.
     *
     * @param string $suffix
     * @return $this
     */
    public function tagSuffix( $suffix )
    {
        $this->tagSuffix = $suffix;
        return $this;
    }

    /**
     * Indicate that we want verbose output; e.g. no newlines.
     *
     * @return $this
     */
    public function verbose( )
    {
        $this->tagPrefix = '';
        $this->tagSuffix = '';
        return $this;
    }

}