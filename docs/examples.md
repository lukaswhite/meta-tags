# Examples (Laravel-flavored)

These simplified examples use Laravel, but you can use this library using any framework - or indeed without a framework at all.

## Registering as a Singleton

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

## A Content Management System

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

## A Restaurant Listing Site

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

## A Social Network

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