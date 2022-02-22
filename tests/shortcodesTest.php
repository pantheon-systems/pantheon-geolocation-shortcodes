<?php
/**
 * Test suite for the Pantheon Geolocation Shortcodes.
 *
 * @package Pantheon/EdgeIntegrations
 */

use Pantheon\EI\WP\Shortcodes;
use Pantheon\EI\WP\Geo;
use PHPUnit\Framework\TestCase;

/**
 * Main test class for the Pantheon Geolocation Shortcodes plugin.
 */
class shortcodesTests extends TestCase {
	/**
	 * Test that the plugin is loaded.
	 */
	public function testBootstrap() {
		$this->assertTrue(
			function_exists( 'Pantheon\EI\WP\Shortcodes\bootstrap' ),
			'bootstrap() function does not exist.'
		 );
	}
}
