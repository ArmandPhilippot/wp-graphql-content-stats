<?php
/**
 * WPGraphQL Content Stats
 *
 * Add some stats to WPGraphQL: total posts, word count and estimated reading
 * time.
 *
 * @package   WPGraphQL-Content-Stats
 * @link      https://github.com/ArmandPhilippot/wp-graphql-content-stats
 * @author    Armand Philippot <contact@armandphilippot.com>
 *
 * @copyright 2022 Armand Philippot
 * @license   GPL-2.0-or-later
 * @since     0.1.0
 *
 * @wordpress-plugin
 * Plugin Name:       WPGraphQL Content Stats
 * Plugin URI:        https://github.com/ArmandPhilippot/wp-graphql-content-stats
 * Description:       Add some stats to WPGraphQL: total posts, word count and estimated reading time.
 * Version:           0.1.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Armand Philippot
 * Author URI:        https://www.armandphilippot.com/
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wpg-content-stats
 * Domain Path:       /languages
 */

namespace WPGraphQL_Content_Stats;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * Currently plugin version.
 * Start at version 0.1.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WPGRAPHQL_CONTENT_STATS_VERSION', '0.1.0' );

/**
 * Initialize the plugin.
 *
 * @since 0.1.0
 */
function wp_graphql_content_stats_init() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wpgraphql-content-stats.php';

	$plugin = new WPGraphQL_Content_Stats();
	$plugin->run();
}

wp_graphql_content_stats_init();
