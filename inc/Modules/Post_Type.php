<?php

namespace AminulBD\Spider\WordPress\Modules;

use AminulBD\Spider\WordPress\Contracts\Module;

class Post_Type extends Module {
	public static string $name    = 'Post_Type';
	public static string $version = '1.0.0';
	public static string $type    = 'backend';

	public function __construct() {
		$this->add_action( 'init', [ $this, 'register' ] );
	}

	public function register() {
		register_post_type( 'spider_site', [
			'labels' => [
				'name'          => __( 'Sites', 'spider' ),
				'singular_name' => __( 'Site', 'spider' ),
			],
		] );
	}
}
