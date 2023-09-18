<?php

function on_screen_keyboard_admin_menu() {
    add_menu_page(
        __( 'On Screen Keyboard', 'on-screen-keyboard' ),
        __( 'Oskb', 'on-screen-keyboard' ),
        'manage_options',
        'on-screen-keyboard-admin-page',
        'on_screen_keyboard_admin_page_contents',
        'dashicons-universal-access'
    );
}
add_action( 'admin_menu', 'on_screen_keyboard_admin_menu' );

function on_screen_keyboard_admin_page_contents() {
    ?>
    <h1> <?php esc_html_e( 'Welcome to On Screen Keyboard admin page.', 'my-plugin-textdomain' ); ?> </h1>
    <form method="POST" action="options.php">
    <?php
    settings_fields( 'on-screen-keyboard-admin-page' );
    do_settings_sections( 'on-screen-keyboard-admin-page' );
    submit_button();
    ?>
    </form>
    <?php
}


add_action( 'admin_init', 'on_screen_keyboard_settings_init' );

function on_screen_keyboard_settings_init() {

  add_settings_section(
		       'on_screen_keyboard_variants_section',
		       __( 'Variants', 'on-screen-keyboard' ),
		       'on_screen_keyboard_variants_section_callback_function',
		       'on-screen-keyboard-admin-page'
		       );

  add_settings_field(
		     'on_screen_keyboard_version_field',
		     __( 'Which version will be used:', 'on-screen-keyboard' ),
		     'on_screen_keyboard_variants_markup',
		     'on-screen-keyboard-admin-page',
		     'on_screen_keyboard_variants_section'
		     );

  register_setting( 'on-screen-keyboard-admin-page', 'on_screen_keyboard_version_field' );
  
  add_settings_field(
		     'on_screen_keyboard_ppposts_field',
		     __( 'On pasword protected posts and pages:', 'on-screen-keyboard' ),
		     'on_screen_keyboard_ppposts_markup',
		     'on-screen-keyboard-admin-page',
		     'on_screen_keyboard_variants_section'
		     );

  register_setting( 'on-screen-keyboard-admin-page', 'on_screen_keyboard_ppposts_field' );
}


function on_screen_keyboard_variants_section_callback_function() {
    echo '<p>Here you can choose between the old and the new version.</p>';
}


function on_screen_keyboard_variants_markup() {
    $adminoptions = OnScreenKeyboard::instance()->options;
    $toCheck=array( "old", "new");
    $checked = array_map(function($val) use ($adminoptions) { return ( $val == $adminoptions['version'] ? "checked" : "" ); }, $toCheck);

    ?>
  <!--
    <label for="my-input"><?php _e( 'My Input' ); ?></label>
    <input type="text" id="on_screen_keyboard_version_field" name="on_screen_keyboard_version_field" value="<?php echo get_option( 'on_screen_keyboard_version_field' ); ?>">
-->
							
  <input type="radio" id="on_screen_keyboard_version_field1" name="on_screen_keyboard_version_field" value="old" <?php echo $checked[0]; ?> >
  <label for="on_screen_keyboard_version_field1">Old version (deprecated). It implements [oskb] shortcode.</label><br>
  <input type="radio" id="on_screen_keyboard_version_field2" name="on_screen_keyboard_version_field" value="new" <?php echo $checked[1]; ?> >
  <label for="on_screen_keyboard_version_field2">New version. It implements [oskb] shortcode and optional facilities below.</label><br>
    <?php
}

function on_screen_keyboard_ppposts_markup() {
  $adminoptions = OnScreenKeyboard::instance()->options;
  $toCheck=array( "pp_disabled", "pp_enabled_standard", "pp_enabled_numeric");
  $checked = array_map(function($val) use ($adminoptions) { return ( $val == $adminoptions['PasswordProtectedPosts'] ? "checked" : "" ); }, $toCheck);
  $disabled = $adminoptions['version'] == 'old' ? 'disabled' : '' ;
    ?>
    <input type="radio" id="on_screen_keyboard_ppposts_field1" name="on_screen_keyboard_ppposts_field" value="pp_disabled" <?php echo $checked[0]; ?> <?php echo $disabled; ?> >
  <label for="on_screen_keyboard_ppposts_field1">Disabled onscreen keyboard for password protected posts and pages.</label><br>
  <input type="radio" id="on_screen_keyboard_ppposts_field2" name="on_screen_keyboard_ppposts_field" value="pp_enabled_standard" <?php echo $checked[1]; ?> <?php echo $disabled; ?> >
  <label for="on_screen_keyboard_ppposts_field2">Enable onscreen keyboard for password protected posts and pages. Standard keyboard can still be used.</label><br>
  <input type="radio" id="on_screen_keyboard_ppposts_field3" name="on_screen_keyboard_ppposts_field" value="pp_enabled_numeric" <?php echo $checked[2]; ?> <?php echo $disabled; ?> >
  <label for="on_screen_keyboard_ppposts_field3">Enable onscreen numerical keyboard for password protected posts and pages where password is numerical i.e. a pincode. Furthermore: 12 digit password is submitted automatically. (Passwords longer than 12 characters, of which the first 12 characters are numbers, are therefore impossible.) Standard keyboard can still be used (but in that case the auto-submit won't work properly - you have to hit the submit button). General passwords can still be used by using standard keyboard.</label><br>
    <?php
}

function register_on_screen_keyboard_admin_page_scripts() {

  wp_register_style( 'on-screen-keyboard-admin-css', plugins_url( 'admin/admin.css' ) );

  wp_register_script( 'on-screen-keyboard-admin-js', plugins_url( 'admin/admin.js' ) );

}

add_action( 'admin_enqueue_scripts', 'register_on_screen_keyboard_admin_page_scripts' );

function load_on_screen_keyboard_admin_page_scripts( $hook ) {

  // Load only on ?page=on-screen-keyboard-admin-page

  if( $hook != 'on-screen-keyboard-admin-page' ) {

    return;

  }

  // Load style & scripts.

  wp_enqueue_style( 'on-screen-keyboard-admin-css' );

  wp_enqueue_script( 'on-screen-keyboard-admin-js' );

}

add_action( 'admin_enqueue_scripts', 'load_on_screen_keyboard_admin_page_scripts' );
