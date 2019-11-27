<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WC_BaoKimPayment class.
 *
 * @extends WC_Payment_Gateway
 */
class WC_Gateway_BaoKimPayment extends WC_Payment_Gateway {
	/**
	 * Is test mode active?
	 *
	 * @var bool
	 */
	public $testmode;

	/**
	 * API key
	 *
	 * @var string
	 */
	public $api_key;

	/**
	 * API secret
	 *
	 * @var string;
	 */
	public $api_secret;

	public function __construct() {
		$this->id                 = 'baokim_payment_gateway';
		$this->icon               = apply_filters( 'woocommerce_baokim_payment_icon', plugins_url( 'assets/img/logo.png', WC_BAOKIM_MAIN_FILE ) );
		$this->has_fields         = false;
		$this->method_title       = __( 'Bảo Kim Payment', 'wc-gateway-baokim-payment' );
		$this->method_description = '';

		$this->title = __( 'Bảo Kim Payment', 'wc-gateway-baokim-payment' );
		$this->description  = __( 'Bảo Kim Payment', 'wc-gateway-baokim-payment' );
		$this->instructions = __( 'Bảo Kim Payment', 'wc-gateway-baokim-payment' );

		$this->testmode             = 'yes' === $this->get_option( 'testmode' );
		$this->api_secret           = $this->testmode ? $this->get_option( 'test_api_secret' ) : $this->get_option( 'api_secret' );
		$this->api_key           = $this->testmode ? $this->get_option( 'test_api_key' ) : $this->get_option( 'api_key' );

		// Set API key and secret
		WC_BaoKimPayment_API::set_api_key( $this->api_key );
		WC_BaoKimPayment_API::set_api_secret( $this->api_secret );

		// Load the settings.
		$this->init_form_fields();
		$this->init_settings();
		
		// Actions
		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'payment_scripts' ) );

		if ( $this->testmode ) {
			$this->view_transaction_url = 'http://sandbox.baokim.vn/txn/detail/%s';
		} else {
			$this->view_transaction_url = 'https://baokim.vn/txn/detail/%s';
		}
	}

	/**
	 * Initialise Gateway Settings Form Fields
	 */
	public function init_form_fields() {
		$this->form_fields = require( dirname( __FILE__ ) . '/settings.php' );
	}

	/**
	 * Load admin scripts.
	 *
	 */
	public function admin_scripts() {
		wp_enqueue_script( 'wc_baokim_payment', plugins_url( 'assets/js/baokim-payment.js', WC_BAOKIM_MAIN_FILE ) );
	}

	/**
	 * Load scripts.
	 *
	 */
	public function payment_scripts() {
		wp_register_style( 'wc_baokim_payment_styles', plugins_url( 'assets/css/baokim-payment.css', WC_BAOKIM_MAIN_FILE ) );
		wp_enqueue_style( 'wc_baokim_payment_styles' );

		wp_enqueue_script( 'wc_baokim_payment_list', plugins_url( 'assets/js/payment-list.js', WC_BAOKIM_MAIN_FILE ) );
		wp_enqueue_script( 'wc_baokim_payment_form', plugins_url( 'assets/js/wc_baokim_payment_form.js', WC_BAOKIM_MAIN_FILE ) );
	}

	/**
	 * Payment form on checkout page
	 */
	public function payment_fields() {
		$payment_methods = WC_BaoKimPayment_API::request( 'GET', '/payment/api/v4/bpm/list' );

		if ( $payment_methods->code == 0 ) {
			$this->elements_form( $payment_methods->data );
		}
	}

	/**
	 * Renders the Bao Kim Payment elements form.
	 *
	 * @param array $methods
	 * 
	 */
	public function elements_form( $methods ) {
		?>
		<fieldset id="wc-<?php echo esc_attr( $this->id ); ?>-cc-form" class="" style="background:transparent;">
			<?php do_action( 'woocommerce_credit_card_form_start', $this->id ); ?>
			<div class="wc-baokim-payment-list">
				<div class="wc-bkp-row-fluid wc-bkp-method">
					<div class="wc-bkp-method-header wc-bkp-bpm-id" data-bpm_id="<?php echo esc_attr( 0 ) ?>">
						<div class="wc-bkp-icon"><img src="<?php echo esc_url( plugins_url( 'assets/img/baokim.png', WC_BAOKIM_MAIN_FILE ) ) ?>" border="0"></div>
						<div class="wc-bkp-info">
							<span class="wc-bkp-title">Ví Bảo Kim</span>
							<span class="wc-bkp-desc">Thanh toán bằng ví Bảo Kim (Baokim.vn)</span>
						</div>
						<div class="wc-bkp-check-box"></div>
						<div class="wc-bkp-clearfix"></div>
					</div>
				</div>

				<div class="wc-bkp-row-fluid wc-bkp-method">
					<div class="wc-bkp-method-header">
						<div class="wc-bkp-icon"><img src="<?php echo esc_attr( esc_url( plugins_url( 'assets/img/atm.png', WC_BAOKIM_MAIN_FILE ) ) ) ?>" border="0"></div>
						<div class="wc-bkp-info">
							<span class="wc-bkp-title">Thẻ ATM nội địa</span>
							<span class="wc-bkp-desc">Chọn thẻ ngân hàng thanh toán</span>
						</div>
						<div class="wc-bkp-check-box" id="wc-bkp-atm"></div>
						<div class="wc-bkp-clearfix"></div>
					</div>
					<div class="wc-bkp-bank-list">
						<!-- START FOREACH -->
						<?php foreach ( $methods as $method ) { ?>
							<?php
								if ( $method->type == 0 || $method->type == 2 ) {
									continue;
								}
							?>
							<div class="wc-bkp-bank-item">
								<img class="wc-bkp-bpm-id" src="<?php echo esc_attr( esc_url( $method->bank_logo ) ) ?>" alt="Bank logo" data-bpm_id="<?php echo esc_attr( $method->id ) ?>"">
							</div>
						<?php } ?>
						<!-- END FOREACH -->
					</div>
					
				</div>

				<div class="wc-bkp-row-fluid wc-bkp-method">
				<div class="wc-bkp-method-header">
						<div class="wc-bkp-icon"><img src="<?php echo esc_attr( esc_url( plugins_url( 'assets/img/atm.png', WC_BAOKIM_MAIN_FILE ) ) ) ?>" border="0"></div>
						<div class="wc-bkp-info">
							<span class="wc-bkp-title">Thẻ Visa/Master Card</span>
							<span class="wc-bkp-desc">Chọn thẻ thanh toán</span>
						</div>
						<div class="wc-bkp-check-box" id="wc-bkp-visa"></div>
						<div class="wc-bkp-clearfix"></div>
					</div>
					<div class="wc-bkp-bank-list">
						<div class="wc-bkp-bank-item">
							<img class="wc-bkp-bpm-id" src="<?php echo esc_attr( esc_url( plugins_url( 'assets/img/visa.png', WC_BAOKIM_MAIN_FILE ) ) ) ?>" alt="Visa logo" data-bpm_id="<?php echo esc_attr( 128 ) ?>">
						</div>
					</div>
					
				</div>
			</div>
		</fieldset>
		<?php
	}

	/**
	 * Process the payment
	 *
	 * @param int  $order_id Reference.
	 * @return array|void
	 */
	public function process_payment( $order_id ) {
		try {
			$order = wc_get_order( $order_id );
			if ( ! empty( $order->get_customer_note() ) ) {
				$note = $order->get_customer_note();
			} else {
				$note = 'Thanh toán đơn hàng cho ' . get_site_url();
			}

			return $this->call_bkp_order_api( $order, $order_id, $note );
		} catch ( WC_BaoKimPayment_Exception $e ) {
			wc_add_notice( $e->getLocalizedMessage(), 'error' );
			WC_BaoKimPayment_Logger::log( 'Error: ' . $e->getMessage() );

			/* translators: error message */
			$order->update_status( 'failed' );

			return array(
				'result'   => 'fail',
				'redirect' => '',
			);
		}
	}

	/**
	 * Call Bao Kim Payment's Send Order API
	 *
	 * @param object $order
	 * @param string $order_id
	 * @param string $note
	 * @return array
	 */
	public function call_bkp_order_api( $order, $order_id, $note ) {
		if ( !empty( $order->get_billing_address_1() ) ) {
			$customerAdd = $order->get_billing_address_1();
		} else if ( !empty( $order->get_billing_address_2() ) ) {
			$customerAdd = $order->get_billing_address_2();
		} else {
			$customerAdd = '';
		}
		$params = array(
			'mrc_order_id' => $this->generate_mrc_order_id( $order_id ),
			'total_amount' => ( int ) $order->get_total(),
			'description' => $note,
			'url_success' => get_site_url() . '/checkout/order-received/' . $order_id . '/?key=' . $order->get_order_key(),
			'bpm_id' => ( int ) $_POST['wc_bpm_id'] ?? 0,
			'webhooks' => get_site_url() . '/wc-api/wc_baokim_payment',
			'accept_bank' => WC_BaoKimPayment_Option::get_checkbox_option( 'accept_bank', 1 ),
			'accept_cc' => WC_BaoKimPayment_Option::get_checkbox_option( 'accept_cc', 1 ),
			'accept_qrpay' => WC_BaoKimPayment_Option::get_checkbox_option( 'accept_qrpay', 1 ),
			'customer_email' => $order->get_billing_email(),
			'customer_phone' => $order->get_billing_phone(),
			'customer_name' => $order->get_billing_first_name() . ' '. $order->get_billing_last_name(),
			'customer_address' => $customerAdd
		);
		
		$res = WC_BaoKimPayment_API::request( 'POST', '/payment/api/v4/order/send', $params, 'Sorry, we are unable to process your payment at this time. Please retry later.' );
		
		if ( $res->code == WC_BaoKimPayment_API::ERR_NONE ) {
			$order->update_status('processing');
			return array(
				'result'   => 'success',
				'redirect' => $res->data->payment_url,
			);
		} else {
			$order->update_status( 'failed' );
			return array(
				'result'   => 'fail',
				'redirect' => '',
			);
		}
	}

	/**
	 * Genrate merchant order id
	 *
	 * @param int $order_id
	 * @return string
	 */
	public function generate_mrc_order_id( $order_id ) {
		return 'wp_' . get_current_user_id() . time() . '_' . $order_id;
	}
}