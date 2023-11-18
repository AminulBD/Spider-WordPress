<?php

namespace AminulBD\Spider\WordPress;

use AminulBD\Spider\WordPress\Modules\{
	Admin_UI, Post_Type
};
use AminulBD\Spider\WordPress\Modules\API\V1\{
	Config, Site
};

final class Plugin {
	public string       $version = '1.0.0';
	private array       $modules = [];
	private static self $instance;

	public function __construct( array $args = [] ) {
		$this->version = $args[ 'version' ] ?? $this->version;

		add_action( 'init', [ $this, 'i18n' ] );

		$this->load_modules();
	}

	public static function init( $args ): Plugin {
		if ( !isset( self::$instance ) ) {
			self::$instance = new self( $args );
		}

		return self::$instance;
	}

	public function i18n(): void {
		load_plugin_textdomain( 'spider', false, SPIDER_PATH . '/languages' );
	}

	private function load_modules() {
		$core = [
			Admin_UI::class,
			Post_Type::class,
			Config::class,
			Site::class,
		];

		$custom  = apply_filters( 'spider_modules', [] );
		$modules = array_merge( $core, $custom );
		$args    = [
			'version' => $this->version,
		];

		foreach ( $modules as $module ) {
			if ( $module::$type === 'backend' && is_admin() ) {
				$load = new $module( $args );
				$load->boot();
			} else if ( $module::$type === 'frontend' && !is_admin() ) {
				$load = new $module( $args );
				$load->boot();
			} else if ( $module::$type === 'any' ) {
				$load = new $module( $args );
				$load->boot();
			} else {
				continue;
			}

			$this->modules[ $module::$name ] = $load;
		}
	}

	public function module( string $name ) {
		return $this->modules[ $name ] ?? null;
	}
}
