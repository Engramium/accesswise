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
		if ( ! empty( $this->protection_settings ) ) {
			$opts = $this->protection_settings;

			$i18n['copyProtection']             = ! empty( $opts['copy_protection'] );
			$i18n['copyProtectionMsg']          = $opts['cp_msg'] ?? '';
			$i18n['copyProtectionExcludeRoles'] = $opts['cp_exclude_roles'] ?? [];
			$i18n['copyProtectionExcludePosts'] = $opts['cp_exclude_posts'] ?? [];

			$i18n['disableRightClick']          = ! empty( $opts['right_click'] );
			$i18n['disableRightClickMsg']       = $opts['rc_disable_msg'] ?? '';
			$i18n['rightClickExcludeRoles']     = $opts['rc_exclude_roles'] ?? [];
			$i18n['rightClickExcludePosts']     = $opts['rc_exclude_posts'] ?? [];

			$i18n['disableKeys']                = $opts['rc_disable_keys'] ?? [];
			$i18n['currentUserRole']            = $this->current_user_role();
			$i18n['currentPostType']            = get_post_type();
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
