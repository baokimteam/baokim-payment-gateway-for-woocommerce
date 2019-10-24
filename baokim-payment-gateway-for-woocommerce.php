<?php
/**
 * Plugin Name: Bao Kim Payment gateway for WooCommerce
 * Plugin URI: https://baokim.vn
 * Description: Full integration for Bao Kim Payment gateway for WooCommerce
 * Version: 1.0.0
 * Author: Bao Kim
 * License: GPL2
 *
 * @version     1.0.0
 * @package     WooCommerce/Classes/Payment
 * @author      Bao Kim
 */

defined( 'ABSPATH' ) or exit;
// Make sure WooCommerce is active
if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	echo '<div class="error"><p><strong>' . sprintf( esc_html__( 'Bao Kim Payment requires WooCommerce to be installed and active. You can download %s here. ', 'wc-gateway-baokim-payment' ), '<a href="https://woocommerce.com/" target="_blank">WooCommerce</a>' ) . sprintf( esc_html__( 'Click %s to active WooCommerce if you already have it installed', 'wc-gateway-baokim-payment' ), '<a href="http://dev.wordpress/wp-admin/plugins.php?action=activate&plugin=woocommerce%2Fwoocommerce.php&plugin_status=all&paged=1&s&_wpnonce=b49dbf6a59">here</a>' ) . '</strong></p></div>';
	
	require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	deactivate_plugins( plugin_basename( __FILE__ ) );

	return;
}

/**
 * Bao Kim Payment Gateway
 *
 * Provides an Bao Kim Payment Gateway; mainly for testing purposes.
 * We load it later to ensure WC is loaded first since we're extending it.
 *
 * @version		1.0.0
 * @package		WooCommerce/Classes/Payment
 * @author 		SkyVerge
 */
add_action( 'plugins_loaded', 'wc_baokim_payment_gateway_init', 11 );

function wc_baokim_payment_gateway_init() {

	define( 'WC_BAOKIM_MAIN_FILE', __FILE__ );

	class WC_BaoKimPayment {
		/**
		 * Constructor for the gateway.
		 */
		public function __construct() {
			$this->init();
		}

		/**
		 * @var Singleton The reference the *Singleton* instance of this class
		 */
		private static $instance;

		/**
		 * Returns the *Singleton* instance of this class.
		 *
		 * @return Singleton The *Singleton* instance.
		 */
		public static function get_instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Initialize Gateway Settings Form Fields
		 */
		public function init_form_fields() {
			$this->form_fields = require( dirname( __FILE__ ) . '/includes/settings.php' );
		}
		
		/**
		 * Init the plugin after plugins_loaded so environment variables are set.
		 *
		 * @since 1.0.0
		 */
		public function init() {
			require_once dirname( __FILE__ ) . '/vendor/autoload.php';
			require_once dirname( __FILE__ ) . '/includes/class-wc-baokim-payment-option.php';
			require_once dirname( __FILE__ ) . '/includes/class-wc-baokim-payment-exception.php';
			require_once dirname( __FILE__ ) . '/includes/class-wc-baokim-payment-logger.php';
			require_once dirname( __FILE__ ) . '/includes/class-wc-baokim-payment-api.php';
			require_once dirname( __FILE__ ) . '/includes/class-wc-gateway-baokim-payment.php';
			require_once dirname( __FILE__ ) . '/includes/class-wc-webhook-handler.php';

			add_filter( 'woocommerce_payment_gateways', array( $this, 'add_gateways' ) );
			add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'plugin_action_links' ) );
		}

		/**
		 * Add the gateways to WooCommerce.
		 *
		 * @since 1.0.0
		 */
		public function add_gateways( $methods ) {
			$methods[] = 'WC_Gateway_BaoKimPayment';

			return $methods;
		}

		/**
		 * Adds plugin page links
		 * 
		 * @since 1.0.0
		 * @param array $links all plugin links
		 * @return array $links all plugin links + our custom links (i.e., "Settings")
		 */
		function plugin_action_links( $links ) {
			$plugin_links = array(
				'<a href="admin.php?page=wc-settings&tab=checkout&section=baokim_payment_gateway">' . esc_html__( 'Settings', 'wc-gateway-baokim-payment' ) . '</a>',
				'<a target="_blank" href="https://developer.baokim.vn/payment/">' . esc_html__( 'Bao Kim API', 'wc-gateway-baokim-payment' ) . '</a>'
			);
			return array_merge( $plugin_links, $links );
		}
	}

	WC_BaoKimPayment::get_instance();
}
