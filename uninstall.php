<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @package     OnScreenKeyboard\Uninstall
 * @author      K.Karamazen 
 * @since       1.2.3.4
 */

// If uninstall, not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// TODO: Define uninstall functionality here.

delete_option( 'on_screen_keyboard_version_field' );
delete_option( 'on_screen_keyboard_ppposts_field' );
