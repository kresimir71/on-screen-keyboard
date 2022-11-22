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
		protected $options = array();

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

			// Setup defaults.
			$defaults = array(
				'demo' => false,
			);

			// If multisite is enabled.
			if ( is_multisite() ) {

				// Get network activated plugins.
				$plugins = get_site_option( 'active_sitewide_plugins' );

				foreach ( $plugins as $file => $plugin ) {
					if ( strpos( $file, 'on-screen-keyboard.php' ) !== false ) {
						$this->plugin_network_activated = true;
						$this->options                  = get_site_option( 'OnScreenKeyboard', $defaults );
					}
				}
			}

			// If options aren't set, grab them now!
			if ( empty( $this->options ) ) {
				$this->options = get_option( 'OnScreenKeyboard', $defaults );
			}
		}

		/**
		 * Include necessary files
		 *
		 * @access      public
		 * @since       1.3
		 * @return      void
		 */
		public function includes() {

			// Include Predux_Core.
			// if ( file_exists( dirname( __FILE__ ) . '/PreduxCore/framework.php' ) ) {
			// 	require_once dirname( __FILE__ ) . '/PreduxCore/framework.php';
			// }

			// if ( isset( Predux_Core::$as_plugin ) ) {
			// 	Predux_Core::$as_plugin = true;
			// }

			// // Include demo config, if demo mode is active.
			// if ( $this->options['demo'] && file_exists( dirname( __FILE__ ) . '/sample/sample-config.php' ) ) {
			// 	require_once dirname( __FILE__ ) . '/sample/sample-config.php';
			// }
		}

		/**
		 * Run action and filter hooks
		 *
		 * @access      private
		 * @since       1.3
		 * @return      void
		 */
		private function hooks() {
			//add_action( 'wp_loaded', array( $this, 'options_toggle_check' ) );

			// Activate plugin when new blog is added.
			add_action( 'wpmu_new_blog', array( $this, 'activate_new_site' ) );

			// Display admin notices.
			add_action( 'admin_notices', array( $this, 'admin_notices' ) );

			// Edit plugin metalinks.
			add_filter( 'plugin_row_meta', array( $this, 'plugin_metalinks' ), null, 2 );

			add_action( 'wp_enqueue_scripts', array( $this, 'onscreenkeyboard_css' ) );
			add_shortcode( 'oskb', array( $this, 'oskb_func' ) );

			// phpcs:ignore WordPress.NamingConventions.ValidHookName
			do_action( 'onscreenkeyboard/plugin/hooks', $this );
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
					}
					restore_current_blog();
				} else {
					self::single_activate();
				}
			} else {
				self::single_activate();
			}

			delete_site_transient( 'update_plugins' );
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
					}
					restore_current_blog();
				} else {
					self::single_deactivate();
				}
			} else {
				self::single_deactivate();
			}

			delete_option( 'OnScreenKeyboard' );
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
		 * Turn on or off
		 *
		 * @access      public
		 * @since       1.3
		 * @global      string $pagenow The current page being displayed
		 * @return      void
		 */
		public function options_toggle_check() {
			global $pagenow;
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
	}

		/**
		 * JS and CSS of the old version
		 * // We need some CSS to position the paragraph
		 * @access      public
		 * @since       1.3
		 * @return      void
		 */

		 public function onscreenkeyboard_css() {
		   // This makes sure that the positioning is also good for right-to-left languages
		   wp_register_style('onscreenkeyboardcss', plugins_url( 'on-screen-keyboard/css/jskeyboard.css'),array(),'1.1','screen');
		   wp_enqueue_style( 'onscreenkeyboardcss' );	
		   wp_register_script('onscreenkeyboard', plugins_url( 'on-screen-keyboard/js/jskeyboard.js'), array('jquery'),'1.1', true);
		   wp_register_script('onscreenkeyboard.main', plugins_url( 'on-screen-keyboard/js/jskeyboard.main.js'), array('jquery'),'1.1', true);
		   wp_enqueue_script('onscreenkeyboard');
		   wp_enqueue_script('onscreenkeyboard.main');
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

}
