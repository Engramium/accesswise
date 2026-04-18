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
	}

	public function modify_i18n_to_enable_protection( $i18n ) {
		if ( isset( $this->protection_settings['right_click'] ) && is_array( $this->protection_settings['right_click'] ) ) {
			$i18n['disableRightClick']    = in_array( 'disable_right_click', $this->protection_settings['right_click'], true );
			$i18n['disableRightClickMsg'] = $this->protection_settings['disable_right_click_msg'] ?? '';
			$i18n['disableCopy']          = in_array( 'disable_copy', $this->protection_settings['right_click'], true );
			$i18n['disableCopyMsg']       = $this->protection_settings['disable_copy_msg'] ?? '';
			$i18n['disableKeys']          = $this->protection_settings['disable_keys'] ?? [];
			$i18n['excludedUserRoles']    = $this->protection_settings['right_click_exclude_roles'] ?? [];
			$i18n['excludedPostTypes']    = $this->protection_settings['right_click_exclude_posts'] ?? [];
			$i18n['currentUserRole']      = $this->current_user_role();
			$i18n['currentPostType']      = get_post_type();
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
}
