<?php
/**
 * OnScreenKeyboard main class
 *
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'OnScreenKeyboard', false ) ) {

	/**
	 * Main OnScreenKeyboard class
	 *
	 * @since       1.3
	 */
	class OnScreenKeyboard {

		/**
		 * Option array.
		 *
		 * @access      protected
		 * @var         array $options Array of config options
		 * @since       1.3
		 */
	        public $options = array();

		/**
		 * Use this value as the text domain when translating strings from this plugin. It should match
		 * the Text Domain field set in the plugin header, as well as the directory name of the plugin.
		 * Additionally, text domains should only contain letters, number and hypens, not underscores
		 * or spaces.
		 *
		 * @access      protected
		 * @var         string $plugin_slug The unique ID (slug) of this plugin
		 * @since       1.3
		 */
		protected $plugin_slug = 'on-screen-keyboard';

		/**
		 * Set on network activate.
		 *
		 * @access      protected
		 * @var         string $plugin_network_activated Check for plugin network activation
		 * @since       1.3
		 */
		protected $plugin_network_activated = null;

		/**
		 * Class instance.
		 *
		 * @access      private
		 * @var         \OnScreenKeyboard $instance The one true OnScreenKeyboard 
		 * @since       1.3
		 */
		private static $instance;

		/**
		 * Get active instance
		 *
		 * @access      public
		 * @since       1.3
		 * @return      self::$instance The one true OnScreenKeyboard		 */
		public static function instance() {
			$path = WP_PLUGIN_DIR . 'on-screen-keyboard/on-screen-keyboard.php';

			if ( function_exists( 'get_plugin_data' ) && file_exists( $path ) ) {
				$data = get_plugin_data( $path );

				if ( isset( $data ) && isset( $data['Version'] ) && '' !== $data['Version'] ) {
					//$res = version_compare( $data['Version'], '4', '<' );
				        // need to do something with version?
				}

			}

			if ( ! self::$instance ) {
				self::$instance = new self();

				self::$instance->get_plugin_options();
				self::$instance->includes();
				self::$instance->hooks();
			}

			return self::$instance;
		}

		/**
		 * Get plugin options
		 *
		 * @access      public
		 * @since       1.3
		 * @return      void
		 */
		public function get_plugin_options() {

		  $this->options = array();
		  $this->options['version'] = get_option('on_screen_keyboard_version_field', 'new');
		  if ($this->options['version'] == "") $this->options['version'] = 'new';
		  $this->options['PasswordProtectedPosts'] = get_option('on_screen_keyboard_ppposts_field', 'pp_disabled');
		  if ($this->options['PasswordProtectedPosts'] == "") $this->options['PasswordProtectedPosts'] = 'pp_disabled';
		  
		}

		/**
		 * Include necessary files
		 *
		 * @access      public
		 * @since       1.3
		 * @return      void
		 */
		public function includes() {

		  if (is_admin()) $this->include_admin();

		}

		/**
		 * Run action and filter hooks
		 *
		 * @access      private
		 * @since       1.3
		 * @return      void
		 */
		private function hooks() {
			// Activate plugin when new blog is added.
			add_action( 'wpmu_new_blog', array( $this, 'activate_new_site' ) );

			// Display admin notices.
			add_action( 'admin_notices', array( $this, 'admin_notices' ) );

			// Edit plugin metalinks.
			add_filter( 'plugin_row_meta', array( $this, 'plugin_metalinks' ), null, 2 );

			if ($this->is_old_version()){
			  add_action( 'wp_enqueue_scripts', array( $this, 'onscreenkeyboard_css' ) );
			} else {
			  add_action( 'wp_enqueue_scripts', array( $this, 'new_onscreenkeyboard_css' ) );
			  if ( $this->is_numeric_password_protected_posts() ){
			    add_filter('the_password_form', array( $this, 'my_custom_password_form_with_numeric_keyboard'), 99);
			  }
			  if ( $this->is_standard_password_protected_posts() ){
			    add_filter('the_password_form', array( $this, 'my_custom_password_form_with_standard_keyboard'), 99);
			  }

			}
			add_shortcode( 'oskb', array( $this, 'oskb_func' ) );

			// phpcs:ignore WordPress.NamingConventions.ValidHookName
			do_action( 'onscreenkeyboard/plugin/hooks', $this );
		}

		/**
		 *
		 * @access      private
		 * @since       1.3
		 * @return      bool
		 */

		private function is_old_version(){
		  $adminOptions = $this->options;
		  $version = isset($adminOptions['version']) ? $adminOptions['version'] : 'old';
		  return $version == 'old';
		}

		/**
		 *
		 * @access      private
		 * @since       1.3
		 * @return      bool
		 */

		private function is_numeric_password_protected_posts(){
		  $adminOptions = $this->options;
		  $enabled = isset($adminOptions['PasswordProtectedPosts']) ? $adminOptions['PasswordProtectedPosts']=='pp_enabled_numeric' : false;
		  return $enabled;
		}

		/**
		 *
		 * @access      private
		 * @since       1.3
		 * @return      bool
		 */

		private function is_standard_password_protected_posts(){
		  $adminOptions = $this->options;
		  $enabled = isset($adminOptions['PasswordProtectedPosts']) ? $adminOptions['PasswordProtectedPosts']=='pp_enabled_standard' : false;
		  return $enabled;
		}
		
		/**
		 * Fired on plugin activation
		 *
		 * @access      public
		 * @since       3.0.0
		 *
		 * @param       boolean $network_wide True if plugin is network activated, false otherwise.
		 *
		 * @return      void
		 */
		public static function activate( $network_wide ) {
			if ( function_exists( 'is_multisite' ) && is_multisite() ) {
				if ( $network_wide ) {
					// Get all blog IDs.
					$blog_ids = self::get_blog_ids();

					foreach ( $blog_ids as $blog_id ) {
						switch_to_blog( $blog_id );
						self::single_activate();
						// https://developer.wordpress.org/reference/functions/restore_current_blog/
						restore_current_blog();
					}
				} else {
					self::single_activate();
				}
			} else {
				self::single_activate();
			}

			//delete_site_transient( 'update_plugins' );
		}

		/**
		 * Fired when plugin is deactivated
		 *
		 * @access      public
		 * @since       1.3
		 *
		 * @param       boolean $network_wide True if plugin is network activated, false otherwise.
		 *
		 * @return      void
		 */
		public static function deactivate( $network_wide ) {
			if ( function_exists( 'is_multisite' ) && is_multisite() ) {
				if ( $network_wide ) {
					// Get all blog IDs.
					$blog_ids = self::get_blog_ids();

					foreach ( $blog_ids as $blog_id ) {
						switch_to_blog( $blog_id );
						self::single_deactivate();
						// https://developer.wordpress.org/reference/functions/restore_current_blog/
						restore_current_blog();
					}
				} else {
					self::single_deactivate();
				}
			} else {
				self::single_deactivate();
			}

			// rather on ununstall
			//delete_option( 'OnScreenKeyboardOptions' );
		}

		/**
		 * Fired when a new WPMU site is activated
		 *
		 * @access      public
		 * @since       1.3
		 *
		 * @param       int $blog_id The ID of the new blog.
		 *
		 * @return      void
		 */
		public function activate_new_site( $blog_id ) {
			if ( 1 !== did_action( 'wpmu_new_blog' ) ) {
				return;
			}

			switch_to_blog( $blog_id );
			self::single_activate();
			restore_current_blog();
		}

		/**
		 * Get all IDs of blogs that are not activated, not spam, and not deleted
		 *
		 * @access      private
		 * @since       1.3
		 * @global      object $wpdb
		 * @return      array|false Array of IDs or false if none are found
		 */
		private static function get_blog_ids() {
			global $wpdb;

			$var = '0';

			// Get an array of IDs (We have to do it this way because WordPress ays so, however reduntant.
			$result = wp_cache_get( 'on-screen-keyboard-blog-ids' );
			if ( false === $result ) {

				// WordPress asys get_col is discouraged?  I found no alternative.  So...ignore! - kp.
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery
				$result = $wpdb->get_col( $wpdb->prepare( "SELECT blog_id FROM $wpdb->blogs WHERE archived = %s AND spam = %s AND deleted = %s", $var, $var, $var ) );

				wp_cache_set( 'on-screen-keyboard-blog-ids', $result );
			}

			return $result;
		}

		/**
		 * Fired for each WPMS blog on plugin activation
		 *
		 * @access      private
		 * @since       1.3
		 * @return      void
		 */
		private static function single_activate() {
		}

		/**
		 * Display admin notices
		 *
		 * @access      public
		 * @since       1.3
		 * @return      void
		 */
		public function admin_notices() {
		}

		/**
		 * Fired for each blog when the plugin is deactivated
		 *
		 * @access      private
		 * @since       1.3
		 * @return      void
		 */
		private static function single_deactivate() {
		}

		/**
		 * Edit plugin metalinks
		 *
		 * @access      public
		 * @since       1.3
		 *
		 * @param       array  $links The current array of links.
		 * @param       string $file  A specific plugin row.
		 *
		 * @return      array The modified array of links
		 */
		public function plugin_metalinks( $links, $file ) {

			return $links;
		}

		/**
		 * JS and CSS of the old version (deprecated)
		 * // We need some CSS to position the paragraph
		 * @access      public
		 * @since       1.3
		 * @return      void
		 */

		 public function onscreenkeyboard_css() {
		   // This makes sure that the positioning is also good for right-to-left languages
		   wp_register_style('onscreenkeyboardcss', plugins_url( 'css/jskeyboard.css', __FILE__),array(),'1.1','screen');
		   wp_enqueue_style( 'onscreenkeyboardcss' );	
		   wp_register_script('onscreenkeyboard', plugins_url( 'js/jskeyboard.js', __FILE__),array('jquery'),'1.1', true);
		   wp_register_script('onscreenkeyboard.main', plugins_url( 'js/jskeyboard.main.js', __FILE__),array('jquery'),'1.1', true);
		   wp_enqueue_script('onscreenkeyboard');
		   wp_enqueue_script('onscreenkeyboard.main');
		 }

		/**
		 * JS and CSS of the NEW version
		 * 
		 * @access      public
		 * @since       1.3
		 * @return      void
		 */

		 public function new_onscreenkeyboard_css() {

		   wp_register_style('newonscreenkeyboardcss', plugins_url( 'css/newjskeyboard.css', __FILE__),array(),'1.1','screen');
		   wp_enqueue_style( 'newonscreenkeyboardcss' );	
		   wp_register_script('newonscreenkeyboard', plugins_url( 'js/newjskeyboard.js', __FILE__),array('jquery'),'1.1', true);
		   wp_register_script('newonscreenkeyboard.main', plugins_url( 'js/newjskeyboard.main.js', __FILE__),array('jquery'),'1.1', true);
		   wp_enqueue_script('newonscreenkeyboard');
		   wp_enqueue_script('newonscreenkeyboard.main');
		 }
		 
		/**
		 * Shortcode
		 * //[oskb]
		 * @access      public
		 * @since       1.3
		 * @return      html element
		 */
		 
		 public function oskb_func( $atts ){
		   return '<div id="onScreenKeyboardElmId"></div>';
		 }
		 
		 /**
		 * admin pages
		 * 
		 * @access      public
		 * @since       1.3
		 * @return      void
		 */

		 public function include_admin(){

		   include( dirname( __FILE__ ) . '/admin/admin.php' );

		 }

		 /**
		 * replace password protected post form: add numeric on screen keyboard
		 * 
		 * @access      public
		 * @since       1.3
		 * @return      html form
		 */

		 public function my_custom_password_form_with_numeric_keyboard() {
		   global $post;
		   $label = 'oskb_pwbox-' . ( empty( $post->ID ) ? rand() : $post->ID );
    $output = '
    <div class="boldgrid-section">
        <div class="container">
            <form action="' . esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) ) . '" class="form-inline post-password-form" method="post">
                <p>' . __( 'This content is password protected. To view it please enter your password below:' ) . '</p>
                <label for="' . $label . '">' . __( 'Password:' ) . ' <input name="post_password" id="' . $label . '" type="password" size="20" class="form-control" /></label>
				<div id="onScreenKeyboardElmId" data-id="' . $label . '" data-keyboard="numeric"></div>
				<button type="submit" name="Submit" class="button-primary" id="' . $label . "OskbPasswordProtectedPageButton".'">' . esc_attr_x( 'Enter', 'post password form' ) . '</button>
            </form>
        </div>

    </div>';
    return $output;
		 }

		 /**
		 * replace password protected post form: add standard on screen keyboard
		 * 
		 * @access      public
		 * @since       1.3
		 * @return      html form
		 */

		 public function my_custom_password_form_with_standard_keyboard() {
		   global $post;
		   $label = 'oskb_pwbox-' . ( empty( $post->ID ) ? rand() : $post->ID );
    $output = '
    <div class="boldgrid-section">
        <div class="container">
            <form action="' . esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) ) . '" class="form-inline post-password-form" method="post">
                <p>' . __( 'This content is password protected. To view it please enter your password below:' ) . '</p>
                <label for="' . $label . '">' . __( 'Password:' ) . ' <input name="post_password" id="' . $label . '" type="password" size="20" class="form-control" /></label>
				<div id="onScreenKeyboardElmId" data-id="' . $label . '" data-keyboard="standard"></div>
				<button type="submit" name="Submit" class="button-primary">' . esc_attr_x( 'Enter', 'post password form' ) . '</button>

            </form>
        </div>

    </div>';
    return $output;
		 }
		 
	}
		 
}
