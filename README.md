# Meta Tags

This library helps you manage meta tags (including Open Graph) for your website or web application.

Chances are, many of your meta tags are generated programmatically, for example from a database. Most notably the page title, but perhaps also the description and keywords.

Perhaps you make these available as data to your views, then build the tags there. What this library does is allow you to build them inside of your application (in a controller, for example), then output them in your views really easily.

So, instead of something like this (I'm using Blade syntax here):

```html
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<title>{{ $title }}</title>
		<meta name="og:title" content="{{ $title }}" />
		<meta name="description" content="{{ $description }}" />
		<meta name="og:description" content="{{ $description }}" />
		<meta name="keywords" content="{{ implode( ', ', $keywords ) }}" />
		<meta name="robots" content="index,nofollow" />
		<link rel="canonical" content="{{ $url }}" />
	</head>
```

This library allows you to do this:

```php
$meta = new MetaTags( );
$meta
	->charSet( 'utf-8' )
	->title( $title )
	->description( $description )
	->keywords( $keywords )
	->robotsShouldIndexButNotFollow( )
	->canonical( $url );
```

> In fact, if you're using UTF-8 then it's the default anyway, so that `->charSet( )` call is redundant.

Then in your view:

```php
<!DOCTYPE html>
<html lang="en">
	<head>
		{!! $meta !!}
	</head>
```	

If you're using Laravel, you could do something like this:

```php
{!! app( )->make( MetaTags::class )->render( ) !!}
```

> In the example above, we've registered the `MetaTags` class as singleton with the service container. More on that later.

So what are the benefits?

* A maximum of one variable to pass to your views
* No hand-coding the tags themselves
* No confusion as to when to use `name` vs `property` attributes
* It makes it harder to forget things like setting the character set
* Set the title and description, and the corresponding Open Graph meta tags are set for you
* It (hopefully!) helps as a reminder for important, but often overlooked things like canonicalization
* It exposes some of the lesser-known meta tags, like the one aimed directly at Googlebots
* It keeps your layouts nice and tidy

## Installation

```
composer require lukaswhite/meta-tags
```

## First Steps

Create an instance as follows:

```php
use Lukaswhite\MetaTags\MetaTags;

$meta = new MetaTags( );
```

I'd recommend creating it as a singleton, then popping it in a service container or similar.

For example, if you're using Laravel:

```php
$this->app->singleton( MetaTags::class, function( $app ) {
	return new MetaTags( );
} );
```

Then, whenever you want to set your meta tags, for example in a controller, then you can get the instance like this:

```php
$meta = app( )->make( MetaTags::class );
```

## Standard Metadata

### Setting the Title

```php
$meta->title( 'My Website' );
```

By default, this will add the following:

```html
<title>My Website</title>
<meta property="og:title" content="My Website" />
```

You'll notice that it's added an Open Graph tag with the title by default; if you don't want that behavior:

```php
$meta->includeOpenGraph( false )
	->title( 'My Website' );
```	

The result:

```html
<title>My Website</title>
```

### Setting the Description

```php
$meta->description( 'A description of the website' );
```

By default, this will add the following:

```html
<meta name="description" content="A description of the website" />
<meta property="og:description" content="A description of the website" />
```

You'll notice that it's added an Open Graph tag with the description by default; if you don't want that behavior:

```php
$meta->includeOpenGraph( false )
	->description( 'A description of the website' );
```

The result:

```php
<meta name="description" content="A description of the website" />
```

### Setting the Keywords

```php
$meta->keywords( 'PHP', 'meta tags', 'SEO' );
```

The result:

```html
<meta name="keywords" content="PHP, meta tags, SEO" />
```


### Setting the URL

Set the url like this:

```php
$meta->url( 'http://example.com' );
```

This will output the following:

```html
<meta name="url" content="http://example.com" />
```

## Setting the Character Set

The library will automatically add the character set to the meta tags, defaulting to UTF-8. So this is added automatically:

```html
<meta charset="utf-8" />
```

To set the character set to something different:

```php
$meta->charSet( 'ISO-8859-1' );
```

The result:

```php
<meta charset="ISO-8859-1" />
```

Should, for any reason, you not wish this to be added then you can simply set it to `null`:

```php
$meta->charSet( null );
```

## Canonical URLs

To set the canonical link meta tag:

```php
$meta->canonical( 'http://example.com' );
```

This results in the following:

```php
<link rel="canonical" content="http://example.com" />
```

## Pagination

If you're rendering a page that lists something and it uses pagination, then it can be helpful to add links to the next and / or previous pages as links. That's to say, a `link` tag with `rel=prev` or `rel=next`.

Suppose we're currently on page four:

```php
$meta->previousPage( 'http://example.com/articles/page/3' )
	->nextPage( 'http://example.com/articles/page/5' );
```

You can also set the first and last pages:

```php
$meta
	->firstPage( 'http://example.com/articles/page/1' )
	->lastPage( 'http://example.com/articles/page/10' );
```

## Feeds

### RSS

If you have an RSS feed associated with a website or category, it's a good idea to create a link to it.

Without a title:

```php
$meta->addRssFeed( 'http://example.com/feed.rss' );
```

Or with:

```php
$meta->addRssFeed( 'http://example.com/feed.rss', 'My Website RSS Feed' );
```

### Atom

If you have an Atom feed associated with a website or category, it's a good idea to create a link to it.

Without a title:

```php
$meta->addAtomFeed( 'http://example.com/feed.xml' );
```

Or with:

```php
$meta->addAtomFeed( 'http://example.com/feed.xml', 'My Website Atom Feed' );
```


## Robots

By default robots will index and follow; if you want to tell them not to do one or both you can use one of the following methods:

```php
$meta->robotsShouldIndexButNotFollow( );
// or
$meta->robotsShouldFollowButNotIndex( );
// or
$meta->robotsShouldNotIndexNorFollow( );
```

### Googlebot

There are a couple of additional requests you can make from the Googlebot, in addition to the `noindex` or `nofollow` clauses.

To prevent a text snippet or video preview from being shown in the search results:

```php
$meta->googleShouldNotIncludeSnippets( );
```

To prevent Google from showing the Cached link for a page:

```php
$meta->googleShouldNotIncludeSnippets( );
```

To specify that you do not want your page to appear as the referring page for an image that appears in Google search results:


```php
$meta->googleShouldNotShowPageAsReferringPageForImageSearchResults( );
```

> Apologies for the ridiculously long method name, better suggestions welcome!

To request that Google stop crawling a page after a specified date:

```php
$meta->googleShouldStopCrawlingAfter( new \DateTime( '2019-10-23' ) );
```

## http-equiv Meta Tags

There's a catgeory of meta tags that look like this:

```html
<meta http-equiv="Pragma" content="no-cache">
```

They're essentially the HTML equivalent of HTTP headers.

To add one:

```php
$meta->httpEquiv( 'Pragma', 'no-cache' );
```

To tell browsers that you don't want a page to be cached, you can either set the relevant tags manually, or you can do this:

```php
$meta->tellBrowsersNotToCache( );
```

As per the advice on [this page](http://www.standardista.com/html5/http-equiv-the-meta-attribute-explained/), since not all all client browsers and caching devices (e.g. proxy servers) are known to successfully implement all no-caching options, this method includes multiple no-caching options; specfically it adds the following meta tags:

```html
<meta http-equiv="pragma" content="no-cache" />
<meta http-equiv="cache-control" content="max-age=0" />
<meta http-equiv="cache-control" content="no-cache" />
<meta http-equiv="cache-control" content="no-store" />
```

## Custom Meta Tags

Custom meta tags can be useful for injecting data into a page that you can later access via JavaScript. Laravel does this for CSRF tokens, for example, or you may wish to use this approach for injecting an application ID or public key. 

Here are some examples:

```php
$meta->custom( 'google-maps', 'your-google-maps-key' );
$meta->custom( 'mixpanel', getenv( 'MIXPANEL_APP_ID' ) );
$meta->custom( 'csrf-token', csrf_token( ), 'name' );
```

Note that last one; Laravel outputs the CSRF meta tag like this:

```html
<meta name="csrf-token" content="the-token" />
```

Whereas the Google Maps example above will use the more common `property` attribute, i.e.:

```html
<meta property="google-maps" content="your-google-maps-key" />
```

## Open Graph Tags

The library makes adding Open Graph meta tags easy.

### Enabling or Disabling

Open Graph tags are enabled by default; that includes some which get added automatically. If you wish to disable this:

```php
$meta->includeOpenGraph( false );
```

### Basic Tags

There are some basic &mdash; and very important &mdash; Open Graph tags that have "standard" equivalents. Notably the title, description and URL. These get automatically added as Open Graph tags in addition to "standard" tags when you specify them with the corresponding methods.

In other words, if you do this:

```php
$meta
	->title( 'My Website' )
	->description( 'A description of the website' )
	->url( 'http://example.com' );
```

You'll get the following:

```html
<title>My Website</title>
<meta property="og:title" content="My Website" />
<meta name="description" content="A description of the website" />
<meta property="og:description" content="A description of the website" />
<meta name="url" content="http://example.com" />
<meta property="og:url" content="http://example.com" />
```

### Defining the Type

By default the required `og:type` meta tag is added, which by default is a website. I.e.:

```
<meta property="og:type" content="website" />
```

You can override this with the `type( )` method, for example:

```php
$meta->type( 'article' );
```

You can also use one of the provided constants:

```php
$meta->type( MetaTags::OG_TYPE_ARTICLE );
```

You can also set any attributes at the same time, for example:

```php
$meta->type(
	MetaTags::OG_TYPE_BOOK,
	[
		'isbn'  =>  'ISBN-123-1234',
	]
);

// or
$meta->type(
	MetaTags::OG_TYPE_PROFILE,
	[
		'first_name'  =>  'Joe',
		'last_name' => 'Bloggs',
		'username' => 'joebloggs',
	]
);
```

> Passing an array of attributes in this way isn't the preferred way to do this; we'll look at other ways later.

Or, call one of the following methods:

```php
$meta->isArticle( );
$meta->isBook( );
$meta->isProfile( );
$meta->isBusiness( );
```

### Setting the Locale

To set the locale, for example for localization [with Facebook](https://developers.facebook.com/docs/internationalization#locales):

```php
$meta->locale( 'en_GB' );
```

### Setting the URL

To set the Open Graph URL &mdash; in other words, `og:url` &mdash; simply do this:

```php
$meta->ogUrl( 'https://example.com' );
```

### Other Open Graph Tags

You can set any Open Graph tag using the `openGraph( )` method:

```php
$meta->openGraph( 'determiner', 'an' );
$meta->openGraph( 'updated_time', new \DateTime( '2018-10-12' ) );
$meta->openGraph( 'restrictions:age', 18 );
```

### Images

Images are important for social networks, for example when your site is shared via Facebook, or used as the basis of a Twitter Card (more on those later).

To add an image:

```php
$meta->addImage(
	( new \Lukaswhite\MetaTags\Entities\Image( ) )
		->setUrl( 'http://example.com/image.jpeg' )
		->setSecureUrl( 'https://example.com/image.jpeg' )
		->setType( 'image/jpeg' )
		->setWidth( 500 )
		->setHeight( 300 )
		->setAlt( 'Example image' )
);
```

If you'd rather use your own image class, simply implement the `Image` contract included in this package.

> It's named `addImage( )` rather than, say, `setImage( )` because the Open Graph protocol allows you to add multiple images.

### Videos

To add a video:

```php
$meta->addVideo(
	( new \Lukaswhite\MetaTags\Entities\Video( ) )
		->setUrl( 'http://example.com/movie.swf' )
		->setSecureUrl( 'https://example.com/movie.swf' )
		->setType( 'application/x-shockwave-flash' )
		->setWidth( 500 )
		->setHeight( 300 )
		->setImage( 'http://example.com/movie-image.jpg' )
);
```

If you'd rather use your own image class, simply implement the `Video` contract included in this package.

> It's named `addVideo( )` rather than, say, `setVideo( )` because the Open Graph protocol allows you to add multiple videos.

### Audio

To add audio:

```php
$meta->addAudio(
	( new \Lukaswhite\MetaTags\Entities\Audio( ) )
		->setUrl( 'http://example.com/audio.mp3' )
		->setSecureUrl( 'https://example.com/audio.mp3' )
		->setType( 'audio/mp3' )
);
```

If you'd rather use your own image class, simply implement the `Video` contract included in this package.

### Profiles

If a web page represents a user profile, for example in a social network, then you can do this:

```php
$meta->profile( 
	( new \Lukaswhite\MetaTags\Entities\Profile( ) )
		->setFirstName( 'Joe' )
		->setLastName( 'Bloggs' )
		->setUsername( 'joebloggs' )
		->setGender( \Lukaswhite\MetaTags\Contracts\Profile::MALE ) );
```
            
If you'd rather use your own profile class, simply implement the `Profile` contract included in this package.

### Books

If a web page represents a book:

```php
$meta->book(
	( new \Lukaswhite\MetaTags\Entities\Book( ) )
		->setReleaseDate( new \DateTime( '2018-04-03 15:20' ) )
		->setAuthor( 'Joe Bloggs' )
		->setIsbn( 'ISBN-123-1234' )
		->setTag( [ 'PHP' ] )
);
```
            
If you'd rather use your own book class, simply implement the `Book` contract included in this package.

Note that since a book may have multiple authors, this is also permitted:

```php
$book->setAuthor( [ 'Joe Bloggs', 'Harry Black' ] );
```


### Contact Data

You can add contact data such as an address, e-mail address or telephone number to the Open Graph meta tags; this might be appropriate for a business listing site, for example.

Here's one way to do it:

```php
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
```

This will add the following meta tags:

```html
<meta name="og:street-address" content="1601 S California Ave" />
<meta name="og:locality" content="Palo Alto" />
<meta name="og:region" content="CA" />
<meta name="og:postal-code" content="94304" />
<meta name="og:country-name" content="USA" />
<meta name="og:email" content="me@example.com" />
<meta name="og:phone_number" content="650-123-4567" />
<meta name="og:fax_number" content="http://example.com" />
```

Alternatively, you can use your own class by implementing the `ContactData` interface:

```php
class Business implements ContactData {
	// getStreetAddress( ), getPostalCode( ) etc
}

$meta->contactData( $business );
```

Finally, there are also individual methods:

```php
$meta->streetAddress( '1601 S California Ave' )
	->locality( 'Palo Alto' )
	->region( 'CA' )
	->postalCode( '94304' )
	->countryName( 'USA' )
	->email( 'me@example.com' )
	->phone('650-123-4567')
	->faxNumber('+1-415-123-4567')
	->website( 'http://example.com' )
);
```


### Geographical Information

In addition to address information via contact data, Open Graph also allows you to specify the specific geographical location a page represents by setting the latitude and longitude, and optionally the altitude.

```php
$meta->addGeopoint( 
	new Geopoint( 37.416343, -122.153013 ) 
);
```

The result:


```html
<meta name="og:latitude" content="37.416343" />
<meta name="og:longitude" content="-122.153013" />
```

Rather than pass an instance of the provided `Geopoint` class, you can instead pass any class that implements the interface with the same name. Essentially this entails providing `getLatitude( )`, `getLatitude( )` and `getAltitude( )` methods.

### Business Opening Hours

To specify a business' opening hours:

```php
$meta->businessHours(
	new BusinessDay( 'Monday', '09:00', '17:00' ),
	new BusinessDay( 'Tuesday', '09:00', '17:00' ),
	new BusinessDay( 'Wednesday', '09:00', '17:00' ),
	new BusinessDay( 'Thursday', '09:00', '17:00' ),
	new BusinessDay( 'Friday', '09:00', '17:00' ),
	new BusinessDay( 'Saturday', '08:00', '18:00' )
);
```        

## Facebook

Facebook has a few Open Graph tags that are specific to the social network.

### The App ID

To set the app ID of the site's app:

```php
$meta->facebookAppId( '12345678910' );
```

### The Profile IDs

To set the Facebook profile ID; that's to say, the Facebook ID of a user
that can be followed:

```php
$meta->facebookProfileId( '78676576576' );
```

### Admins

To set the admins:

```php
$meta->facebookAdmins(
	'12345',
	'678910'
);

// or
$meta->facebookAdmins(
	'12345'
);
```

### Pages

To set one or more Facebook Page IDs that are associated with a URL in order
to enable link editing and instant article publishing:

```php
$meta->facebookPages(
	'76236783',
	'67873687'
);

// or
$meta->facebookPages(
	'76236783'
);
```

## Twitter Cards

For Twitter cards, the Open Graph tags will generally suffice (previously Twitter used proprietary tags such as `twitter:title`, but these are now largely redundant), although you'll also want to call this:

```php
$meta->twitterCard( );
```

This will add the following:

```php
<meta name="twitter:card" content="summary" />
```

For a different type, just pass it as an argument:

```php
$meta->twitterCard( MetaTags::TWITTER_CARD_SUMMARY_LARGE_IMAGE );
$meta->twitterCard( MetaTags::TWITTER_CARD_APP );
$meta->twitterCard( MetaTags::TWITTER_CARD_PLAYER );
```

You can also set the site and / or creator:

```php
$meta
	->twitterSite( '@lukaswhite' )
	->twitterCreator( '@lukaswhite' );
```

The metadata &mdash; for example, the name and description &mdash; along with any media such as images will be fetched from the Open Graph tags.

You can, however, override these; if for any reason you want different metadata for Twitter:

```php
$meta
	->twitterTitle( 'My Twitter' )
	->twitterDescription( 'A Twitter description' );
```

This will set `twitter:title` and `twitter:description` metatags respectively.

Here's a complete example:

```php
$meta
	->title( 'My Website' )
	->description( 'A website' )
	->twitterCard( )
	->twitterSite( '@lukaswhite' )
	->twitterCreator( '@lukaswhite' );
	->addImage(
	( new \Lukaswhite\MetaTags\Entities\Image( ) )
		->setUrl( 'http://example.com/image.jpeg' )
		->setSecureUrl( 'https://example.com/image.jpeg' )
		->setType( 'image/jpeg' )
		->setWidth( 500 )
		->setHeight( 300 )
		->setAlt( 'Example image' )
);
```

## Examples (Laravel-flavored)

These simplified examples use Laravel, but you can use this library using any framework - or indeed without a framework at all.

### Registering as a Singleton

One approach you could take if you're using Laravel is to register the meta tags as a singleton; that way you can also start setting your meta tags during the bootstrap phase:

```php
$this->app->singleton( MetaTags::class, function( $app ) {
	$meta = new MetaTags( );
	$meta->siteName( array_get( $app[ 'config' ], 'app.name' ) );
	return $meta;
} );
```

> You can of course use a similar approach without Laravel, though the syntax will obviously be different.

Then, whenever you want to set your meta tags, for example in a controller, then you can get the instance like this:

```php
$meta = app( )->make( MetaTags::class );
```

Because it's a singleton, you can build your meta tags from multiple parts of your application.

### A Content Management System

This is a really basic example; it's pulling a page from the database, and setting the page title and page description from that.

```php
class PagesController {

	public function show( Page $page )
	{
		app( )->make( MetaTags::class )
			->title( $page->title )
			->description( $page->description );
			
		// The rest of the method	
	}

}
```

### A Restaurant Listing Site

In this example, the controller is pulling a restaurant record from the database in order to display it. It's using the meta tags library to match the title and description to the restaurant.

In addition, it's setting the Open Graph tags for the address of the restaurant, as well as pointing it to the geographical location.


```php
class RestaurantController {

	public function show( Restaurant $restaurant )
	{
		app( )->make( MetaTags::class )
			->isBusiness( )
			->title( $restaurant->name )
			->description( $restaurant->description )
			->addGeopoint( 
				new Geopoint(
					$restaurant->lat, $restaurant->lng
				)
			)
			->streetAddress( $restaurant->street_address )
			->locality( $restaurant->locality )
			->region( $restaurant->locality )
			->postalCode( $restaurant->postal_code )
			->country( $restaurant->country )
			->phone( $restaurant->phone );
			
		// The rest of the method	
	}

}
```

Alternatively, if the `Restaurant` class implements the `ContactData` and `Geopoint` interfaces, you can simplify the code above significantly:

```php
class RestaurantController {

	public function show( Restaurant $restaurant )
	{
		app( )->make( MetaTags::class )
			->isBusiness( )
			->title( $restaurant->name )
			->description( $restaurant->description )
			->addGeopoint( $restaurant )
			->contactData( $restaurant );
			
		// The rest of the method	
	}

}
```

### A Social Network

This example demonstrates a simple example whereby this particular controller display's a user's profile on a social network, setting the appropriate Open Graph tags.

```php

class User extends Model implements Profile {
	
	public class getFirstName( )
	{
		return $this->attributes[ 'first_name' ];
	}

	public class getLastName( )
	{
		return $this->attributes[ 'last_name' ];
	}

	public class getUsername( )
	{
		return $this->attributes[ 'username' ];
	}

	public class getGender( )
	{
		return $this->attributes[ 'gender' ];
	}

}

class ProfileController {

	public function show( User $user )
	{
		app( )->make( MetaTags::class )
			->profile( $user );
			
		// The rest of the method	
	}

}
```

## Outputting the Tags

To render the tags, just call the `render( )` method, for example:

```php
<html>
	<head>
		<?php print $meta->render( ); ?>
	</head>
```	

Or using Blade:

```php
<html>
	<head>
		{!! $meta->render( ) !!}
	</head>
```	

The class also implements the `__toString( )` method, so you can even `print` it right out. It simply calls the `render( )` method.

### Customizing the Output

By default, the meta tag output includes newlines. You can turn this off using the `verbose( )` method.

If you want even more control over the resulting string, you can set the tag prefix and tag suffix. For example, perhaps you want pretty-printed HTML:

```
$meta->tagPrefix( "\t\t" )
	->tagSuffix( "\n" );
```	

The result will be along these lines:

```
<html>
	<head>
		<meta charset="utf-8" />
		<title>My Website</title>
		<meta name="description" content="A description here" />
	</head>
```

Should you want even more control over the output, or want to make any modifications, then you can call the `build( )` method, which will return an array of `HtmlElement` instances. The documentation for that [is here](https://github.com/lukaswhite/html-element).

## Geo Tags

You can also add geotags:

```php
$meta->geoPosition( new Geopoint( 37.416343, -122.153013 ) )
	->geoPlaceName( 'London' )
	->geoRegion( 'GB' );
```

The result:

```html
<meta name="geo.position" content="37.416343, -122.153013" />
<meta name="ICBM" content="37.416343, -122.153013" />
<meta name="geo.placename" content="London" />
<meta name="geo.region" content="GB" />	
```

## Adding Additional Meta Tags

It's worth pointing out that you don't necessarily need to add all of your meta tags to your site using this library; indeed arguably there are some that you shouldn't.

Take this example:

```php
<!DOCTYPE html>
<html lang="en">
	<head>
		{!! $meta !!}
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="/assets/styles.min.css">
	</head>
```	

The meta tag and link tag are really theme-specific, so it might make things clearer if you add them to your layout in this way.

That's not to say you can't do it programmatically:

```php
$meta->viewport( 'width=device-width, initial-scale=1' )
	->addLink( '/assets/styles.min.css', 'stylesheet' );
```


