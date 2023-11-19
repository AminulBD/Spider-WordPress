<?php

namespace AminulBD\Spider\WordPress\Support;

class TemplateEngine {
	static function render( $tpl, $data = [] ) {
		$file = tempnam( sys_get_temp_dir(), 'spiderwptpl' );
		file_put_contents( $file, $tpl );

		ob_start();
		extract( $data, EXTR_SKIP );

		require $file;

		$data = ob_get_clean();

		@unlink( $file );

		return $data;
	}
}
