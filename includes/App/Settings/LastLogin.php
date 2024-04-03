<?php

namespace Engramium\Accesswise\App\Settings;

// If this file is called directly, abort.
defined( 'ABSPATH' ) || exit;

/**
 * Application base class
 *
 * @author sayedulsayem
 * @since 1.0.0
 */
class LastLogin {

    use \Engramium\Accesswise\Traits\Singleton;

    /**
     * initialization function
     *
     * @return void
     * @since 1.0.0
     */
    public function init() {
    }
}