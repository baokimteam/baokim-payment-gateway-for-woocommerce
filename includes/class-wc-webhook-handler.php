<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WC_Webhook_Handler {
	/**
	 * The secret to use when verifying webhooks.
	 *
	 * @var string
	 */
	protected $secret;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->secret = WC_BaoKimPayment_API::get_api_secret();
		add_action( 'woocommerce_api_wc_baokim_payment', array( $this, 'check_for_webhook' ) );
	}

		/**
	 * Check incoming requests for Bao Kim Payment Webhook data and process them.
	 *
	 */
	public function check_for_webhook() {
		$request_body    = file_get_contents( 'php://input' );
		
		// Validate it to make sure it is legit.
		if ( $this->is_valid_request( $request_body ) ) {
			$this->process_webhook( $request_body );
			status_header( 200 );
			exit;
		} else {
			WC_BaoKimPayment_Logger::log( 'Incoming webhook failed validation: ' . print_r( $request_body, true ) );
			status_header( 400 );
			exit;
		}
	}

	/**
	 * Verify the incoming webhook notification to make sure it is legit.
	 *
	 * @since 4.0.0
	 * @version 4.0.0
	 * @param string $request_headers The request headers from Stripe.
	 * @param string $request_body The request body from Stripe.
	 * @return bool
	 */
	public function is_valid_request($request_body = null ) {
		if ( null === $request_body ) {
			return false;
		}

		if ( ! empty( $this->secret ) ) {
			$data = json_decode( $request_body );

			if ( empty( $data->order ) || empty( $data->txn ) || empty( $data->sign ) ) {
				return false;
			}

			$signData = json_encode( array(
				'order' => $data->order,
				'txn' => $data->txn,
			) );
			$mySign = hash_hmac( 'sha256', $signData, $this->secret );

			if ( $mySign != $data->sign ) {
				return false;
			}
		}

		return true;
	}

	public function process_webhook( $request_body = null ) {
		$data = json_decode( $request_body );
		$order = wc_get_order( $this->get_order_id( $data->order->mrc_order_id ) );

		$order->set_transaction_id( $data->order->txn_id );
		$order->update_status( 'completed' );
		
		// Remove cart.
		if ( isset( WC()->cart ) ) {
			WC()->cart->empty_cart();
		}
	}

	private function get_order_id( $bkp_mrc_order_id ) {
		$index = strrpos( $bkp_mrc_order_id, '_' );
		return substr( $bkp_mrc_order_id, $index + 1 );
	}
}

new WC_Webhook_Handler();