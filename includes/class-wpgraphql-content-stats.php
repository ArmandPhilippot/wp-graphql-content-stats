<?php
/**
 * Define the core plugin class.
 *
 * @package WPGraphQL-Content-Stats
 * @link    https://github.com/ArmandPhilippot/wp-graphql-content-stats
 * @since   0.1.0
 */

namespace WPGraphQL_Content_Stats;

/**
 * The core plugin class.
 *
 * This class defines internationalization, activation hooks, and deactivation
 * hooks.
 */
class WPGraphQL_Content_Stats {
	/**
	 * The plugin name.
	 *
	 * @since 0.1.0
	 *
	 * @var   string $plugin_name The plugin name.
	 */
	protected $plugin_name;

	/**
	 * The description of this plugin.
	 *
	 * @since 0.1.0
	 *
	 * @var   string The string used as description of this plugin.
	 */
	protected $plugin_description;

	/**
	 * The plugin version.
	 *
	 * @since 0.1.0
	 *
	 * @var   string $plugin_version The current plugin version.
	 */
	protected $plugin_version;

	/**
	 * Define the plugin functionality.
	 *
	 * @since 0.1.0
	 */
	public function __construct() {
		$this->plugin_version     = defined( WPGRAPHQL_CONTENT_STATS_VERSION ) ? WPGRAPHQL_CONTENT_STATS_VERSION : '1.0.0';
		$this->plugin_name        = 'WPGraphQL Content Stats';
		$this->plugin_description = __( 'Add some stats to WPGraphQL: total posts, word count and estimated reading time.', 'wpg-content-stats' );

		$this->load_dependencies();
		$this->set_locale();
	}

	/**
	 * Loads the required dependencies for this plugin.
	 *
	 * @since  0.1.0
	 */
	private function load_dependencies() {
		/**
		 * The class responsible of plugin internationalization.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-i18n.php';

		/**
		 * The class responsible of WPGraphQL types registration.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-types.php';

		/**
		 * The class responsible of WPGraphQL fields registration.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-fields.php';
	}

	/**
	 * Define the locale used by the plugin.
	 *
	 * @since 0.1.0
	 */
	private function set_locale() {
		$translation = new I18n();
		$translation->init();
	}

	/**
	 * Add a content stats types to WPGraphQL.
	 *
	 * @since 0.1.0
	 */
	public function add_content_stats_types() {
		$types = new Types();
		$types->init();
	}

	/**
	 * Add a content stats fields to WPGraphQL.
	 *
	 * @since 0.1.0
	 */
	public function add_content_stats_fields() {
		$fields = new Fields();
		$fields->init();
	}

	/**
	 * Print the error notice during activation.
	 *
	 * @since 0.1.0
	 */
	public function print_plugin_activation_error() {
		include_once 'partials/wp-graphql-content-stats-error-notice.php';
	}

	/**
	 * Check if WPGraphQL is installed and activated.
	 *
	 * @since 0.1.0
	 */
	public function check_plugin_dependencies() {
		if ( ! class_exists( '\WPGraphQL' ) ) {
			add_action( 'admin_notices', array( $this, 'print_plugin_activation_error' ) );
		}
	}

	/**
	 * Execute the plugin.
	 *
	 * @since 0.1.0
	 */
	public function run() {
		$this->set_locale();
		add_action( 'plugins_loaded', array( $this, 'check_plugin_dependencies' ) );
		$this->add_content_stats_types();
		$this->add_content_stats_fields();
	}

	/**
	 * Retrieve the plugin name.
	 *
	 * @since 0.1.0
	 *
	 * @return string The plugin name.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * Retrieves the description of the plugin.
	 *
	 * @since 0.1.0
	 *
	 * @return string The description of the plugin.
	 */
	public function get_plugin_description() {
		return $this->plugin_description;
	}

	/**
	 * Retrieve the current plugin version.
	 *
	 * @since 0.1.0
	 *
	 * @return string The current version of the plugin.
	 */
	public function get_plugin_version() {
		return $this->plugin_version;
	}
}
