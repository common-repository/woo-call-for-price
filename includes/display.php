<?php
/**
 * Display WCFP
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WCFP_Display' ) ) {

	/**
	 *
	 * Class for display wcfp
	 */
	class WCFP_Display {
		/**
		 * Constructor.
		 *
		 * @version 1.0.0
		 */
		public function __construct() {
			add_filter( 'woocommerce_get_price_html', array( $this, 'custom_price_message' ) );
			// Simple, grouped and external products
			add_filter('woocommerce_product_get_price', array( $this, 'custom_price'), 99);
			add_filter('woocommerce_product_get_regular_price', array( $this, 'custom_price'), 99);

			// Variable products (min-max)
			add_filter('woocommerce_variation_prices_price', array( $this, 'custom_price'), 99);
			add_filter('woocommerce_variation_prices_regular_price', array( $this, 'custom_price'), 99);

			// Products variations
			add_filter('woocommerce_product_variation_get_regular_price', array( $this, 'custom_price'), 99);
			add_filter('woocommerce_product_variation_get_price', array( $this, 'custom_price'), 99);

		}

		public function custom_price_message( $price ) {
			global $post;
			$wcfp_product_enabled = get_post_meta( $post->ID, '_wcfp_enabled' );
			$wcfp_enabled = get_option( 'wcfp_setting_enabled' );
			$wcfp_text = get_option( 'wcfp_setting_text_output' );
			if ( ( !empty( $wcfp_enabled ) && $wcfp_enabled == 'yes' ) && ( count( $wcfp_product_enabled ) > 0 && $wcfp_product_enabled[0] == 'yes' ) ) {
				
				$price = '<span class="wcfp_price">' . $wcfp_text . '</span>';
			}

			return $price;
		}

		public function custom_price($price) {
			global $post;

			$wcfp_product_enabled = get_post_meta( $post->ID, '_wcfp_enabled' );
			$wcfp_enabled = get_option( 'wcfp_setting_enabled' );
			$wcfp_text = get_option( 'wcfp_setting_text_output' );
			if ( ( !empty( $wcfp_enabled ) && $wcfp_enabled == 'yes' ) && ( count( $wcfp_product_enabled ) > 0 && $wcfp_product_enabled[0] == 'yes' ) ) {
			    wc_delete_product_transients($post->ID);
			    return '';
			} else {
				return $price;
			}
		}

	}

}
