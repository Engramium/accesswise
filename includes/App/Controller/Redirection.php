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

	/**
	 * initialization function
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function init() {
		$this->general_settings = Base::instance()->settings['generals'];

		if ( isset( $this->general_settings['redirection_after_login'] ) && 'default' !== $this->general_settings['redirection_after_login'] ) {
			add_filter( 'login_redirect', [$this, 'login_redirection'], PHP_INT_MAX, 3 );
		}

		if ( isset( $this->general_settings['redirection_after_logout'] ) && 'default' !== $this->general_settings['redirection_after_logout'] ) {
			add_action( 'wp_logout', [$this, 'logout_redirection'], PHP_INT_MAX );
		}

		if ( isset( $this->general_settings['private_website'] ) && is_array( $this->general_settings['private_website'] ) && in_array( 'logged_in_users', $this->general_settings['private_website'] ) ) {
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

			$str_pub_contents   = $this->general_settings['public_website_contents'] ?? '';
			$array_pub_contents = ( ! empty( $this->general_settings['public_website_contents'] ) ) ? explode( PHP_EOL, $str_pub_contents ) : [];

			$allowed_pages = ['wp-login.php', 'register', 'password-reset'];

			global $pagenow;

			if ( in_array( $pagenow, $allowed_pages ) ) {
				$redirect = false;
			}

			global $wp;
			$request = $wp->request;

			foreach ( $array_pub_contents as $content ) {
				$content = str_replace('/', '', $content);
				if ( strpos( $request, $content ) !== false ) {
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
}
