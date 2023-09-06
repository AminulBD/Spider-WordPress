<?php

namespace AminulBD\Spider\WordPress\Modules\API\V1;

use AminulBD\Spider\WordPress\Contracts\Module;
use WP_Post;
use WP_REST_Request;

class Engine extends Module {
	public static string $name    = 'APIv1_Engine';
	public static string $version = '1.0.0';
	public static string $type    = 'any';

	public function __construct() {
		$this->add_action( 'rest_api_init', [ $this, 'routes' ] );
	}

	public function routes() {
		register_rest_route( 'spider/v1', '/engines', [
			'methods'             => 'GET',
			'callback'            => [ $this, 'index' ],
			'permission_callback' => [ $this, 'has_access' ],
		] );

		register_rest_route( 'spider/v1', '/engines', [
			'methods'             => 'POST',
			'callback'            => [ $this, 'create' ],
			'permission_callback' => [ $this, 'has_access' ],
		] );

		register_rest_route( 'spider/v1', '/engines/(?P<id>\d+)', [
			'methods'             => 'GET',
			'callback'            => [ $this, 'get' ],
			'permission_callback' => [ $this, 'has_access' ],
		] );

		register_rest_route( 'spider/v1', '/engines/(?P<id>\d+)', [
			'methods'             => 'PUT',
			'callback'            => [ $this, 'update' ],
			'permission_callback' => [ $this, 'has_access' ],
		] );

		register_rest_route( 'spider/v1', '/engines/(?P<id>\d+)', [
			'methods'             => 'DELETE',
			'callback'            => [ $this, 'delete_engine' ],
			'permission_callback' => [ $this, 'has_access' ],
		] );
	}

	private function transform( WP_Post $engine ): array {
		return [
			'id'         => $engine->ID,
			'name'       => $engine->post_title,
			'identifier' => $engine->post_name,
			'config'     => json_decode( $engine->post_content, true ),
			'status'     => $engine->post_status,
		];
	}

	public function has_access(): bool {
		return current_user_can( 'manage_options' );
	}

	private function clean_fields( array $data ): array {
		$defaults = [
			'name'       => null,
			'identifier' => null,
			'status'     => 'inactive',
			'config'     => [],
		];
		$filtered = array_intersect_key( $data, $defaults );

		return array_merge( $defaults, $filtered );
	}

	public function index(): array {
		$engines = get_posts( [
			'post_type'   => 'spider_engine',
			'post_status' => [ 'active', 'inactive' ],
		] );

		return [
			'message' => __( 'List of engines.', 'spider' ),
			'data'    => array_map( [ $this, 'transform' ], $engines ),
		];
	}

	public function create( WP_REST_Request $request ): array {
		$data = $this->clean_fields( $request->get_json_params() );
		$id   = wp_insert_post( [
			'post_type'    => 'spider_engine',
			'post_title'   => $data[ 'name' ],
			'post_name'    => $data[ 'identifier' ],
			'post_content' => json_encode( $data[ 'config' ], JSON_PRETTY_PRINT ),
			'post_status'  => $data[ 'status' ],
		] );

		return [
			'message' => __( 'Engine has been created.', 'spider' ),
			'data'    => array_merge( $data, [ 'id' => $id ] ),
		];
	}

	public function get( WP_REST_Request $request ): array {
		$id     = $request->get_param( 'id' );
		$engine = get_post( $id );

		if ( !$engine || $engine->post_type !== 'spider_engine' ) {
			return [
				'message' => __( 'The requested engine does not exist.', 'spider' ),
			];
		}

		return [
			'message' => __( 'Engine details.', 'spider' ),
			'data'    => $this->transform( $engine ),
		];
	}

	public function update( WP_REST_Request $request ): array {
		$id     = $request->get_param( 'id' );
		$engine = get_post( $id );

		if ( !$engine || $engine->post_type !== 'spider_engine' ) {
			return [
				'message' => __( 'The requested engine does not exist.', 'spider' ),
			];
		}

		$data       = $this->clean_fields( $request->get_json_params() );
		$inp_config = $data[ 'config' ];
		$ori_config = json_decode( $engine->post_content, true ) ?? [];

		wp_update_post( [
			'ID'           => $id,
			'post_title'   => $data[ 'name' ],
			'post_name'    => $data[ 'identifier' ],
			'post_content' => json_encode( array_merge( $ori_config, $inp_config ), JSON_PRETTY_PRINT ),
			'post_status'  => $data[ 'status' ],
		] );

		return [
			'message' => 'Engine has been updated.',
			'data'    => array_merge( $data, [ 'id' => $id ] ),
		];
	}

	public function delete_engine( WP_REST_Request $request ): array {
		$id     = $request->get_param( 'id' );
		$engine = get_post( $id );

		if ( !$engine || $engine->post_type !== 'spider_engine' ) {
			return [
				'message' => __( 'The requested engine does not exist.', 'spider' ),
			];
		}

		wp_delete_post( $id, true );

		return [
			'message' => __( 'Engine has been deleted.', 'spider' ),
		];
	}
}
