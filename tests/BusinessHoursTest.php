<?php

use Lukaswhite\MetaTags\Tests\TestCase;
use Lukaswhite\MetaTags\MetaTags;
use Lukaswhite\MetaTags\Entities\BusinessDay;

class BusinessHoursTest extends TestCase
{

    public function testCreateInstanceWithStringDay( )
    {
        $day = new BusinessDay( 'Monday', '09:00', '17:00' );
        $this->assertEquals( 'monday', $day->getDay( ) );
    }

    public function testCreateInstanceWithIntDay( )
    {
        $day = new BusinessDay( 2, '09:00', '17:00' );
        $this->assertEquals( 'tuesday', $day->getDay( ) );
    }

    public function testCreateInstanceWithDateTimeDay( )
    {
        $day = new BusinessDay( new \DateTime( '2018-08-20' ), '09:00', '17:00' );
        $this->assertEquals( 'monday', $day->getDay( ) );
    }

    public function testAddingSingleDay( )
    {
        $meta = new \Lukaswhite\MetaTags\MetaTags( );
        $day = new BusinessDay( 'Monday', '09:00', '17:00' );
        $meta->addBusinessHoursForDay( $day );

        $this->assertTrue(
            $this->hasMetaTag(
                $meta->render( ),
                'og:business:hours:day'
            )
        );

        $this->assertEquals(
            'monday',
            $this->getContentOfMetaTag(
                $meta->render( ),
                'og:business:hours:day'
            )
        );

        $this->assertTrue(
            $this->hasMetaTag(
                $meta->render( ),
                'og:business:hours:start'
            )
        );

        $this->assertEquals(
            '09:00',
            $this->getContentOfMetaTag(
                $meta->render( ),
                'og:business:hours:start'
            )
        );

        $this->assertTrue(
            $this->hasMetaTag(
                $meta->render( ),
                'og:business:hours:end'
            )
        );

        $this->assertEquals(
            '17:00',
            $this->getContentOfMetaTag(
                $meta->render( ),
                'og:business:hours:end'
            )
        );
    }

    public function testAddingMultipleDays( )
    {
        $meta = new \Lukaswhite\MetaTags\MetaTags( );
        $meta->businessHours(
            new BusinessDay( 'Monday', '09:00', '17:00' ),
            new BusinessDay( 'Tuesday', '09:00', '17:00' ),
            new BusinessDay( 'Wednesday', '09:00', '17:00' ),
            new BusinessDay( 'Thursday', '09:00', '17:00' ),
            new BusinessDay( 'Friday', '09:00', '17:00' ),
            new BusinessDay( 'Saturday', '08:00', '18:00' )
        );

        $days = $this->getContentOfMultipleMetaTags( $meta->render( ), 'og:business:hours:day' );
        $this->assertEquals( 6, count( $days ) );
        $this->assertEquals( 'monday', $days[ 0 ] );
        $this->assertEquals( 'saturday', $days[ 5 ] );

        $starts = $this->getContentOfMultipleMetaTags( $meta->render( ), 'og:business:hours:start' );
        $this->assertEquals( 6, count( $starts ) );
        $this->assertEquals( '09:00', $starts[ 0 ] );
        $this->assertEquals( '08:00', $starts[ 5 ] );

        $ends = $this->getContentOfMultipleMetaTags( $meta->render( ), 'og:business:hours:end' );
        $this->assertEquals( 6, count( $ends ) );
        $this->assertEquals( '17:00', $ends[ 0 ] );
        $this->assertEquals( '18:00', $ends[ 5 ] );
    }



}