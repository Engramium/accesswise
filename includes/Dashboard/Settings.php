<?php

namespace Engramium\Accesswise\Dashboard;

// If this file is called directly, abort.
defined( 'ABSPATH' ) || exit;

/**
 * Dashboard base class
 *
 * @author sayedulsayem
 * @since 1.0.0
 */
class Settings {

	use \Engramium\Accesswise\Traits\Singleton;

	public $settings_key = 'accesswise_settings';

	public function get_settings() {
		$default_settings = $this->get_default_settings();
		$current_settings = get_option( $this->settings_key, [] );

		if ( empty( $current_settings ) ) {
			return $default_settings;
		}

		return $current_settings;
	}

	public function update_settings( $data ) {
		$default_settings = $this->get_default_settings();

		$filtered_data = $this->filter_inputs( $data, $default_settings );

		$sanitized_data   = $this->sanitize_inputs( $filtered_data );
		$current_settings = update_option( $this->settings_key, $sanitized_data, true );

		return $current_settings;
	}

	public function sanitize_inputs( $inputs ) {
		$text_areas = ['public_website_contents'];
		foreach ( $inputs as $key => &$value ) {
			if ( is_array( $value ) || is_object( $value ) ) {
				$value = $this->sanitize_inputs( $value );
			} else if ( in_array( $key, $text_areas ) ) {
				$value = sanitize_textarea_field( $value );
			} else {
				$value = sanitize_text_field( $value );
			}
		}

		return $inputs;
	}

	public function get_default_settings() {
		return [
			'generals' => [
				'toolbar'                   => ['show_for_admins', 'show_for_non_admins'],
				'redirection_after_login'   => 'default',
				'redirection_after_logout'  => 'default',
				'private_website'           => [],
				'public_website_contents'   => '',
				'when_last_login'           => [],
				'right_click'               => [],
				'disable_keys'              => [],
				'disable_right_click_msg'   => 'Right click is disabled!',
				'disable_copy_msg'          => 'Cut/Copy/Paste is disabled!',
				'right_click_exclude_posts' => [],
				'right_click_exclude_roles' => ['administrator']
			]
		];
	}

	public function filter_inputs( $input_data, $allowed_structure ) {
		$filtered_data = [];

		foreach ( $allowed_structure['generals'] as $key => $value ) {
			if ( array_key_exists( $key, $input_data['generals'] ) ) {
				$filtered_data['generals'][$key] = $input_data['generals'][$key];
			}
		}

		return $filtered_data;
	}

}
