<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WC_BaoKimPayment_Option class
 * 
 * Get Bao Kim Payment gateway settings
 */
class WC_BaoKimPayment_Option {
	
	const SETTING_NAME = 'woocommerce_baokim_payment_gateway_settings';

	/**
	 * Get option by name
	 *
	 * @param string $option_name
	 * @return string
	 */
	public static function get_option( $option_name ) {
		$options = get_option( self::SETTING_NAME );
		return $options[$option_name] ?? '';
	}

	/**
	 * Get all settings
	 *
	 * @return array
	 */
	public static function get_options() {
		return get_option( self::SETTING_NAME );
	}

	/**
	 * Get checkbox option
	 *
	 * @param string $option_name
	 * @param integer $in_int
	 * @return bool/int // return int if $int_int = 1
	 */
	public static function get_checkbox_option( $option_name, $in_int = 0 ) {
		if ( $in_int === 1 ) {
			return (int) ('yes' === self::get_option( $option_name ));
		}
		
		return 'yes' === self::get_option( $option_name );
	}
}

new WC_BaoKimPayment_Option();