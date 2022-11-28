<?php
/**
 * OnScreenKeyboard admin class
 *
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'OnScreenKeyboardAdmin', false ) ) {

  include( dirname( __FILE__ ) . '/library/apf/admin-page-framework.php' );
  
  /**
   * Admin OnScreenKeyboard class
   *
   * @since       1.3
   * IMPORTANT: Ask the generator for unique name OnScreenKeyboardAdminPageFramework i.e. do not just use AdminPageFramework
   */
  class OnScreenKeyboardAdmin extends OnScreenKeyboardAdminPageFramework {
    
    public function setUp() {
       
      // Create the root menu - specifies to which parent menu to add.
      //        $this->setRootMenuPage( 'Settings' ); // it could also go under Settings
      $this->setRootMenuPage( 'OnScreen Keyboard' );  
 
      // Add the sub menus and the pages.
      $this->addSubMenuItems(
			     array(
				   'title'     => 'Settings',  // page and menu title
				   'page_slug' => 'onscreenkeyboard_settings_page'     // page slug
				   )
			     );
 
    }

    /**
     * One of the pre-defined methods which is triggered when the registered page loads.
     * 
     * Here we add form fields.
     * @callback        action      load_{page slug}
     */
    public function load_onscreenkeyboard_settings_page( $oAdminPage ) {

      $this->addSettingFields(
			      array(
				    'field_id'      => 'version',
				    'title'         => __( 'Variants', 'on-screen-keyboard' ),
				    'type'          => 'radio',
				    'label'         => array(
							     'old' => __( 'Old version (deprecated).', 'on-screen-keyboard' ).' It implements [oskb] shortcode.',
							     'new' => __( 'New version.', 'on-screen-keyboard' ).' It implements [oskb] shortcode and optional facilities below.',
							     ),
				    'default'       => 'new',
				    'after_label'   => '<br />',
				    /* 'attributes'    => array( */
				    /* 			     'old' => array( */
				    /* 					  'disabled' => 'disabled', */
				    /* 					  ), */
				    /* 			     ), */
				    )
			      );

      $is_old = OnScreenKeyboardAdminPageFramework::getOption( 'OnScreenKeyboardOptions', 'version', 'old' ) == 'old';
      
      $this->addSettingFields(
			      array(
				    'field_id'      => 'PasswordProtectedPosts',
				    'title'         => __( 'PP posts', 'on-screen-keyboard' ),
				    'type'          => 'radio',
				    'label'         => array ('pp_disabled' => __( 'Disabled onscreen keyboard for password protected posts and pages', 'on-screen-keyboard' ),
							      'pp_enabled_standard' => __( 'Onscreen keyboard for password protected posts and pages. Standard keyboard can still be used.', 'on-screen-keyboard' ),
							      'pp_enabled_numeric' => __( 'Onscreen numerical keyboard for password protected posts and pages where password is numerical i.e. a pincode. 12 digit password is submitted automatically.  (Passwords longer than 12 characters, of which the first 12 characters are numbers, are therefore impossible.) Standard keyboard can still be used. General passwords can still be used by using standard keyboard.', 'on-screen-keyboard' ), ),
				    'default'   => 'pp_disabled',
				    'attributes'    => array(
							     'pp_disabled' => ( $is_old ?  array('disabled' => 'disabled',) : array() ),
							     'pp_enabled_standard' => ( $is_old ?  array('disabled' => 'disabled',) : array() ),
							     'pp_enabled_numeric' => ( $is_old ?  array('disabled' => 'disabled',) : array() )),));
      
  $this->addSettingFields(
            array( // Submit button
                'field_id'      => 'submit_button',
                'type'          => 'submit',
            )
        );
        
    }
    /**
     * Notice that the name of the method is 'do_' + the page slug.
     */    
    public function do_onscreenkeyboard_settings_page() {   

      // return;
        echo '<h3>Show all the options as an array</h3>';
        echo $this->oDebug->get( OnScreenKeyboardAdminPageFramework::getOption( 'OnScreenKeyboardOptions' ) );
 
    }
  }
  new OnScreenKeyboardAdmin("OnScreenKeyboardOptions");
 }

