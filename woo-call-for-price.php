<?php
/**
 * Plugin Name: Woo Call for Price
 * Description: Display Call for Price into woocommerce product
 * Version: 1.0.1
 * Author: Arif Rohman Hakim
 * Author URI: http://www.arifrohmanhakim.com/
 * Plugin URI: http://www.arifrohmanhakim.com/plugin/woo-call-for-price
 * License: MIT License
 * License URI: http://opensource.org/licenses/MIT
 * Text Domain: wcfp
 * Domain Path: /languages
 **/

// exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Call_For_Price' ) ) {

	/**
	 * Main Class Call For Price
	 */
	final class Call_For_Price {

		private static $instance;

		/**
		 * Main plugin instance,
		 * Insures that only one instance of the plugin exists in memory at one time.
		 *
		 * @return object
		 */
		public static function instance() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Call_For_Price ) ) {

				self::$instance = new Call_For_Price();
				self::$instance->define_constants();

				add_action( 'plugins_loaded', array( self::$instance, 'load_textdomain' ) );

				self::$instance->includes();

				self::$instance->settings = new WCFP_Settings();
				self::$instance->fields = new WCFP_Fields();
				self::$instance->display = new WCFP_Display();

			}
			return self::$instance;
		}

		/**
		 * Setup plugin constants.
		 *
		 * @return void
		 */
		private function define_constants() {
			define( 'WCFP_VERSION', '1.0.1' );
			define( 'WCFP_URL', plugins_url( '', __FILE__ ) );
			define( 'WCFP_PATH', plugin_dir_path( __FILE__ ) );
			define( 'WCFP_REL_PATH', dirname( plugin_basename( __FILE__ ) ) . '/' );
		}

		/**
		 * Include required files.
		 *
		 * @return void
		 */
		private function includes() {
			include_once WCFP_PATH . 'includes/setting.php';
			include_once WCFP_PATH . 'includes/fields.php';
			include_once WCFP_PATH . 'includes/display.php';
		}

		/**
		 * Class constructor.
		 *
		 * @return void
		 */
		public function __construct() {
			// Check if WooCommerce is active.
			$plugin = 'woocommerce/woocommerce.php';
			if ( ! in_array( $plugin, apply_filters( 'active_plugins', get_option( 'active_plugins', array() ) ), true ) && ! ( is_multisite() && array_key_exists( $plugin, get_site_option( 'active_sitewide_plugins', array() ) ) ) ) {
				$this->add_notice( __( 'Woo Call for Price need woocommerce plugin to be actived.', 'wcfp' ), 'error', true );
				return;
			}

			add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );
			add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ) , array( $this, 'plugin_add_settings_link' ) );
		}

		/**
		 * add setting plugin links
		 * @param  [type] $links
		 */
		public function plugin_add_settings_link( $links ) {
		    $settings_link = '<a href="admin.php?page=wc-settings&tab=wcfp_setting">' . __( 'Settings' ) . '</a>';
		    array_push( $links, $settings_link );
		  	return $links;
		}

		/**
		 * Load text domain.
		 */
		public function load_textdomain() {
			load_plugin_textdomain( 'wcfp', false, WCFP_REL_PATH . 'languages/' );
		}

		/**
		 * Enqueue admin scripts and styles.
		 *
		 * @global string $post_type
		 * @param string $page
		 */
		public function wp_enqueue_scripts( $page ) {
			wp_enqueue_style(
				'wcfp-main-style', WCFP_URL . '/assets/css/style.css'
			);
		}

		/**
		 * Add admin notices.
		 */
		public function add_notice( $html = '', $status = '', $paragraph = false ) {
			$this->notices[] = array(
				'html'       => $html,
				'status'     => $status,
				'paragraph'  => $paragraph,
			);

			add_action( 'admin_notices', array( $this, 'display_notice' ) );
		}

		/**
		 * Print admin notices.
		 */
		public function display_notice() {
			foreach ( $this->notices as $notice ) {
				echo '
				<div class="wcfp ' . esc_attr( $notice['status'] ) . '">
					' . ( $notice['paragraph'] ? '<p>' : '' ) . '
					' . $notice['html'] . '
					' . ( $notice['paragraph'] ? '</p>' : '' ) . '
				</div>';
			}
		}

	}
}

/**
 * Initialise Call For Price.
 *
 * @return object
 */
function call_for_price() {
	static $instance;

	// first call to instance() initializes the plugin
	if ( null === $instance || ! ( $instance instanceof Call_For_Price ) ) {
		$instance = Call_For_Price::instance();
	}

	return $instance;
}

call_for_price();
