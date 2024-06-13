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
class Toolbar {

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
        add_action( 'after_setup_theme', [$this, 'hide_admin_bar'], PHP_INT_MAX );
    }

    public function hide_admin_bar() {
        if ( current_user_can( 'administrator' ) && is_array( $this->general_settings['toolbar'] ) && in_array( 'logged_in_admins', $this->general_settings['toolbar'] ) ) {
            add_filter( 'show_admin_bar', '__return_false' );
        }
        if ( ( ! current_user_can( 'administrator' ) ) && is_array( $this->general_settings['toolbar'] ) && in_array( 'logged_in_members', $this->general_settings['toolbar'] ) ) {
            add_filter( 'show_admin_bar', '__return_false' );
        }
    }
}
