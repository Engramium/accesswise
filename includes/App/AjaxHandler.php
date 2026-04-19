<?php

namespace Engramium\Accesswise\App;

use Engramium\Accesswise\Dashboard\Settings;

// If this file is called directly, abort.
defined( 'ABSPATH' ) || exit;

/**
 * Ajax Handler class
 *
 * @author sayedulsayem
 * @since 1.0.0
 */
class AjaxHandler {

	use \Engramium\Accesswise\Traits\Singleton;

	/**
	 * initialization function
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function init() {
		add_action( 'wp_ajax_accesswise_update_settings', [$this, 'update_settings'] );
		add_action( 'wp_ajax_accesswise_get_settings', [$this, 'get_settings'] );
		add_action( 'wp_ajax_accesswise_get_post_types', [$this, 'get_post_types'] );
		add_action( 'wp_ajax_accesswise_get_user_roles', [$this, 'get_user_roles'] );
	}

	public function check_nonce() {
		$status = check_ajax_referer( 'accesswise_nonce', 'nonce' );
		if ( ! $status ) {
			wp_send_json( [
				'status' => true,
				'msg'    => 'Unauthorized Request',
				'data'   => []
			] );
		}
	}

	public function update_settings() {
		$this->check_nonce();
		$request = $_REQUEST;
		$status  = Settings::instance()->update_settings( $request['settings'] ?? [] );
		$msg     = $status ? 'Settings updated.' : 'Settings nothing to update/ failed.';
		wp_send_json( [
			'status' => $status,
			'msg'    => $msg,
			'data'   => $request['settings'] ?? []
		] );
	}

	public function get_settings() {
		$this->check_nonce();
		wp_send_json( [
			'status' => true,
			'msg'    => 'Settings get.',
			'data'   => Settings::instance()->get_settings()
		] );
	}

	public function get_post_types() {
		$this->check_nonce();

		$public_post_types = get_post_types( [
			'public' => true
		], 'objects' );

		unset( $public_post_types['attachment'] );

		wp_send_json( [
			'status' => true,
			'msg'    => 'Post types get.',
			'data'   => $public_post_types
		] );
	}

	public function get_user_roles() {
		$this->check_nonce();

		$wp_roles = wp_roles();

		wp_send_json( [
			'status' => true,
			'msg'    => 'User roles get.',
			'data'   => $wp_roles->get_names()
		] );
	}
}
