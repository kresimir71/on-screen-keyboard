<?php
/*
 * Plugin Name: Onscreen Keyboard
 * Plugin URI: http://onscreenkeyboard.co.nf/
 * Description: This plugin adds an onscreen keyboard powered by Javascript/JQuery, ported from github by Iain Redmill, now working within WordPress, allowing mouse based input or input when working from a touch screen device.  Keyboard settings etc can be modified by editing the file.  To add a keyboard, enter <b>[oskb]</b> within your page where you wish it to appear.
 * Version: 1.2
 * Author: Ported by Iain Redmill
 * Author URI: http://onscreenkeyboard.co.nf/
 * Network: true
 * License: GPL2
 */


function onscreenkeyboard_the_content( $content ) {
   return str_replace("[oskb]",'<div id="virtualKeyboard"></div>',$content);
}

add_filter( 'the_content', 'onscreenkeyboard_the_content' );
/*  
// This just echoes the chosen line, we'll position it later
function onscreenkeyboard() {
	echo "testing 123 ";
	
	
// Now we set that function up to execute when the admin_notices action is called
add_action( 'admin_notices', 'onscreenkeyboard' );
*/

// We need some CSS to position the paragraph
function onscreenkeyboard_css() {
	// This makes sure that the positioning is also good for right-to-left languages
	wp_register_style('onscreenkeyboardcss', plugins_url( 'on-screen-keyboard/css/jskeyboard.css'),array(),'1.1','screen');
	wp_enqueue_style( 'onscreenkeyboardcss' );	
	wp_register_script('onscreenkeyboard', plugins_url( 'on-screen-keyboard/js/jskeyboard.js'), array('jquery'),'1.1', true);
	wp_register_script('onscreenkeyboard.main', plugins_url( 'on-screen-keyboard/js/jskeyboard.main.js'), array('jquery'),'1.1', true);
	wp_enqueue_script('onscreenkeyboard');
	wp_enqueue_script('onscreenkeyboard.main');
}

add_action( 'wp_enqueue_scripts', 'onscreenkeyboard_css' );
  
?>