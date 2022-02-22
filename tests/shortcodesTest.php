<?php
/**
 * Test suite for the Pantheon Geolocation Shortcodes.
 *
 * @package Pantheon/EdgeIntegrations
 */

use Pantheon\EI;
use Pantheon\EI\WP\Shortcodes;
use Pantheon\EI\WP\Geo;
use PHPUnit\Framework\TestCase;

/**
 * Main test class for the Pantheon Geolocation Shortcodes plugin.
 */
class shortcodesTests extends TestCase {
	/**
	 * Setup the test suite.
	 */
	function setUp() : void {
		add_filter( 'pantheon.ei.parsed_geo_data', function() {
			return [
				'country' => 'US',
				'city' => 'Salt Lake City',
				'region' => 'UT',
				'continent' => 'NA',
				'conn-speed' => 'broadband',
				'conn-type' => 'wired',
				'lat' => '40.7',
				'lon' => '-111.9',
			];
		} );

		parent::setUp();
	}

	/**
	 * Test that the plugin is loaded.
	 */
	public function testBootstrap() {
		$this->assertTrue(
			function_exists( 'Pantheon\EI\WP\Shortcodes\bootstrap' ),
			'bootstrap() function does not exist.'
		 );
	}

	/**
	 * Test the get_all function.
	 */
	public function testGetAll() {
		$this->assertEquals(
			Shortcodes\get_all(),
			json_decode( Geo\get_geo(), true ),
			'get_all() function should match JSON-decoded get_geo() function.'
		);
	}

	public function testGetContinent() {
		$this->assertEquals(
			Shortcodes\get_continent(),
			Geo\get_geo( 'continent' ),
			'get_continent() function should match get_geo( \'continent\' ).'
		);
	}

	public function testGetCountry() {
		$this->assertEquals(
			Shortcodes\get_country(),
			Geo\get_geo( 'country' ),
			'get_country() function should match get_geo( \'country\' ).'
		);
	}

	public function testGetRegion() {
		$this->assertEquals(
			Shortcodes\get_region(),
			Geo\get_geo( 'region' ),
			'get_region() function should match get_geo( \'region\' ).'
		);
	}

	public function testGetCity() {
		$this->assertEquals(
			Shortcodes\get_city(),
			Geo\get_geo( 'city' ),
			'get_city() function should match get_geo( \'city\' ).'
		);
	}

	/**
	 * @skip
	 */
	public function testGetPostalCode() {
		$postal_code = Geo\get_geo( 'postal-code' );

		// This is not currently supported.
		if ( empty( $postal_code ) ) {
			$this->markTestSkipped( 'get_geo( \'postal-code\' ) returned false.' );
		}

		$this->assertEquals(
			Shortcodes\get_postal_code(),
			$postal_code,
			'get_postal_code() function should match get_geo( \'postal-code\' ).'
		);
	}

	public function testGetLatitude() {
		$latitude = Geo\get_geo( 'lat' );

		// This is not currently supported.
		if ( empty( $latitude ) ) {
			$this->markTestSkipped( 'get_geo( \'lat\' ) returned false.' );
		}

		$this->assertEquals(
			Shortcodes\get_latitude(),
			$latitude,
			'get_latitude() function should match get_geo( \'lat\' ).'
		);
	}

	public function testGetLongitude() {
		$longitude = Geo\get_geo( 'lon' );

		// This is not currently supported.
		if ( empty( $longitude ) ) {
			$this->markTestSkipped( 'get_geo( \'lon\' ) returned false.' );
		}

		$this->assertEquals(
			Shortcodes\get_longitude(),
			$longitude,
			'get_longitude() function should match get_geo( \'lon\' ).'
		);
	}

	public function testGetShortcodes() {
		$shortcodes = [
			'continent' => 'geoip-continent',
			'country' => 'geoip-country',
			'region' => 'geoip-region',
			'city' => 'geoip-city',
			'location' => 'geoip-location',
			'content' => 'geoip-content',
		];

		$this->assertSame(
			Shortcodes\get_shortcodes(),
			$shortcodes,
			'get_shortcodes() function should match the expected shortcodes.'
		);
	}
}
