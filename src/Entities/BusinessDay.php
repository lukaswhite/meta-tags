<?php

namespace Lukaswhite\MetaTags\Entities;

/**
 * Class BusinessDay
 *
 * Represents a business day.
 *
 * @package Lukaswhite\MetaTags\Entities
 */
class BusinessDay
{
    /**
     * Class constants
     */
    const SUNDAY        =   'sunday';
    const MONDAY        =   'monday';
    const TUESDAY       =   'tuesday';
    const WEDNESDAY     =   'wednesday';
    const THURSDAY      =   'thursday';
    const FRIDAY        =   'friday';
    const SATURDAY      =   'saturday';

    /**
     * The day (of the week)
     *
     * @var string
     */
    protected $day;

    /**
     * The start time; i.e. when the business opens
     *
     * @var string
     */
    protected $start;

    /**
     * The start time; i.e. when the business closes
     *
     * @var string
     */
    protected $end;

    /**
     * BusinessDay constructor.
     *
     * @param $day
     * @param $start
     * @param $end
     */
    public function __construct( $day, $start, $end )
    {
        $this->setDay( $day );
        $this->setStart( $start );
        $this->setEnd( $end );
    }

    /**
     * @return string
     */
    public function getDay()
    {
        return $this->day;
    }

    /**
     * @param string $day
     * @return BusinessDay
     */
    public function setDay($day)
    {
        if ( is_string( $day ) ) {
            $this->day = strtolower( $day );
        } elseif ( is_int( $day ) ) {
            $this->day = $this->intToDay( $day );
        } elseif ( is_a( $day, 'DateTime' ) ) {
            $this->day = $this->day = $this->intToDay( intval( $day->format( 'w' ) ) );
        }
        return $this;
    }

    /**
     * @return string
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @param string $start
     * @return BusinessDay
     */
    public function setStart($start)
    {
        $this->start = $start;
        return $this;
    }

    /**
     * @return string
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * @param string $end
     * @return BusinessDay
     */
    public function setEnd($end)
    {
        $this->end = $end;
        return $this;
    }

    protected function intToDay( $day )
    {
        return $this->getDays( )[ $day ];
    }

    protected function getDays( )
    {
        return [
            self::SUNDAY,
            self::MONDAY,
            self::TUESDAY,
            self::WEDNESDAY,
            self::THURSDAY,
            self::FRIDAY,
            self::SATURDAY,
        ];
    }
}