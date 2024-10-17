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
		add_filter( 'accesswise/frontend/i18n', [$this, 'modify_i18n_to_enable_protection'], 20, 1 );
	}

	public function modify_i18n_to_enable_protection( $i18n ) {
		if ( ! current_user_can( 'administrator' ) && isset( $this->general_settings['right_click'] ) && is_array( $this->general_settings['right_click'] ) ) {
			$i18n['disableRightClick']    = in_array( 'disable_right_click', $this->general_settings['right_click'], true );
			$i18n['disableRightClickMsg'] = $this->general_settings['disable_right_click_msg'] ?? '';
			$i18n['disableCopy']          = in_array( 'disable_copy', $this->general_settings['right_click'], true );
			$i18n['disableCopyMsg']       = $this->general_settings['disable_copy_msg'] ?? '';
		}

		return $i18n;
	}

}
