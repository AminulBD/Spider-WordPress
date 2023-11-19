<?php

namespace AminulBD\Spider\WordPress\Modules\API\V1;

use AminulBD\Spider\WordPress\Contracts\Module;
use WP_REST_Request;

class Config extends Module {
	public static string $name       = 'APIv1_Config';
	public static string $version    = '1.0.0';
	public static string $type       = 'any';
	public static string $option_key = '_spider_config';

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
			'pro_licence'      => null,
			'subject_template' => null,
			'content_template' => null,
		];
		$filtered = array_intersect_key( $data, $defaults );

		return array_merge( $defaults, $filtered );
	}

	public function index(): array {
		$config = get_option( self::$option_key );

		return [
			'message' => 'Spider configuration.',
			'data'    => [
				'pro_licence'      => [
					'name'        => 'pro_licence',
					'type'        => 'text',
					'label'       => __( 'Pro Licence', 'spider' ),
					'placeholder' => __( 'Enter your pro licence key', 'spider' ),
					'rules'       => [ 'required' ],
					'default'     => null,
					'value'       => $config[ 'pro_licence' ] ?? null,
				],
				'subject_template' => [
					'name'        => 'subject_template',
					'type'        => 'text',
					'label'       => __( 'Subject Template', 'spider' ),
					'placeholder' => __( 'Enter your subject template', 'spider' ),
					'rules'       => [ 'required' ],
					'help'        => 'Available tags: $keyword',
					'default'     => null,
					'value'       => $config[ 'subject_template' ] ?? null,
				],
				'content_template' => [
					'name'        => 'content_template',
					'type'        => 'textarea',
					'label'       => __( 'Content Template', 'spider' ),
					'placeholder' => __( 'Build your template', 'spider' ),
					'rules'       => [ 'required' ],
					'help'        => 'Available variables: $items',
					'default'     => null,
					'value'       => $config[ 'content_template' ] ?? null,
				],
			],
		];
	}

	public function store( WP_REST_Request $request ): array {
		$data = $this->clean_fields( $request->get_json_params() );

		// TODO: validate $data with defined rules.
		update_option( self::$option_key, $data );

		return [
			'message' => __( 'Configuration saved successfully.', 'spider' ),
			'data'    => $data,
		];
	}
}
