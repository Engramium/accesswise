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
        add_action( 'wp_head', [$this, 'insert_in_header'] );
        add_action( 'wp_footer', [$this, 'insert_in_footer'] );
    }

    public function insert_in_header() {
        if ( ! current_user_can( 'administrator' ) ) {
            if ( isset( $this->general_settings['right_click'] ) && is_array( $this->general_settings['right_click'] ) && in_array( 'disable_copy', $this->general_settings['right_click'] ) ) {
                echo "
                <style type='text/css'>
                    body {
                        -webkit-user-select: none; /* Safari */
                        -moz-user-select: none;    /* Firefox */
                        -ms-user-select: none;     /* Internet Explorer/Edge */
                        user-select: none;         /* Non-prefixed version, currently supported by Chrome, Opera and Firefox */
                    }
                </style>
                ";
            }
        }
    }

    public function insert_in_footer() {
        if ( ! current_user_can( 'administrator' ) && isset( $this->general_settings['right_click'] ) && is_array( $this->general_settings['right_click'] ) ) {
            if ( in_array( 'disable_copy', $this->general_settings['right_click'] ) ) {
				$disable_right_click_msg = $this->general_settings['disable_right_click_msg'] ??  esc_attr__('Right click is disabled!', 'accesswise');
                echo "
                <script type='text/javascript'>
					function showMessage(msg) {
						alert(msg);
					}
                    document.addEventListener('DOMContentLoaded', function() {

                        document.addEventListener('selectstart', function(e) {
                            e.preventDefault();
							showMessage({$disable_right_click_msg});
                        });

                        document.addEventListener('dragstart', function(e) {
                            e.preventDefault();
							showMessage({$disable_right_click_msg});
                        });

                        document.addEventListener('keydown', function(e) {
                            const forbiddenKeys = ['U', 'S', 'C', 'X'];

                            if ((e.ctrlKey || e.metaKey) && forbiddenKeys.includes(e.key.toUpperCase())) {
                                e.preventDefault();
								showMessage({$disable_right_click_msg});
                            }

                            if (e.key === 'F12') {
                                e.preventDefault();
								showMessage({$disable_right_click_msg});
                            }
                        });
                    });
                </script>
                ";
            }
            if ( in_array( 'disable_right_click', $this->general_settings['right_click'] ) ) {
				$disable_copy_msg = $this->general_settings['disable_copy_msg'] ??  esc_attr__('Cut/Copy/Paste is disabled!', 'accesswise');
                echo "
                <script type='text/javascript'>
					function showMessage(msg) {
						alert(msg);
					}
                    document.addEventListener('DOMContentLoaded', function() {
                        document.addEventListener('contextmenu', function(e) {
                            e.preventDefault();
							showMessage({$disable_right_click_msg});
                        });
                    });
                </script>
                ";
            }
        }
    }

}
