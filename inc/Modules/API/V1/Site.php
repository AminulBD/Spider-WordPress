<?php

namespace AminulBD\Spider\WordPress\Modules\API\V1;

use AminulBD\Spider\WordPress\Contracts\Module;
use WP_Post;
use WP_REST_Request;

class Site extends Module {
	public static string $name    = 'APIv1_Site';
	public static string $version = '1.0.0';
	public static string $type    = 'any';

	public function __construct() {
		$this->add_action( 'rest_api_init', [ $this, 'routes' ] );
	}

	public function routes(): void {
		register_rest_route( 'spider/v1', '/sites', [
			'methods'             => 'GET',
			'callback'            => [ $this, 'index' ],
			'permission_callback' => [ $this, 'has_access' ],
		] );

		register_rest_route( 'spider/v1', '/sites', [
			'methods'             => 'POST',
			'callback'            => [ $this, 'create' ],
			'permission_callback' => [ $this, 'has_access' ],
		] );

		register_rest_route( 'spider/v1', '/sites/(?P<id>\d+)', [
			'methods'             => 'GET',
			'callback'            => [ $this, 'get' ],
			'permission_callback' => [ $this, 'has_access' ],
		] );

		register_rest_route( 'spider/v1', '/sites/(?P<id>\d+)', [
			'methods'             => 'PUT',
			'callback'            => [ $this, 'update' ],
			'permission_callback' => [ $this, 'has_access' ],
		] );

		register_rest_route( 'spider/v1', '/sites/(?P<id>\d+)', [
			'methods'             => 'DELETE',
			'callback'            => [ $this, 'delete_site' ],
			'permission_callback' => [ $this, 'has_access' ],
		] );
	}

	private function transform( WP_Post $site ): array {
		$content = json_decode( $site->post_content, true );

		return [
			'id'         => $site->ID,
			'name'       => $site->post_title,
			'identifier' => $site->post_name,
			'engine'     => $content[ 'engine' ],
			'config'     => $content[ 'config' ],
			'status'     => $site->post_status,
		];
	}

	public function has_access(): bool {
		return current_user_can( 'manage_options' );
	}

	private function clean_fields( array $data ): array {
		$defaults = [
			'name'       => null,
			'identifier' => null,
			'engine'     => null,
			'status'     => 'inactive',
			'config'     => [],
		];
		$filtered = array_intersect_key( $data, $defaults );

		return array_merge( $defaults, $filtered );
	}

	public function index() {
		$sites = get_posts( [
			'post_type'   => 'spider_site',
			'post_status' => [ 'active', 'inactive' ],
		] );

		return [
			'message' => 'All available sites are fetched.',
			'data'    => array_map( [ $this, 'transform' ], $sites ),
		];
	}

	public function create( WP_REST_Request $request ): array {
		$data = $this->clean_fields( $request->get_json_params() );
		$id   = wp_insert_post( [
			'post_type'    => 'spider_site',
			'post_title'   => $data[ 'name' ],
			'post_name'    => $data[ 'identifier' ],
			'post_content' => json_encode( [
				'engine' => $data[ 'engine' ],
				'config' => $data[ 'config' ],
			], JSON_PRETTY_PRINT ),
			'post_status'  => $data[ 'status' ],
		] );

		return [
			'message' => 'Site has been created.',
			'data'    => array_merge( $data, [ 'id' => $id ] ),
		];
	}

	public function get( WP_REST_Request $request ): array {
		$id   = $request->get_param( 'id' );
		$site = get_post( $id );

		if ( !$site || $site->post_type !== 'spider_site' ) {
			return [
				'message' => 'The requested site does not exist.',
			];
		}

		return [
			'message' => 'The requested site is fetched.',
			'data'    => $this->transform( $site ),
		];
	}

	public function update( WP_REST_Request $request ): array {
		$id   = $request->get_param( 'id' );
		$site = get_post( $id );

		if ( !$site || $site->post_type !== 'spider_site' ) {
			return [
				'message' => 'The requested site does not exist.',
			];
		}

		$data = $this->clean_fields( $request->get_json_params() );

		wp_update_post( [
			'ID'           => $id,
			'post_title'   => $data[ 'name' ],
			'post_name'    => $data[ 'identifier' ],
			'post_content' => json_encode( [
				'engine' => $data[ 'engine' ],
				'config' => $data[ 'config' ],
			], JSON_PRETTY_PRINT ),
			'post_status'  => $data[ 'status' ],
		] );

		return [
			'message' => 'Site has been updated.',
			'data'    => array_merge( $data, [ 'id' => $id ] ),
		];
	}

	public function delete_site( WP_REST_Request $request ): array {
		$id   = $request->get_param( 'id' );
		$site = get_post( $id );

		if ( !$site || $site->post_type !== 'spider_site' ) {
			return [
				'message' => 'The requested site does not exist.',
			];
		}

		wp_delete_post( $id, true );

		return [
			'message' => 'The site has been deleted.',
		];
	}
}
