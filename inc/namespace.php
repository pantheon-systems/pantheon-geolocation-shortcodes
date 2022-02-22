<?php
/**
 * Pantheon Geolocation Shortcodes main namespace file.
 *
 * @package Pantheon\EdgeIntegrations
 */

namespace Pantheon\EI\WP\Shortcodes;

/**
 * Kick off the plugin.
 */
function bootstrap() {
	// Check if the WordPress Edge Integrations plugin exists. If it doesn't, we can't use this plugin.
	add_action( 'admin_init', __NAMESPACE__ . '\\check_ei_plugin' );
}

/**
 * Check if the WordPress Edge Integrations plugin exists.
 */
function check_ei_plugin() {
	if (
		is_admin() &&
		current_user_can( 'activate_plugins' ) &&
		! is_plugin_active( 'pantheon-wordpress-edge-integrations/pantheon-wordpress-edge-integrations.php' )
	) {
		add_action( 'admin_notices', __NAMESPACE__ . '\\ei_plugin_notice' );
		deactivate_plugins( plugin_basename( __FILE__ ) );

		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}
	}
}

/**
 * Display a notice if the WordPress Edge Integrations plugin is not installed and active.
 */
function ei_plugin_notice() {
	?>
	<div class="error">
		<p>
			<?php
			printf(
				__( 'The %1$s plugin requires the %2$s plugin to be installed and active.', 'pantheon-geolocation-shortcodes' ),
				'<strong>' . esc_html__( 'Pantheon Geolocation Shortcodes', 'pantheon-geolocation-shortcodes' ) . '</strong>',
				'<strong>' . esc_html__( 'Pantheon WordPress Edge Integrations', 'pantheon-geolocation-shortcodes' ) . '</strong>'
			);
			?>
		</p>
	<?php
}
