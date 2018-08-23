<?php

namespace Lukaswhite\MetaTags\Tests;

class TestCase extends \PHPUnit\Framework\TestCase {

    /**
     * Helper method. Given an HTML string it grabs a named meta tag.
     *
     * The $attribute parameter specifies whether to look for a meta tag by the name
     * attribute, or by the property attribute
     *
     * @param $html
     * @param $name
     * @param string $attribute
     * @return string
     */
    protected function hasMetaTag( $html, $name, $attribute = 'property' )
    {
        $doc = new \DOMDocument( );
        $doc->loadHTML( $html );
        $xpath = new \DOMXPath($doc);
        $query = sprintf( '//meta[@%s="%s"]', $attribute, $name );
        $entries = $xpath->query($query);
        return count( $entries ) == 1;
    }

    /**
     * Helper method. Given an HTML string it grabs the content of a named meta tag.
     *
     * The $attribute parameter specifies whether to look for a meta tag by the name
     * attribute, or by the property attribute
     *
     * @param $html
     * @param $name
     * @param string $attribute
     * @return string
     */
    protected function getContentOfMetaTag( $html, $name, $attribute = 'property' )
    {
        $doc = new \DOMDocument( );
        $doc->loadHTML( $html );
        $xpath = new \DOMXPath($doc);
        $query = sprintf( '//meta[@%s="%s"]', $attribute, $name );
        $entries = $xpath->query($query);
        return $entries->item( 0 )->getAttribute('content' );
    }

    /**
     * Helper method. Given an HTML string it grabs the content of a named meta tag.
     *
     * The $attribute parameter specifies whether to look for a meta tag by the name
     * attribute, or by the property attribute
     *
     * @param $html
     * @param $name
     * @param string $attribute
     * @return string
     */
    protected function getContentOfMultipleMetaTags( $html, $name, $attribute = 'property' )
    {
        $doc = new \DOMDocument( );
        $doc->loadHTML( $html );
        $xpath = new \DOMXPath($doc);
        $query = sprintf( '//meta[@%s="%s"]', $attribute, $name );
        $entries = $xpath->query($query);
        $arr = [ ];
        foreach( $entries as $el ) {
            $arr[ ] = $el->getAttribute( 'content' );
        }
        return $arr;
        return array_map(
            function( $el ) {
                return $el->getAttribute( 'content' );
            },
            $entries
        );
        //return $entries->item( 0 )->getAttribute('content' );
    }

    protected function hasLinkWithRel( $html, $rel )
    {
        $doc = new \DOMDocument( );
        $doc->loadHTML( $html );
        $xpath = new \DOMXPath($doc);
        $query = sprintf( '//link[@rel="%s"]', $rel );
        $entries = $xpath->query( $query );
        return count( $entries ) == 1;
    }

    protected function getContentOfLinkTag( $html, $rel )
    {
        $doc = new \DOMDocument( );
        $doc->loadHTML( $html );
        $xpath = new \DOMXPath($doc);
        $query = sprintf( '//link[@rel="%s"]', $rel );
        $entries = $xpath->query($query);
        return $entries->item( 0 )->getAttribute('href' );
    }

    protected function strContains( $haystack, $needle )
    {
        return strpos(
            $haystack,
            $needle
        ) !== false;
    }

    /**
     * Check that the given date string is in ISO8601 format
     *
     * @param string $date
     * @return bool
     */
    protected function dateIsISO8601( $date )
    {
        return !! preg_match(
            '/^([\+-]?\d{4}(?!\d{2}\b))((-?)((0[1-9]|1[0-2])(\3([12]\d|0[1-9]|3[01]))?|W([0-4]\d|5[0-2])(-?[1-7])?|(00[1-9]|0[1-9]\d|[12]\d{2}|3([0-5]\d|6[1-6])))([T\s]((([01]\d|2[0-3])((:?)[0-5]\d)?|24\:?00)([\.,]\d+(?!:))?)?(\17[0-5]\d([\.,]\d+)?)?([zZ]|([\+-])([01]\d|2[0-3]):?([0-5]\d)?)?)?)?$/',
            $date
        );

    }
}