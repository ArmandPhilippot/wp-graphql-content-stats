<?php
/**
 * Define the content stats fields class.
 *
 * @package WPGraphQL-Content-Stats
 * @link    https://github.com/ArmandPhilippot/wp-graphql-content-stats
 * @since   0.1.0
 */

namespace WPGraphQL_Content_Stats;

/**
 * The content stats fields class.
 *
 * This class defines new WPGraphQL fields to retrieve content stats.
 */
class Fields {
	/**
	 * Register content stats fields in WPGraphQL.
	 *
	 * @since 0.1.0
	 */
	public function init() {
		add_action( 'graphql_register_types', array( $this, 'register_content_stats_field' ) );
		add_action( 'graphql_register_types', array( $this, 'register_total_posts_field' ) );
		add_filter( 'graphql_connection_page_info', array( $this, 'filter_page_info_connection' ), 10, 2 );
		add_filter( 'graphql_connection_query_args', array( $this, 'update_graphql_query_args' ), 10, 2 );
	}

	/**
	 * Count words including special characters.
	 *
	 * @since 0.1.0
	 *
	 * @param string $string The string to parse.
	 * @return integer The words count.
	 */
	private function count_all_words( $string ) {
		$array = preg_split( '/\W+/u', $string, -1, ENT_QUOTES );

		return count( $array );
	}

	/**
	 * Retrieve the total number of words in content.
	 *
	 * @since 0.1.0
	 *
	 * @param string $content The content to parse.
	 * @return integer The total number of words.
	 */
	private function get_words_count( $content ) {
		$blocks = parse_blocks( $content );
		$html   = '';

		foreach ( $blocks as $block ) {
			$html .= render_block( $block );
		}

		$cleaned_content = wp_strip_all_tags( $html, true );

		return $cleaned_content ? $this->count_all_words( $cleaned_content ) : 0;
	}

	/**
	 * Calculate time to read all words in minutes.
	 *
	 * @since 0.1.0
	 *
	 * @param integer $words_count The total number of words.
	 * @param string  $format Either "minutes" or "seconds".
	 * @param integer $words_per_minute The number of words read per minute.
	 * @return float The reading time in chosen format.
	 */
	private function get_reading_time( $words_count, $format = 'minutes', $words_per_minute = 250 ) {
		if ( 'minutes' === $format ) {
			return ceil( $words_count / $words_per_minute );
		}

		$words_per_second = $words_per_minute / 60;

		return ceil( $words_count / $words_per_second );
	}

	/**
	 * Register a contentStats field to WPGraphQL ContentNode.
	 *
	 * @since 0.1.0
	 */
	public function register_content_stats_field() {
		\register_graphql_field(
			'ContentNode',
			'info',
			array(
				'type'        => 'InfoType',
				'description' => __( 'Return the content statistics.', 'wpg-content-stats' ),
				'resolve'     => function( \WPGraphQL\Model\Post $post_model ) {
					// phpcs:ignore WordPress.NamingConventions.ValidVariableName
					$raw_content = $post_model->contentRaw;
					$words_count = $this->get_words_count( $raw_content );
					$reading_time_in_minutes = $this->get_reading_time( $words_count );
					$reading_time_in_seconds = $this->get_reading_time( $words_count, 'seconds' );

					return array(
						'wordsCount'  => $words_count,
						'readingTime' => array(
							'minutes' => $reading_time_in_minutes,
							'seconds' => $reading_time_in_seconds,
						),
					);
				},
			)
		);
	}

	/**
	 * Update GraphQL query args to count the total number of rows.
	 *
	 * @since 0.1.0
	 *
	 * @param array                      $args The GraphQL query args.
	 * @param AbstractConnectionResolver $connection Instance of the connection resolver.
	 * @return array The GraphQL query args.
	 */
	public function update_graphql_query_args( $args, $connection ) {
		if ( $connection->get_query() instanceof \WP_Query ) {
			$args['no_found_rows'] = false;
		} elseif ( $connection->get_query() instanceof \WP_User_Query ) {
			$args['count_total'] = true;
		}

		return $args;
	}

	/**
	 * Filter PageInfo connection to set the total.
	 *
	 * @since 0.1.0
	 *
	 * @param array                      $page_info Array containing page info.
	 * @param AbstractConnectionResolver $connection Instance of the connection resolver.
	 * @return array
	 */
	public function filter_page_info_connection( $page_info, $connection ) {
		$page_info['total'] = null;

		if ( $connection->get_query() instanceof \WP_Query ) {
			if ( isset( $connection->get_query()->found_posts ) ) {
				$page_info['total'] = (int) $connection->get_query()->found_posts;
			}
		} elseif ( $connection->get_query() instanceof \WP_User_Query ) {
			if ( isset( $connection->get_query()->total_users ) ) {
				$page_info['total'] = (int) $connection->get_query()->total_users;
			}
		}

		return $page_info;
	}

	/**
	 * Register a total field to WPGraphQL PageInfo.
	 *
	 * @since 0.1.0
	 */
	public function register_total_posts_field() {
		\register_graphql_field(
			'WPPageInfo',
			'total',
			array(
				'type'        => 'Int',
				'description' => __( 'Return the total of found posts or found users depending on query type.', 'wpg-content-stats' ),
			)
		);
	}
}
