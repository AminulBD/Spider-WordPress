<?php

namespace AminulBD\Spider\WordPress\Support;

class TemplateEngine {
	static array $blocks = [];

	static function view( $tpl, $data = [] ) {
		$file = self::cache( $tpl );
		ob_start();
		extract( $data, EXTR_SKIP );

		require $file;

		return ob_get_clean();
	}

	static function cache( $tpl ) {
		$file = tempnam( sys_get_temp_dir(), 'spiderwptpl' );
		$code = self::compileCode( $tpl );
		file_put_contents( $file, '<?php class_exists(\'' . __CLASS__ . '\') or exit; ?>' . PHP_EOL . $code );

		return $file;
	}

	static function clearCache() {
		foreach ( glob( sys_get_temp_dir() . '/spiderwptpl*' ) as $file ) {
			unlink( $file );
		}
	}

	static function compileCode( $code ) {
		$code = self::compileBlock( $code );
		$code = self::compileYield( $code );
		$code = self::compileEscapedEchos( $code );
		$code = self::compileEchos( $code );

		return self::compilePHP( $code );
	}

	static function compilePHP( $code ) {
		return preg_replace( '~\{%\s*(.+?)\s*\%}~is', '<?php $1 ?>', $code );
	}

	static function compileEchos( $code ) {
		return preg_replace( '~\{{\s*(.+?)\s*\}}~is', '<?php echo $1 ?>', $code );
	}

	static function compileEscapedEchos( $code ) {
		return preg_replace( '~\{{{\s*(.+?)\s*\}}}~is', '<?php echo htmlentities($1, ENT_QUOTES, \'UTF-8\') ?>', $code );
	}

	static function compileBlock( $code ) {
		preg_match_all( '/{% ?block ?(.*?) ?%}(.*?){% ?endblock ?%}/is', $code, $matches, PREG_SET_ORDER );
		foreach ( $matches as $value ) {
			if ( !array_key_exists( $value[ 1 ], self::$blocks ) ) self::$blocks[ $value[ 1 ] ] = '';
			if ( strpos( $value[ 2 ], '@parent' ) === false ) {
				self::$blocks[ $value[ 1 ] ] = $value[ 2 ];
			} else {
				self::$blocks[ $value[ 1 ] ] = str_replace( '@parent', self::$blocks[ $value[ 1 ] ], $value[ 2 ] );
			}
			$code = str_replace( $value[ 0 ], '', $code );
		}

		return $code;
	}

	static function compileYield( $code ) {
		foreach ( self::$blocks as $block => $value ) {
			$code = preg_replace( '/{% ?yield ?' . $block . ' ?%}/', $value, $code );
		}

		return preg_replace( '/{% ?yield ?(.*?) ?%}/i', '', $code );
	}
}
