<?php
/**
 * Define the WPGraphQL types required by this plugin.
 *
 * @package WPGraphQL-Content-Stats
 * @link    https://github.com/ArmandPhilippot/wp-graphql-content-stats
 * @since   0.1.0
 */

namespace WPGraphQL_Content_Stats;

/**
 * The WPGraphQL types required by this plugin.
 *
 * This class defines new WPGraphQL types for the content stats fields.
 */
class Types {
	/**
	 * Register the new WPGraphQL types.
	 *
	 * @since 0.1.0
	 */
	public function init() {
		add_action( 'graphql_register_types', array( $this, 'register_reading_time_format_enum_type' ) );
		add_action( 'graphql_register_types', array( $this, 'register_content_stats_type' ) );
	}

	/**
	 * Register a new ReadingTimeFormatEnum type in WPGraphQL.
	 *
	 * @since 0.1.0
	 */
	public static function register_reading_time_format_enum_type() {
		\register_graphql_enum_type(
			'ReadingTimeFormatEnum',
			array(
				'description' => __( 'The format of reading time field data.', 'wpg-content-stats' ),
				'values'      => array(
					'minutes' => array(
						'name'        => __( 'minutes', 'wpg-content-stats' ),
						'description' => __( 'Provide a rounded estimate of reading time in minutes.', 'wpg-content-stats' ),
						'value'       => 'minutes',
					),
					'seconds' => array(
						'name'        => __( 'seconds', 'wpg-content-stats' ),
						'description' => __( 'Provide a rounded estimate of reading time in seconds.', 'wpg-content-stats' ),
						'value'       => 'seconds',
					),
				),
			)
		);
	}

	/**
	 * Register a new InfoType type in WPGraphQL.
	 *
	 * @since 0.1.0
	 */
	public function register_content_stats_type() {
		\register_graphql_object_type(
			'InfoType',
			array(
				'description' => __( 'The content extra information.', 'wpg-content-stats' ),
				'fields'      => array(
					'readingTime' => array(
						'type'        => 'Int',
						'description' => __( 'The estimated time to read content.', 'wpg-content-stats' ),
						'args'        => array(
							'format' => array(
								'type'        => 'ReadingTimeFormatEnum',
								'description' => __( 'Reading time format.', 'wpg-content-stats' ),
							),
						),
						'resolve'     => function( $source, $args ) {
							if ( isset( $args['format'] ) && 'seconds' === $args['format'] ) {
								return $source['readingTime']['seconds'];
							}
							return $source['readingTime']['minutes'];
						},
					),
					'wordsCount' => array(
						'type'        => 'Int',
						'description' => __( 'The number of words in post content.', 'wpg-content-stats' ),
						'resolve'     => function( $source ) {
							return $source['wordsCount'];
						},
					),
				),
			)
		);
	}
}
