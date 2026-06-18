<?php

namespace Engramium\Accesswise\App;

// If this file is called directly, abort.
defined( 'ABSPATH' ) || exit;

/**
 * Any kind of helper class
 *
 *
 * @author sayedulsayem
 * @since 1.0.0
 */
class Helper {

	use \Engramium\Accesswise\Traits\Singleton;

	private $env_data = null;

	/**
	 * env file read function
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function read_env_file() {
		if ( null !== $this->env_data ) {
			return $this->env_data;
		}

		$env_data = [];

		$env_file = ACCESSWISE_PATH . '.env';

		if ( ! is_readable( $env_file ) ) {
			$this->env_data = $env_data;

			return $this->env_data;
		}

		$lines = file( $env_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES );

		foreach ( $lines as $line ) {
			$line = trim( $this->strip_utf8_bom( $line ) );

			if ( '' === $line || 0 === strpos( $line, '#' ) ) {
				continue;
			}

			if ( 0 === strpos( $line, 'export ' ) ) {
				$line = trim( substr( $line, 7 ) );
			}

			if ( false === strpos( $line, '=' ) ) {
				continue;
			}

			list( $key, $value ) = explode( '=', $line, 2 );
			$key   = trim( $key );
			$value = $this->normalize_env_value( $value );

			if ( '' !== $key && ! array_key_exists( $key, $env_data ) ) {
				$env_data[ $key ] = $value;
			}
		}

		$this->env_data = $env_data;

		return $this->env_data;
	}

	public function get_env( $key, $default = null ) {
		$env = $this->read_env_file();

		return array_key_exists( $key, $env ) ? $env[ $key ] : $default;
	}

	private function strip_utf8_bom( $value ) {
		return preg_replace( '/^\xEF\xBB\xBF/', '', (string) $value );
	}

	private function normalize_env_value( $value ) {
		$value = trim( (string) $value );

		if ( '' === $value ) {
			return '';
		}

		$first_char = substr( $value, 0, 1 );
		$last_char  = substr( $value, -1 );

		if ( ( '"' === $first_char && '"' === $last_char ) || ( "'" === $first_char && "'" === $last_char ) ) {
			$value = substr( $value, 1, -1 );
		} else {
			$value = preg_replace( '/\s+#.*$/', '', $value );
			$value = trim( $value );
		}

		return strtr(
			$value,
			[
				'\n' => "\n",
				'\r' => "\r",
				'\t' => "\t",
				'\"' => '"',
				"\'" => "'",
			]
		);
	}
}
