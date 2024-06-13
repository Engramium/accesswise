<?php

namespace Engramium\Accesswise\App\Controller;

use Engramium\Accesswise\Dashboard\Settings;

// If this file is called directly, abort.
defined( 'ABSPATH' ) || exit;

/**
 * Application base class
 *
 * @author sayedulsayem
 *
 * @since 1.0.0
 */
class Base {

    use \Engramium\Accesswise\Traits\Singleton;

    /**
     * initialization function
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function init() {
        $settings = Settings::instance()->get_settings();
        if ( is_array( $settings['generals']['when_last_login'] ) && in_array( 'show_last_login', $settings['generals']['when_last_login'] ) ) {
            LastLogin::instance()->init();
        }
    }
}
