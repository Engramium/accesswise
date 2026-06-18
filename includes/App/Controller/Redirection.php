<?php

namespace Engramium\Accesswise\App\Controller;

// If this file is called directly, abort.
defined( 'ABSPATH' ) || exit;

/**
 * Application base class
 *
 * @author sayedulsayem
 *
 * @since 1.0.0
 */
class Redirection {

	use \Engramium\Accesswise\Traits\Singleton;

	private $general_settings;
	private $restriction_settings;

	/**
	 * initialization function
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function init() {
		$this->general_settings     = Base::instance()->settings['generals'] ?? [];
		$this->restriction_settings = Base::instance()->settings['restrictions'] ?? [];

		if ( isset( $this->general_settings['redirection_after_login'] ) && 'default' !== $this->general_settings['redirection_after_login'] ) {
			add_filter( 'login_redirect', [$this, 'login_redirection'], PHP_INT_MAX, 3 );
		}

		if ( isset( $this->general_settings['redirection_after_logout'] ) && 'default' !== $this->general_settings['redirection_after_logout'] ) {
			add_action( 'wp_logout', [$this, 'logout_redirection'], PHP_INT_MAX );
		}

		if ( isset( $this->restriction_settings['private_website'] ) && is_array( $this->restriction_settings['private_website'] ) && in_array( 'logged_in_users', $this->restriction_settings['private_website'] ) ) {
			add_action( 'template_redirect', [$this, 'restrict_access_to_logged_in_users'], PHP_INT_MAX );
		}

	}

	public function login_redirection( $redirect_to, $request, $user ) {
		if ( isset( $user->roles ) && is_array( $user->roles ) ) {
			if ( in_array( 'subscriber', $user->roles ) ) {
				$redirect_to = get_permalink( $this->general_settings['redirection_after_login'] );
			}
		}

		return $redirect_to;
	}

	public function logout_redirection() {
		wp_redirect( get_permalink( $this->general_settings['redirection_after_logout'] ) );
		exit();
	}

	public function restrict_access_to_logged_in_users() {
		if ( ! is_user_logged_in() && ! is_admin() ) {
			$redirect = true;

			$str_pub_contents   = $this->restriction_settings['public_website_contents'] ?? '';
			$array_pub_contents = ( ! empty( $this->restriction_settings['public_website_contents'] ) ) ? explode( PHP_EOL, $str_pub_contents ) : [];

			$allowed_pages = ['wp-login.php', 'register', 'password-reset'];

			global $pagenow;

			if ( in_array( $pagenow, $allowed_pages ) ) {
				$redirect = false;
			}

			global $wp;
			$request      = $wp->request;
			$request_uri  = isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '';
			$current_url  = home_url( $request_uri );
			$request_path = trim( (string) wp_parse_url( $request_uri, PHP_URL_PATH ), '/' );

			foreach ( $array_pub_contents as $content ) {
				if ( $this->is_public_content_match( $content, $current_url, $request, $request_path ) ) {
					$redirect = false;
					break;
				}
			}

			if ( $redirect ) {
				wp_redirect( wp_login_url() );
				exit();
			}
		}
	}

	private function is_public_content_match( $content, $current_url, $request, $request_path ) {
		$content = trim( (string) $content );

		if ( '' === $content ) {
			return false;
		}

		if ( filter_var( $content, FILTER_VALIDATE_URL ) ) {
			$normalized_content_url = untrailingslashit( $content );
			$normalized_current_url = untrailingslashit( $current_url );

			if ( 0 === strpos( $normalized_current_url, $normalized_content_url ) ) {
				return true;
			}

			$content_path = trim( (string) wp_parse_url( $content, PHP_URL_PATH ), '/' );

			if ( '' !== $content_path ) {
				return false !== strpos( $request, $content_path ) || false !== strpos( $request_path, $content_path );
			}

			return false;
		}

		$normalized_content = trim( (string) wp_parse_url( $content, PHP_URL_PATH ), '/' );

		if ( '' === $normalized_content ) {
			$normalized_content = trim( $content, '/' );
		}

		return false !== strpos( $request, $normalized_content ) || false !== strpos( $request_path, $normalized_content );
	}
}
