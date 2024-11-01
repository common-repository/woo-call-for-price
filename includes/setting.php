<?php
/**
 * Setting WCFP
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WCFP_Settings' ) ) :

	/**
	 *
	 * Class for setting wcfp
	 */
	class WCFP_Settings {

		/**
		 * Constructor.
		 *
		 * @version 1.0.0
		 */
		public function __construct() {
			$this->id = 'wcfp_setting';
			$this->label = __( 'Call for Price', 'wcfp' );

			add_filter( 'woocommerce_settings_tabs_array', array( $this, 'add_settings_tab' ), 50 );
			add_action( 'woocommerce_settings_tabs_wcfp_setting', array( $this, 'settings_tab' ) );
			add_action( 'woocommerce_update_options_wcfp_setting', array( $this, 'update_settings' ) );
		}

		/**
		 * Set the woocommerce tab setting
		 */
	    public function add_settings_tab( $settings_tabs ) {
	        $settings_tabs[$this->id] = $this->label;
	        return $settings_tabs;
	    }

	    /**
	     * Display Form
	     */
	    public function settings_tab() {
	    	woocommerce_admin_fields( $this->get_settings() );
	    }

		/**
		 * Save settings.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		public function update_settings() {
	        woocommerce_update_options( $this->get_settings() );
	    }

		/**
		 * Get_settings.
		 *
		 * @version 3.1.0
		 */
		public function get_settings() {
			global $current_section;
			$settings = apply_filters( 'woocommerce_get_settings_' . $this->id . '_' . $current_section, array() );

			return array_merge( $settings, array(
				array(
					'title'     => __( 'Call for Price Options', 'wcfp' ),
					'type'      => 'title',
					'id'        => $this->id . '_reset_options',
				),
				array(
					'title'     => __( 'Enabled', 'wcfp' ),
					'desc'      => '',
					'id'        => $this->id . '_enabled',
					'default'   => 'yes',
					'type'      => 'checkbox',
				),
				array(
					'title'     => __( 'Text', 'wcfp' ),
					'desc'      => '',
					'default'	=> 'Call for Price',
					'id'        => $this->id . '_text_output',
					'type'      => 'textarea',
				),
				array(
					'type'      => 'sectionend',
					'id'        => $this->id . '_end',
				),
			) );
		}

	}

endif;