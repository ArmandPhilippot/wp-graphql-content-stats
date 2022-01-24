<?php
/**
 * Provide an error notice view during plugin activation.
 *
 * This file is used to markup the error notice during activation.
 *
 * @package WPGraphQL-Content-Stats
 * @link    https://github.com/ArmandPhilippot/wp-graphql-content-stats
 * @since   0.1.0
 */

?>
<div class="notice notice-error">
	<p><?php esc_html_e( 'WPGraphQL must be installed and activated in order for WP GraphQL Content Stats to work.', 'wpg-content-stats' ); ?></p>
</div>
