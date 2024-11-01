<?php
/**
 * Fields WCFP
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WCFP_Fields' ) ) {

	/**
	 *
	 * Class for add fields wcfp
	 */
	class WCFP_Fields {
		/**
		 * Constructor.
		 *
		 * @version 1.0.0
		 */
		public function __construct() {
			add_action( 'woocommerce_product_options_general_product_data', array( $this, 'woo_add_custom_general_fields' ) );
			add_action( 'woocommerce_process_product_meta', array( $this, 'woo_add_custom_general_fields_save' ) );
		}

		public function woo_add_custom_general_fields() {
			global $woocommerce, $post;
  
		  	echo '<div class="options_group">';
		  
		  	woocommerce_wp_checkbox( 
				array( 
					'id'            => '_wcfp_enabled', 
					'label'         => __('Call for Price', 'wcfp' ), 
					'desc_tip'      => 'true',
					'description'   => __( 'Select if this product display Call for Price label', 'wcfp' ), 
				)
			);
		  
		  	echo '</div>';
		}

		public function woo_add_custom_general_fields_save( $post_id ) {
			$woocommerce_checkbox = isset( $_POST['_wcfp_enabled'] ) ? 'yes' : 'no';
			update_post_meta( $post_id, '_wcfp_enabled', $woocommerce_checkbox );
		}
	}

}
