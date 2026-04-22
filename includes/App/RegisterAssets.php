<?php

namespace Engramium\Accesswise\App;

// If this file is called directly, abort.
defined( 'ABSPATH' ) || exit;

/**
 * All assets register class
 *
 * @author sayedulsayem
 * @since 1.0.0
 */
class RegisterAssets {
	use \Engramium\Accesswise\Traits\Singleton;

	public function init() {
		if ( is_admin() ) {
			add_action( 'admin_enqueue_scripts', [$this, 'register'], 5 );
		} else {
			add_action( 'wp_enqueue_scripts', [$this, 'register'], 5 );
		}
	}

	/**
	 * Register our app scripts and styles
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function register() {
		$this->register_scripts( $this->get_scripts() );
		$this->register_styles( $this->get_styles() );
	}

	/**
	 * Register scripts
	 *
	 * @param  array $scripts
	 *
	 * @return void
	 * @since 1.0.0
	 */
	private function register_scripts( $scripts ) {
		foreach ( $scripts as $handle => $script ) {
			$deps      = isset( $script['deps'] ) ? $script['deps'] : false;
			$in_footer = isset( $script['in_footer'] ) ? $script['in_footer'] : false;
			$version   = isset( $script['version'] ) ? $script['version'] : ACCESSWISE_VERSION;

			wp_register_script( $handle, $script['src'], $deps, $version, $in_footer );
		}
	}

	/**
	 * Register styles
	 *
	 * @param  array $styles
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function register_styles( $styles ) {
		foreach ( $styles as $handle => $style ) {
			$deps = isset( $style['deps'] ) ? $style['deps'] : false;

			wp_register_style( $handle, $style['src'], $deps, ACCESSWISE_VERSION );
		}
	}

	/**
	 * Get all registered scripts
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function get_scripts() {
		$env  = Helper::instance()->read_env_file();
		$port = intval( Helper::instance()->get_env( 'VITE_PORT', 4000 ) );

		if ( ! empty( $env ) && ! empty( $port ) ) {
			$main_src     = "http://localhost:{$port}/src/main.js";
			$frontend_src = "http://localhost:{$port}/src/frontend/main.js";
			$version      = null; // No version query string — let Vite handle cache busting via HMR

			$this->add_script_to_auto_reload( $port );

		} else {
			$main_src     = ACCESSWISE_URL . 'dist/assets/admin.js';
			$frontend_src = ACCESSWISE_URL . 'dist/assets/frontend/frontend.js';
			$version      = ACCESSWISE_VERSION;
		}

		$scripts = [
			'accesswise-dashboard' => [
				'src'       => $main_src,
				'deps'      => ['jquery', 'wp-i18n'],
				'version'   => $version,
				'in_footer' => true
			],
			'accesswise-frontend'  => [
				'src'       => $frontend_src,
				'deps'      => ['jquery', 'wp-i18n'],
				'version'   => $version,
				'in_footer' => true
			]
		];

		return $scripts;
	}

	/**
	 * Get registered styles
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function get_styles() {
		$env  = Helper::instance()->read_env_file();
		$port = intval( Helper::instance()->get_env( 'VITE_PORT', 4000 ) );
		if ( ! empty( $env ) && ! empty( $port ) ) {
			return [];
		}

		$styles = [
			'accesswise-dashboard' => [
				'src' => ACCESSWISE_URL . 'dist/assets/admin.css'
			]
		];

		return $styles;
	}

	/**
	 * Vite hmr enable function, it won't run or inject in production
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function add_script_to_auto_reload( $port ) {
		// Inject @vite/client for HMR so Vite can reload the page when deps change
		add_action( 'admin_head', function () use ( $port ) {
			echo "<script type=\"module\" src=\"http://localhost:{$port}/@vite/client\"></script>\n";
		} );
	}
}
