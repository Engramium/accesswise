<?php

namespace Engramium\Accesswise\Dashboard;

// If this file is called directly, abort.
defined('ABSPATH') || exit;

/**
 * Add dashboard menu class
 *
 * @author sayedulsayem
 * @since 1.0.0
 */
class AdminMenu {

    use \Engramium\Accesswise\Traits\Singleton;

    private $slug = 'accesswise-settings';

    /**
     * initialization function
     *
     * @return void
     * @since 1.0.0
     */
    public function init() {
        add_action('admin_menu', [$this, 'admin_menu']);
        add_action('in_admin_header', [$this, 'remove_all_notices'], PHP_INT_MAX);
    }

    /**
     * add admin menu function
     *
     * @return void
     * @since 1.0.0
     */
    public function admin_menu() {
        global $submenu;

        $capability = 'manage_options';

        $hook = add_menu_page(
            __('AccessWise', 'accesswise'),
            __('AccessWise', 'accesswise'),
            $capability,
            $this->slug,
            [$this, 'render_page'],
            'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHZpZXdCb3g9IjAgMCAyMCAyMCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTkuNjU3NDUgMS4zMDczOEM5LjAzNzA4IDEuMzcyMTkgOC41Njg1NiAxLjUwMzY3IDguMDY0ODUgMS43NDgxMkM3LjYxODU2IDEuOTY2NjQgNy4zMDc0NSAyLjE4NTE1IDYuOTY2NzEgMi41MjQwNEM2LjUyNDExIDIuOTYxMDggNi4xNDgxOSAzLjUzMTQ1IDUuOTg3MDggNC4wMDU1Mkw1LjkzNTIyIDQuMTU3MzhMNS45Mjk2NyA2LjE5ODEyQzUuOTI0MTEgOC40ODMzIDUuOTE4NTYgOC4zODMzIDYuMDU3NDUgOC42NjI5M0M2LjI0MjYzIDkuMDM4ODYgNi42NTc0NSA5LjI5NjI2IDcuMDgzMzcgOS4yOTYyNkM3LjUwOTMgOS4yOTYyNiA3LjkyNDExIDkuMDQwNzEgOC4xMDkzIDguNjY2NjRDOC4yNDQ0OCA4LjM5MDcxIDguMjQwNzggOC40NjY2NCA4LjI0MDc4IDYuNDM1MTVWNC42MjAzNEw4LjMxMyA0LjUwOTIzQzguNjg1MjIgMy45NDQ0MSA5LjI3NTk3IDMuNjI3NzUgOS45NjMgMy42MjQwNEMxMC4xNDYzIDMuNjIyMTkgMTAuMjMxNSAzLjYzMTQ1IDEwLjM3MDQgMy42NjQ3OEMxMC45MjYgMy44MDE4MiAxMS4zNzYgNC4wODcwMSAxMS41ODg5IDQuNDM1MTVMMTEuNjQ0NSA0LjUyNzc1TDExLjY1NzQgNi4zODg4NkMxMS42NzIzIDguNDYxMDggMTEuNjY4NiA4LjM5OTk3IDExLjc5ODIgOC42NjY2NEMxMS45ODM0IDkuMDQwNzEgMTIuMzk4MiA5LjI5NjI2IDEyLjgyNDEgOS4yOTYyNkMxMy4yNSA5LjI5NjI2IDEzLjY2NDkgOS4wNDA3MSAxMy44NSA4LjY2NDc4QzEzLjk5MjYgOC4zNzc3NSAxMy45ODcxIDguNDgxNDUgMTMuOTcyMyA2LjIzMTQ1QzEzLjk1OTMgNC4wMDE4MiAxMy45NjY3IDQuMTI0MDQgMTMuODI3OCAzLjc2NDc4QzEzLjY1NTYgMy4zMTg0OSAxMy40Mjc4IDIuOTcwMzQgMTMuMDc0MSAyLjYwMzY3QzEyLjcyOTcgMi4yNDk5NyAxMi40MDc0IDIuMDE4NDkgMTEuOTE2NyAxLjc3Nzc1QzExLjQ3NzggMS41NjEwOCAxMS4wMTg2IDEuNDE0NzggMTAuNTQ2MyAxLjM0MjU2QzEwLjM2NDkgMS4zMTI5MyA5LjgwMzc0IDEuMjkyNTYgOS42NTc0NSAxLjMwNzM4WiIgZmlsbD0iI0E3QUFBRCIvPgo8cGF0aCBkPSJNMTYuNjY0OCA5LjgzNzAzQzE2LjIzODkgMTAuNTYxMSAxNS41NDYzIDExLjIxMTEgMTQuOCAxMS41ODMzQzE0LjEwNTYgMTEuOTI5NiAxMy40ODUyIDEyLjA3NTkgMTIuNzEzIDEyLjA3NTlDMTIuMzA5MyAxMi4wNzU5IDEyLjA0MDggMTIuMDQ4MSAxMS42ODUyIDExLjk2ODVDMTEuMTI0MSAxMS44NDI2IDEwLjYyNzggMTEuNjI1OSAxMC4xMDU2IDExLjI4MTVMMTAuMDM3MSAxMS4yMzUyTDkuODA5MjkgMTEuMzg4OUM5LjU0MjYyIDExLjU2ODUgOS4wNzc4MSAxMS44IDguNzc0MTEgMTEuOTA1NUM3LjY3NDExIDEyLjI4NTIgNi40NzA0IDEyLjI0MjYgNS40MTY3IDExLjc4N0M1LjIxODU1IDExLjcwMTggNC45MTY3IDExLjU0MDcgNC43NTAwMyAxMS40Mjk2QzQuNTkwNzcgMTEuMzI0MSA0LjUxNDg1IDExLjI3MDQgNC4yOTA3NyAxMS4wOTYzQzQuMjM4OTIgMTEuMDU1NSAzLjc1OTI5IDEwLjU3NTkgMy43MTg1NSAxMC41MjQxQzMuNTQ0NDggMTAuMjk4MSAzLjQ5MDc3IDEwLjIyNDEgMy4zODUyMiAxMC4wNjQ4QzMuMzE4NTUgOS45NjI5NiAzLjI0NDQ4IDkuODQ0NDQgMy4yMjIyNSA5Ljc5OTk5QzMuMTkyNjIgOS43NDgxNCAzLjE3NDExIDkuNzI5NjIgMy4xNjQ4NSA5Ljc0NDQ0QzMuMTU3NDQgOS43NTc0IDMuMTMzMzcgOS45MjU5MiAzLjExMjk5IDEwLjEyMDRDMi44NjQ4NSAxMi4zNTU1IDMuMjQ2MzMgMTQuMTIwNCA0LjMyNTk2IDE1Ljc0NjNDNC41NTE4OCAxNi4wODMzIDQuNjg4OTIgMTYuMjYxMSA0Ljk4ODkyIDE2LjU5MDdDNS45Njg1NSAxNy42NzA0IDcuMjA3NDQgMTguNDM4OSA4LjQ2NjcgMTguNzQ4MUMxMC42NDgyIDE5LjI4NTIgMTIuODQwOCAxOC42MDU1IDE0LjU5MjYgMTYuODUxOEMxNC45NjY3IDE2LjQ3NzggMTUuMjMzNCAxNi4xNTc0IDE1LjQ5MDggMTUuNzY4NUMxNi4zMjc4IDE0LjUwOTMgMTYuNzM3MSAxMy4yMzMzIDE2LjgzNTIgMTEuNTc0MUMxNi44NjY3IDExLjA0NjMgMTYuODI3OCA5Ljc4MzMzIDE2Ljc3OTcgOS42ODg4OEMxNi43NzA0IDkuNjc0MDcgMTYuNzI5NyA5LjcyOTYyIDE2LjY2NDggOS44MzcwM1oiIGZpbGw9IiNBN0FBQUQiLz4KPC9zdmc+Cg==',
            30
        );

        if (current_user_can($capability)) {
            $submenu[$this->slug][] = array(__('Welcome', 'accesswise'), $capability, 'admin.php?page=' . $this->slug . '#/');
            $submenu[$this->slug][] = array(__('Settings', 'accesswise'), $capability, 'admin.php?page=' . $this->slug . '#/settings');
        }
    }

    /**
     * render html in menu function
     *
     * @return void
     * @since 1.0.0
     */
    public function render_page() {
        echo '<div class="wrap"><div id="accesswise-dashboard-app"></div></div>';
    }

    /**
     * remove all notices from menu function
     *
     * @return void
     * @since 1.0.0
     */
    public function remove_all_notices() {
        if (!$this->is_page()) return;
        remove_all_actions('admin_notices');
        remove_all_actions('all_admin_notices');
        remove_all_actions('user_admin_notices');
    }

    /**
     * is menu page check function
     *
     * @return boolean
     * @since 1.0.0
     */
    public function is_page() {
        return (isset($_GET['page']) && (sanitize_text_field($_GET['page']) === $this->slug));
    }
}
