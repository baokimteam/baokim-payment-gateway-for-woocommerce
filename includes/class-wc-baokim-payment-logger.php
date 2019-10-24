<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Log all things!
 *
 * @since 4.0.0
 * @version 4.0.0
 */
class WC_BaoKimPayment_Logger {

	public static $logger;
	const WC_LOG_FILENAME = 'woocommerce-gateway-baokim-payment';

	public static function log( $message, $start_time = null, $end_time = null ) {
		if ( ! class_exists('WC_Logger') ) {
			return;
		}
		
		if ( apply_filters( 'wc_baokim_payment_logging', true, $message ) ) {
			if ( empty( self::$logger ) ) {
				if ( version_compare( WC_VERSION, '3.0', '<' ) ) {
					self::$logger = new WC_Logger();
				} else {
					self::$logger = wc_get_logger();
				}
			}
			
			$settings = WC_BaoKimPayment_Option::get_options();
			
			if ( empty( $settings ) || isset( $settings['logging'] ) && 'yes' !== $settings['logging'] ) {
				return;
			}

			if ( ! is_null( $start_time ) ) {

				$formatted_start_time = date_i18n( get_option( 'date_format' ) . ' g:ia', $start_time );
				$end_time             = is_null( $end_time ) ? current_time( 'timestamp' ) : $end_time;
				$formatted_end_time   = date_i18n( get_option( 'date_format' ) . ' g:ia', $end_time );
				$elapsed_time         = round( abs( $end_time - $start_time ) / 60, 2 );

				$log_entry  = "\n" . '====BAO KIM PAYMENT GATEWAY====' . "\n";
				$log_entry .= '====Start Log ' . $formatted_start_time . '====' . "\n" . $message . "\n";
				$log_entry .= '====End Log ' . $formatted_end_time . ' (' . $elapsed_time . ')====' . "\n\n";

			} else {
				$log_entry  = "\n" . '====BAO KIM PAYMENT GATEWAY====' . "\n";
				$log_entry .= '====Start Log====' . "\n" . $message . "\n" . '====End Log====' . "\n\n";

			}
			
			if ( version_compare( WC_VERSION, '3.0', '<' ) ) {
				self::$logger->add( self::WC_LOG_FILENAME, $log_entry );
			} else {
				self::$logger->debug( $log_entry, array( 'source' => self::WC_LOG_FILENAME ) );
			}
		}
	}
}

new WC_BaoKimPayment_Logger();