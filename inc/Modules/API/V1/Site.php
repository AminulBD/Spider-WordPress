<?php

namespace AminulBD\Spider\WordPress\Modules\API\V1;

use AminulBD\Spider\Spider;
use AminulBD\Spider\WordPress\Contracts\Module;
use AminulBD\Spider\WordPress\Support\TemplateEngine;
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
			'methods'             => 'POST',
			'callback'            => [ $this, 'run' ],
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
			'id'     => $site->ID,
			'name'   => $site->post_title,
			'engine' => $content[ 'engine' ],
			'config' => $content[ 'config' ],
			'status' => $site->post_status,
		];
	}

	public function has_access(): bool {
		return current_user_can( 'manage_options' );
	}

	private function clean_fields( array $data ): array {
		$defaults = [
			'name'   => 'New Site',
			'engine' => 'google',
			'status' => 'inactive',
			'config' => [
				'limit' => null,
			],
		];
		$filtered = array_intersect_key( $data, $defaults );

		return array_merge( $defaults, $filtered );
	}

	public function index(): array {
		$sites = get_posts( [
			'numberposts' => -1,
			'post_type'   => 'spider_site',
			'post_status' => [ 'active', 'inactive' ],
		] );

		return [
			'message' => __( 'List of sites.', 'spider' ),
			'data'    => array_map( [ $this, 'transform' ], $sites ),
		];
	}

	public function create( WP_REST_Request $request ): array {
		$data = $this->clean_fields( $request->get_json_params() );
		$id   = wp_insert_post( [
			'post_type'    => 'spider_site',
			'post_title'   => $data[ 'name' ],
			'post_content' => json_encode( [
				'engine' => $data[ 'engine' ],
				'config' => $data[ 'config' ],
			], JSON_PRETTY_PRINT ),
			'post_status'  => $data[ 'status' ],
		] );

		return [
			'message' => __( 'Site has been created.', 'spider' ),
			'data'    => array_merge( $data, [ 'id' => $id ] ),
		];
	}

	public function get( WP_REST_Request $request ): array {
		$id   = $request->get_param( 'id' );
		$site = get_post( $id );

		if ( !$site || $site->post_type !== 'spider_site' ) {
			return [
				'message' => __( 'The requested site does not exist.', 'spider' ),
			];
		}

		return [
			'message' => __( 'Site details.', 'spider' ),
			'data'    => $this->transform( $site ),
		];
	}

	public function run( WP_REST_Request $request ): array {
		$id   = $request->get_param( 'id' );
		$site = get_post( $id );

		if ( !$site || $site->post_type !== 'spider_site' ) {
			return [
				'message' => __( 'The requested site does not exist.', 'spider' ),
			];
		}
		$params = $request->get_json_params();
		if ( !isset( $params[ 'keywords' ] ) || !is_array( $params[ 'keywords' ] ) || count( $params[ 'keywords' ] ) === 0 ) {
			return [
				'message' => __( 'Please input valid keywords.', 'spider' ),
			];
		}

		$content = json_decode( $site->post_content, true );

		$error   = null;
		$results = [];
		try {
			$spider = new Spider( $content[ 'engine' ], $content[ 'config' ] );

			foreach ( $params[ 'keywords' ] as $keyword ) {
				$results[ $keyword ] = $spider->find( [ 'q' => $keyword ] )->next()->all();
			}
		} catch ( \Exception $e ) {
			$error = $e->getMessage();
		}

		$template = get_option( Config::$option_key )[ 'template' ] ?? null;
		$compiled = [];

		foreach ( $results as $key => $value ) {
			$compiled[ $key ] = TemplateEngine::view( $template, [
				'keyword' => $key,
				'items'   => $value,
			] );
		}

		foreach ( $compiled as $keyword => $content ) {
			wp_insert_post( [
				'post_title'   => 'Top result of ' . $keyword,
				'post_content' => $content,
				'post_status'  => 'draft',
			] );
		}

		return [
			'message' => $error ?? __( 'Site has been run.', 'spider' ),
			'data'    => $results,
		];
	}

	public function update( WP_REST_Request $request ): array {
		$id   = $request->get_param( 'id' );
		$site = get_post( $id );

		if ( !$site || $site->post_type !== 'spider_site' ) {
			return [
				'message' => __( 'The requested site does not exist.', 'spider' ),
			];
		}

		$data        = $this->clean_fields( $request->get_json_params() );
		$content     = json_decode( $site->post_content, true ) ?? [];
		$inp_config  = $data[ 'config' ];
		$orig_config = $content[ 'config' ] ?? [ 'limit' => null ];
		$engine      = $data[ 'engine' ] ?? $content[ 'engine' ];

		wp_update_post( [
			'ID'           => $id,
			'post_title'   => $data[ 'name' ],
			'post_content' => json_encode( [
				'engine' => $engine,
				'config' => array_merge( $orig_config, $inp_config ),
			], JSON_PRETTY_PRINT ),
			'post_status'  => $data[ 'status' ],
		] );

		return [
			'message' => __( 'Site has been updated.', 'spider' ),
			'data'    => array_merge( $data, [
				'id'     => $id,
				'engine' => $engine,
			] ),
		];
	}

	public function delete_site( WP_REST_Request $request ): array {
		$id   = $request->get_param( 'id' );
		$site = get_post( $id );

		if ( !$site || $site->post_type !== 'spider_site' ) {
			return [
				'message' => __( 'The requested site does not exist.', 'spider' ),
			];
		}

		wp_delete_post( $id, true );

		return [
			'message' => __( 'Site has been deleted.', 'spider' ),
		];
	}
}
