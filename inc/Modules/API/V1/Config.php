<?php

namespace AminulBD\Spider\WordPress\Modules\API\V1;

use AminulBD\Spider\WordPress\Contracts\Module;
use WP_REST_Request;

class Config extends Module {
	public static string $name       = 'APIv1_Config';
	public static string $version    = '1.0.0';
	public static string $type       = 'any';
	private string       $option_key = '_spider_config';

	public function __construct() {
		$this->add_action( 'rest_api_init', [ $this, 'routes' ] );
	}

	public function routes() {
		register_rest_route( 'spider/v1', '/config', [
			'methods'             => 'GET',
			'callback'            => [ $this, 'index' ],
			'permission_callback' => [ $this, 'has_access' ],
		] );

		register_rest_route( 'spider/v1', '/config', [
			'methods'             => 'POST',
			'callback'            => [ $this, 'store' ],
			'permission_callback' => [ $this, 'has_access' ],
		] );
	}

	public function has_access(): bool {
		return current_user_can( 'manage_options' );
	}

	private function clean_fields( array $data ): array {
		$defaults = [
			'pro_licence' => null,
		];
		$filtered = array_intersect_key( $data, $defaults );

		return array_merge( $defaults, $filtered );
	}

	public function index(): array {
		$config = get_option( $this->option_key );

		return [
			'message' => 'Spider configuration.',
			'data'    => [
				'pro_licence' => [
					'type'        => 'text',
					'label'       => __( 'Pro Licence', 'spider' ),
					'placeholder' => __( 'Enter your pro licence key', 'spider' ),
					'rules'       => [ 'required' ],
					'default'     => null,
					'value'       => $config[ 'pro_licence' ] ?? null,
				],
			],
		];
	}

	public function store( WP_REST_Request $request ): array {
		$data = $this->clean_fields( $request->get_json_params() );

		// TODO: validate $data with defined rules.
		update_option( $this->option_key, $data );

		return [
			'message' => __( 'Configuration saved successfully.', 'spider' ),
			'data'    => $data,
		];
	}
}
