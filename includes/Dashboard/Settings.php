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

		return $this->merge_settings( $default_settings, $current_settings );
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
		$booleans   = ['copy_protection', 'right_click', 'cp_text_selection', 'cp_exclude_inputs', 'cp_protect_no_js', 'rc_disable_images', 'rc_disable_links', 'rc_disable_dev_keys', 'rc_disable_drag_drop', 'rc_disable_left_click', 'rc_disable_scroll_img_mobile', 'rc_protect_no_js', 'rc_enable_copyright', 'rc_enable_pasting'];
		foreach ( $inputs as $key => &$value ) {
			if ( is_array( $value ) || is_object( $value ) ) {
				$value = $this->sanitize_inputs( $value );
			} else if ( in_array( $key, $text_areas ) ) {
				$value = sanitize_textarea_field( $value );
			} else if ( in_array( $key, $booleans ) ) {
				$value = rest_sanitize_boolean( $value );
			} else {
				$value = sanitize_text_field( $value );
			}
		}

		return $inputs;
	}

	public function get_default_settings() {
		return [
			'generals'     => [
				'toolbar'                  => ['show_for_admins', 'show_for_non_admins'],
				'redirection_after_login'  => 'default',
				'redirection_after_logout' => 'default',
				'when_last_login'          => []
			],
			'protections'  => [
				// Copy protection
				'copy_protection'              => false,
				'cp_exclude_posts'             => [],
				'cp_exclude_individual_posts'  => [],
				'cp_exclude_roles'             => ['administrator'],
				'cp_text_selection'            => false,
				'cp_exclude_inputs'            => false,
				'cp_exclude_css_selector'      => '',
				'cp_msg'                       => 'Cut/Copy/Paste is disabled!',
				'cp_protect_no_js'             => false,
				'cp_no_js_msg'                 => 'Javascript is disabled!',
				// Disable Right click
				'right_click'                  => false,
				'rc_exclude_posts'             => [],
				'rc_disable_images'            => false,
				'rc_disable_links'             => false,
				'rc_disable_dev_keys'          => false,
				'rc_disable_drag_drop'         => false,
				'rc_disable_keys'              => [],
				'rc_disable_left_click'        => false,
				'rc_disable_scroll_img_mobile' => false,
				'rc_disable_msg'               => 'Right click is disabled!',
				'rc_protect_no_js'             => false,
				'rc_no_js_msg'                 => 'Javascript is disabled!',
				'rc_enable_copyright'          => false,
				'rc_copyright_msg'             => '',
				'rc_enable_pasting'            => false,
				'rc_pasting_msg'               => '',
				'rc_exclude_roles'             => ['administrator'],
				'rc_protect_individual_posts'  => []
			],
			'restrictions' => [
				'private_website'         => [],
				'public_website_contents' => ''
			]
		];
	}

	public function filter_inputs( $input_data, $allowed_structure ) {
		$filtered_data = [];

		foreach ( $allowed_structure as $section => $section_defaults ) {
			if ( ! isset( $input_data[$section] ) || ! is_array( $input_data[$section] ) ) {
				continue;
			}
			foreach ( $section_defaults as $key => $value ) {
				if ( array_key_exists( $key, $input_data[$section] ) ) {
					$filtered_data[$section][$key] = $input_data[$section][$key];
				} elseif ( is_array( $value ) && ! $this->is_assoc_array( $value ) ) {
					// Empty checkbox/multiselect arrays are omitted from FormData, so preserve explicit clears as [].
					$filtered_data[$section][$key] = [];
				}
			}
		}

		return $filtered_data;
	}

	private function merge_settings( $defaults, $current ) {
		foreach ( $defaults as $key => $value ) {
			if ( ! array_key_exists( $key, $current ) ) {
				$current[ $key ] = $value;
				continue;
			}

			if ( is_array( $value ) && is_array( $current[ $key ] ) && $this->is_assoc_array( $value ) ) {
				$current[ $key ] = $this->merge_settings( $value, $current[ $key ] );
			}
		}

		return $current;
	}

	private function is_assoc_array( $array ) {
		if ( [] === $array ) {
			return false;
		}

		return array_keys( $array ) !== range( 0, count( $array ) - 1 );
	}

}
