<?php
/*
 * Plugin Name: Onscreen Keyboard
 * Plugin URI: https://wordpress.org/plugins/on-screen-keyboard/
 * Description: This plugin adds an onscreen keyboard powered by Javascript/JQuery, ported from github by Iain Redmill, now working within WordPress, allowing mouse based input or input when working from a touch screen device.  Keyboard settings etc can be modified by editing the file.  To add a keyboard, enter <b>[oskb]</b> within your page where you wish it to appear. There are additional options for: password protected posts.
 * Version: 1.3.10
 * Author: Ported by Iain Redmill, K.Karamazen
 * Text Domain:     on-screen-keyboard 
 * License: GNU General Public License, version 3
 * License URI:     http://www.gnu.org/licenses/gpl-3.0.txt
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// Require the main plugin class.
require_once plugin_dir_path( __FILE__ ) . 'class-on-screen-keyboard-plugin.php';

// Register hooks that are fired when the plugin is activated and deactivated, respectively.
register_activation_hook( __FILE__, array( 'OnScreenKeyboard', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'OnScreenKeyboard', 'deactivate' ) );

// Get plugin instance.
OnScreenKeyboard::instance();
  
?>
