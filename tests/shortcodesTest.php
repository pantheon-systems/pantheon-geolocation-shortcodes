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
		add_filter( 'pantheon.ei.get_all_geo', function() {
			return [
				'country-code' => 'US',
				'city' => 'Salt Lake City',
				'region' => 'UT',
				'continent-code' => 'NA',
				'conn-speed' => 'broadband',
				'conn-type' => 'wired',
			];
		} );

		add_filter( 'pantheon.ei.get_geo_country-code', function() {
			return 'US';
		} );

		add_filter( 'pantheon.ei.get_geo_continent-code', function() {
			return 'NA';
		} );

		add_filter( 'pantheon.ei.get_geo_city', function() {
			return 'Salt Lake City';
		} );

		add_filter( 'pantheon.ei.get_geo_region', function() {
			return 'UT';
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
			json_decode( Geo\get_geo(), true ),
			Shortcodes\get_all(),
			'get_all() function should match JSON-decoded get_geo() function.'
		);
	}

	/**
	 * Test the get_continent function.
	 */
	public function testGetContinent() {
		$this->assertEquals(
			Geo\get_geo( 'continent' ),
			Shortcodes\get_continent(),
			'get_continent() function should match get_geo( \'continent\' ).'
		);
	}

	/**
	 * Test the get_country function.
	 */
	public function testGetCountry() {
		$this->assertEquals(
			Geo\get_geo( 'country' ),
			Shortcodes\get_country(),
			'get_country() function should match get_geo( \'country\' ).'
		);
	}

	/**
	 * Test the get_region function.
	 */
	public function testGetRegion() {
		$this->assertEquals(
			Geo\get_geo( 'region' ),
			Shortcodes\get_region(),
			'get_region() function should match get_geo( \'region\' ).'
		);
	}

	/**
	 * Test the get_city function.
	 */
	public function testGetCity() {
		$this->assertEquals(
			Geo\get_geo( 'city' ),
			Shortcodes\get_city(),
			'get_city() function should match get_geo( \'city\' ).'
		);
	}

	/**
	 * Test the get_postal_code function.
	 *
	 * This parameter is not currently supported by the Edge Integrations plugin.
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

	/**
	 * Test the get_latitude function.
	 *
	 * This parameter is not currently supported by the Edge Integrations plugin.
	 */
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

	/**
	 * Test the get_longitude function.
	 *
	 * This parameter is not currently supported by the Edge Integrations plugin.
	 */
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

	/**
	 * Test the get_shortcodes function.
	 */
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
			$shortcodes,
			Shortcodes\get_shortcodes(),
			'get_shortcodes() function should match the expected shortcodes.'
		);
	}

	/**
	 * Test the do_shortcode_continent function.
	 */
	public function testDoShortcodeContinent() {
		$this->assertEquals(
			'NA',
			Shortcodes\do_shortcode_continent( [] ),
			'do_shortcode( \'geoip-continent\' ) does not matcht expected output.'
		);
	}

	/**
	 * Test the do_shortcode_country function.
	 */
	public function testDoShortcodeCountry() {
		$this->assertEquals(
			'US',
			Shortcodes\do_shortcode_country( [] ),
			'do_shortcode( \'geoip-country\' ) does not match expected output.'
		);
	}

	/**
	 * Test the do_shortcode_region function.
	 */
	public function testDoShortcodeRegion() {
		$this->assertEquals(
			'UT',
			Shortcodes\do_shortcode_region( [] ),
			'do_shortcode( \'geoip-region\' ) does not match expected output.'
		);
	}

	/**
	 * Test the do_shortcode_city function.
	 */
	public function testDoShortcodeCity() {
		$this->assertEquals(
			'Salt Lake City',
			Shortcodes\do_shortcode_city( [] ),
			'do_shortcode( \'geoip-city\' ) does not match expected output.'
		);
	}

	/**
	 * Test the do_shortcode_location function.
	 */
	public function testDoShortcodeLocation() {
		$this->assertEquals(
			'Salt Lake City, UT, US',
			Shortcodes\do_shortcode_location( [] ),
			'do_shortcode( \'geoip-location\' ) does not match expected output.'
		);
	}

	/**
	 * Test the compare_location_types function.
	 */
	public function testCompareLocationTypes() {
		$this->assertEquals(
			0,
			Shortcodes\compare_location_types( 'city', 'city' )
		);

		$this->assertEquals(
			0,
			Shortcodes\compare_location_types( 'planet', 'continent' )
		);

		$this->assertEquals(
			1,
			Shortcodes\compare_location_types( 'country', 'continent' )
		);

		$this->assertEquals(
			2,
			Shortcodes\compare_location_types( 'city', 'country' )
		);

		$this->assertEquals(
			3,
			Shortcodes\compare_location_types( 'city', 'continent' )
		);

		$this->assertEquals(
			-3,
			Shortcodes\compare_location_types( 'continent', 'city' )
		);

		$this->assertEquals(
			-2,
			Shortcodes\compare_location_types( 'country', 'city' )
		);

		$this->assertEquals(
			-1,
			Shortcodes\compare_location_types( 'continent', 'country' )
		);
	}

	/**
	 * Test the do_shortcode_content function.
	 *
	 * Example implementations come from WPE GeoIP's examples. These tests ensure that the shortcode works as expected for the most common best practice scenarios.
	 */
	public function testDoShortcodeContent() {
		// Test country-specific content.
		$shortcode = [ 'country' => Shortcodes\get_country() ];
		$content = 'Your US specific content goes here';
		// Fail if the test parameters don't match what they should be.
		if ( $shortcode['country'] !== 'US' ) {
			$this->fail( 'Failing this test because the country is not US.' );
		}
		$this->assertEquals(
			$content,
			Shortcodes\do_shortcode_content( $shortcode, $content ),
			'[geoip-content country="US"] does not match expected output.'
		);

		// Set the region to something other than Utah. We shouldn't get output because the region doesn't match.
		$shortcode = [ 'region' => 'TX, CA' ];
		// Fail if the test parameters don't match what they should be.
		if ( Shortcodes\get_region() !== 'UT' ) {
			$this->fail( 'Failing this test because the region is not UT.' );
		}
		$this->assertEquals(
			'',
			Shortcodes\do_shortcode_content( $shortcode, $content ),
			'[geoip-content region="TX, CA"] does not match expected output.'
		);

		// Set the shortcode region to the one we've established. We should get output because the region matches.
		$shortcode = [ 'region' => Shortcodes\get_region() ];
		$this->assertEquals(
			Shortcodes\do_shortcode_content( $shortcode, $content ),
			$content,
			'[geoip-content region="UT"] does not match expected output.'
		);

		// Set country and not-city. We should not get output because our city is excluded.
		$shortcode = [
			'country' => Shortcodes\get_country(),
			'not_city' => Shortcodes\get_city(),
		];
		$content = 'Content for US visitors but not for visitors in Salt Lake City';
		// Fail if the test parameters don't match what they should be.
		if ( $shortcode['not_city'] !== 'Salt Lake City' ) {
			$this->fail( 'Failing this test because the not_city is not Salt Lake City.' );
		}
		$this->assertEquals(
			Shortcodes\do_shortcode_content( $shortcode, $content ),
			'',
			'[geoip-content country="US" not_city="Salt Lake City"] does not match expected output.'
		);

		// Now add the city and make sure we get our content.
		$shortcode = [ 'city' => Shortcodes\get_city() ];
		$content = 'Your Salt Lake City specific content goes here';
		// Fail if the test parameters don't match what they should be.
		if ( $shortcode['city'] !== 'Salt Lake City' ) {
			$this->fail( 'Failing this test because the city is not Salt Lake City.' );
		}
		$this->assertEquals(
			Shortcodes\do_shortcode_content( $shortcode, $content ),
			$content,
			'[geoip-content city="Salt Lake City"] does not match expected output.'
		);

		// Define the regions in the shortcode, including our region. We should see the content.
		$shortcode = [ 'region' => 'AL, AZ, AR, CA, CO, CT, DE, FL, GA, ID, IL, IN, IA, KS, KY, LA, ME, MD, MA, MI, MN, MS, MO, MT, NE, NV, NH, NJ, NM, NY, NC, ND, OH, OK, OR, PA, RI, SC, SD, TN, TX, UT, VT, VA, WA, WV, WI, WY' ];
		$content = 'Free shipping on all orders over $50 in the lower 48!';
		// Fail if the test parameters don't match what they should be.
		if ( Shortcodes\get_region() !== 'UT' ) {
			$this->fail( 'Failing this test because the region is not UT.' );
		}
		$this->assertEquals(
			Shortcodes\do_shortcode_content( $shortcode, $content ),
			$content,
			'[geoip-content region="AL, AZ, AR, CA, CO, CT, DE, FL, GA, ID, IL, IN, IA, KS, KY, LA, ME, MD, MA, MI, MN, MS, MO, MT, NE, NV, NH, NJ, NM, NY, NC, ND, OH, OK, OR, PA, RI, SC, SD, TN, TX, UT, VT, VA, WA, WV, WI, WY"] does not match expected output.'
		);

		// Test continent and not-country.
		$shortcode = [
			'continent' => Shortcodes\get_continent(),
			'not_country' => 'US',
		];
		$content = 'This should be hidden from US visitors.';
		// Fail if the test parameters don't match what they should be.
		if ( Shortcodes\get_country() !== 'US' || Shortcodes\get_continent() !== 'NA' ) {
			$this->fail( 'Failing this test because the not_country is not US or the continent is not NA.' );
		}
		$this->assertEquals(
			Shortcodes\do_shortcode_content( $shortcode, $content ),
			'',
			'[geoip-content continent="NA" not_country="US"] does not match expected output.'
		);

		// Test excluding continents.
		$shortcode = [ 'not_continent' => 'EU, SA' ];
		$content = 'This should be visible to US visitors.';
		// Fail if the test parameters don't match what they should be.
		if ( in_array( Shortcodes\get_continent(), [ 'EU', 'SA' ], true ) ) {
			$this->fail( 'Failing this test because the continent is not NA.' );
		}
		$this->assertEquals(
			Shortcodes\do_shortcode_content( $shortcode, $content ),
			$content,
			'[geoip-content not_continent="EU, SA"] does not match expected output.'
		);

		// Test using multiple countries excluding a city.
		$shortcode = [
			'country' => 'US, FR',
			'not_city' => Shortcodes\get_city(),
		];
		$content = 'This should be visible to US and FR visitors but not people in Salt Lake City.';
		// Fail if the test parameters don't match what they should be.
		if ( Shortcodes\get_country() !== 'US' || Shortcodes\get_city() !== 'Salt Lake City' ) {
			$this->fail( 'Failing this test because the country is not US or the city is not Salt Lake City.' );
		}
		$this->assertEquals(
			Shortcodes\do_shortcode_content( $shortcode, $content ),
			'',
			'[geoip-content country="US, FR" not_city="Salt Lake City"] does not match expected output.'
		);

		// Test excluding the continent.
		$shortcode['not_continent'] = Shortcodes\get_continent();
		$content = 'Now it won\'t be visible to anyone in North America. FR visitors will still see this.';
		// Fail if the test parameters don't match what they should be.
		if ( Shortcodes\get_continent() !== 'NA' ) {
			$this->fail( 'Failing this test because the continent is not NA.' );
		}
		$this->assertEquals(
			Shortcodes\do_shortcode_content( $shortcode, $content ),
			'',
			'[geoip-content country="US, FR" not_city="Salt Lake City" not_continent="NA"] does not match expected output.'
		);

		$shortcode = [
			'country' => 'US',
			'not_region' => 'UT, NV',
		];
		$content = 'This should be visible to US visitors but not people in Utah or Nevada.';
		// Fail if the test parameters don't match what they should be.
		if ( Shortcodes\get_country() !== 'US' || Shortcodes\get_region() !== 'UT' ) {
			$this->fail( 'Failing this test because the country is not US or the region is not UT.' );
		}
		$this->assertEquals(
			'',
			Shortcodes\do_shortcode_content( $shortcode, $content ),
			'[geoip-content country="US" not_region="UT, NV"] does not match expected output.'
		);
	}
}
