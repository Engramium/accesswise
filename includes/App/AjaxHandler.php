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
		add_action( 'wp_ajax_accesswise_get_posts', [$this, 'get_posts'] );
		add_action( 'wp_ajax_accesswise_get_user_roles', [$this, 'get_user_roles'] );
	}

	public function check_nonce() {
		$status = check_ajax_referer( 'accesswise_nonce', 'nonce' );
		if ( ! $status ) {
			wp_send_json( [
				'status' => false,
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

	public function get_posts() {
		$this->check_nonce();

		$post_types = get_post_types(
			[
				'public' => true
			],
			'names'
		);

		unset( $post_types['attachment'] );

		$posts = get_posts(
			[
				'post_type'              => array_values( $post_types ),
				'post_status'            => 'publish',
				'posts_per_page'         => -1,
				'orderby'                => 'title',
				'order'                  => 'ASC',
				'suppress_filters'       => false,
				'ignore_sticky_posts'    => true,
				'update_post_term_cache' => false,
				'update_post_meta_cache' => false
			]
		);

		$formatted_posts = [];

		foreach ( $posts as $post ) {
			$post_type_object = get_post_type_object( $post->post_type );
			$post_type_label  = $post_type_object->labels->singular_name ?? $post->post_type;
			$post_title       = get_the_title( $post );
			$post_title       = ! empty( $post_title ) ? $post_title : sprintf( __( 'Untitled #%d', 'accesswise' ), $post->ID );

			$formatted_posts[ $post->ID ] = sprintf( '%s (%s)', $post_title, $post_type_label );
		}

		wp_send_json( [
			'status' => true,
			'msg'    => 'Posts get.',
			'data'   => $formatted_posts
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
