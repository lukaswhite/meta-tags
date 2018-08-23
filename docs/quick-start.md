# Quick Start

## Installation

```
composer require lukaswhite/meta-tags
```

## Create an Instance

```php
use Lukaswhite\MetaTags\MetaTags;

$meta = new MetaTags( );
```

> Recommended: create a singleton, then place it in a service container or similar

## Set some Basic Information

```php
$meta
	->title( 'My website' )
	->description( 'A description' )
	->url( 'http://www.example.com' )
	->canonical( 'http://www.example.com' );
```

## Optionally add Media

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

## Render the Tags

```php
print $meta->render( );
```