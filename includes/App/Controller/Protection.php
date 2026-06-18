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
class Protection {

	use \Engramium\Accesswise\Traits\Singleton;

	private $protection_settings;

	/**
	 * initialization function
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function init() {
		$this->protection_settings = Base::instance()->settings['protections'] ?? [];
		add_filter( 'accesswise/frontend/i18n', [$this, 'modify_i18n_to_enable_protection'], 20, 1 );

		if ( ! empty( $this->protection_settings['cp_protect_no_js'] ) || ! empty( $this->protection_settings['rc_protect_no_js'] ) ) {
			add_action( 'wp_footer', [$this, 'render_no_js_protection'] );
		}
	}

	public function modify_i18n_to_enable_protection( $i18n ) {
		if ( ! empty( $this->protection_settings ) ) {
			$opts = $this->protection_settings;

			$i18n['copyProtection']             = ! empty( $opts['copy_protection'] );
			$i18n['copyProtectionMsg']          = $opts['cp_msg'] ?? '';
			$i18n['copyProtectionExcludeRoles'] = $opts['cp_exclude_roles'] ?? [];
			$i18n['copyProtectionExcludePosts'] = $opts['cp_exclude_posts'] ?? [];
			$i18n['copyProtectionExcludeIds']   = array_map( 'intval', $opts['cp_exclude_individual_posts'] ?? [] );
			$i18n['copyProtectionAllowSelect']  = ! empty( $opts['cp_text_selection'] );
			$i18n['copyProtectionExcludeInputs'] = ! empty( $opts['cp_exclude_inputs'] );
			$i18n['copyProtectionExcludeSelectors'] = $this->normalize_lines_to_array( $opts['cp_exclude_css_selector'] ?? '' );

			$i18n['disableRightClick']          = ! empty( $opts['right_click'] );
			$i18n['disableRightClickMsg']       = $opts['rc_disable_msg'] ?? '';
			$i18n['rightClickExcludeRoles']     = $opts['rc_exclude_roles'] ?? [];
			$i18n['rightClickExcludePosts']     = $opts['rc_exclude_posts'] ?? [];
			$i18n['rightClickProtectIds']       = array_map( 'intval', $opts['rc_protect_individual_posts'] ?? [] );
			$i18n['rightClickDisableImages']    = ! empty( $opts['rc_disable_images'] );
			$i18n['rightClickDisableLinks']     = ! empty( $opts['rc_disable_links'] );
			$i18n['rightClickDisableDevKeys']   = ! empty( $opts['rc_disable_dev_keys'] );
			$i18n['rightClickDisableDragDrop']  = ! empty( $opts['rc_disable_drag_drop'] );
			$i18n['rightClickDisableLeftClick'] = ! empty( $opts['rc_disable_left_click'] );
			$i18n['rightClickDisableScrollMobile'] = ! empty( $opts['rc_disable_scroll_img_mobile'] );
			$i18n['rightClickEnableCopyright']  = ! empty( $opts['rc_enable_copyright'] );
			$i18n['rightClickCopyrightText']    = $opts['rc_copyright_msg'] ?? '';
			$i18n['rightClickEnablePasting']    = ! empty( $opts['rc_enable_pasting'] );
			$i18n['rightClickPastingText']      = $opts['rc_pasting_msg'] ?? '';

			$i18n['rightClickDisableKeys']      = $opts['rc_disable_keys'] ?? [];
			$i18n['currentUserRole']            = $this->current_user_role();
			$i18n['currentPostType']            = get_post_type();
			$i18n['currentPostId']              = get_queried_object_id();
		}

		return $i18n;
	}

	public function current_user_role() {
		$current_user = wp_get_current_user();

		if ( ! empty( $current_user->roles ) && is_array( $current_user->roles ) ) {
			$user_roles = $current_user->roles;
			$user_role  = $user_roles[0];
		} else {
			$user_role = 'none';
		}

		return $user_role;
	}

	public function render_no_js_protection() {
		$messages = [];

		if ( $this->should_apply_copy_protection() && ! empty( $this->protection_settings['cp_protect_no_js'] ) ) {
			$messages[] = $this->protection_settings['cp_no_js_msg'] ?? '';
		}

		if ( $this->should_apply_right_click_protection() && ! empty( $this->protection_settings['rc_protect_no_js'] ) ) {
			$messages[] = $this->protection_settings['rc_no_js_msg'] ?? '';
		}

		$messages = array_filter( array_unique( array_map( 'trim', $messages ) ) );

		if ( empty( $messages ) ) {
			return;
		}

		$message = implode( ' ', $messages );
		?>
		<div id="accesswise-no-js-message" class="accesswise-no-js-message" style="display:none;">
			<?php echo esc_html( $message ); ?>
		</div>
		<noscript>
			<style>
				body > *:not(#accesswise-no-js-message) {
					display: none !important;
				}

				#accesswise-no-js-message {
					position: fixed;
					inset: 0;
					z-index: 999999;
					display: flex !important;
					align-items: center;
					justify-content: center;
					padding: 24px;
					background: #111827;
					color: #ffffff;
					text-align: center;
					font-size: 20px;
					line-height: 1.5;
				}
			</style>
		</noscript>
		<?php
	}

	private function should_apply_copy_protection() {
		if ( empty( $this->protection_settings['copy_protection'] ) ) {
			return false;
		}

		if ( in_array( $this->current_user_role(), $this->protection_settings['cp_exclude_roles'] ?? [], true ) ) {
			return false;
		}

		if ( in_array( get_post_type(), $this->protection_settings['cp_exclude_posts'] ?? [], true ) ) {
			return false;
		}

		$current_post_id = get_queried_object_id();

		if ( $current_post_id && in_array( $current_post_id, array_map( 'intval', $this->protection_settings['cp_exclude_individual_posts'] ?? [] ), true ) ) {
			return false;
		}

		return true;
	}

	private function should_apply_right_click_protection() {
		if ( empty( $this->protection_settings['right_click'] ) ) {
			return false;
		}

		if ( in_array( $this->current_user_role(), $this->protection_settings['rc_exclude_roles'] ?? [], true ) ) {
			return false;
		}

		if ( in_array( get_post_type(), $this->protection_settings['rc_exclude_posts'] ?? [], true ) ) {
			return false;
		}

		$protected_ids   = array_map( 'intval', $this->protection_settings['rc_protect_individual_posts'] ?? [] );
		$current_post_id = get_queried_object_id();

		if ( ! empty( $protected_ids ) ) {
			return $current_post_id && in_array( $current_post_id, $protected_ids, true );
		}

		return true;
	}

	private function normalize_lines_to_array( $value ) {
		$lines = preg_split( '/\r\n|\r|\n/', (string) $value );
		$lines = array_map( 'trim', $lines );

		return array_values( array_filter( $lines ) );
	}
}
