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
		$this->icon               = apply_filters( 'woocommerce_baokim_payment_icon', '' );
		$this->has_fields         = false;
		$this->method_title       = __( 'Bảo Kim Payment', 'wc-gateway-baokim-payment' );
		$this->method_description = sprintf( __( '<a href="%1$s" target="_blank">Sign up</a> for a Bao Kim account, and <a href="%2$s" target="_blank">get your API keys</a>. Click here if you want to try <a href="%3$s" target="_blank">Test Mode</a>', 'woocommerce-gateway-stripe' ), 'https://vnid.net/register?site=baokim', 'https://www.baokim.vn/api-key/create-api-key', 'https://developer.baokim.vn/payment/#mi-trng-sandboxtest' );

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
	 * @since 1.0.0
	 */
	public function admin_scripts() {
		wp_register_script( 'wc_baokim_payment', plugins_url( 'assets/js/baokim-payment.js', WC_BAOKIM_MAIN_FILE ) );
		wp_enqueue_script( 'wc_baokim_payment_admin', plugins_url( 'assets/js/baokim-payment.js', WC_BAOKIM_MAIN_FILE ) );
	}

	/**
	 * Load scripts.
	 *
	 * @since 1.0.0
	 */
	public function payment_scripts() {
		wp_register_style( 'wc_baokim_payment_styles', plugins_url( 'assets/css/baokim-payment.css', WC_BAOKIM_MAIN_FILE ) );
		wp_enqueue_style( 'wc_baokim_payment_styles' );
	}

	/**
	 * Payment form on checkout page
	 */
	public function payment_fields() {
		$payment_methods = WC_BaoKimPayment_API::request( 'GET', '/payment/api/v4/bpm/list' );

		if ( $payment_methods->code == 0 ) {
			$this->elements_form( $methods );
		}
	}

	/**
	 * Renders the Bao Kim Payment elements form.
	 *
	 * @param array $methods
	 * 
	 * @since 1.0.0
	 */
	public function elements_form( $methods ) {
		?>
		<fieldset id="wc-<?php echo esc_attr( $this->id ); ?>-cc-form" class="" style="background:transparent;">
			<?php do_action( 'woocommerce_credit_card_form_start', $this->id ); ?>
			<div class="wc-baokim-payment-list">
				<div class="wc-bkp-row-fluid wc-bkp-method">
					<div class="wc-bkp-icon"><img src="<?php echo esc_url( plugins_url( 'assets/img/baokim.jpg', WC_BAOKIM_MAIN_FILE ) ) ?>" border="0"></div>
					<div class="wc-bkp-info">
						<span class="wc-bkp-title">Ví Bảo Kim</span>
						<span class="wc-bkp-desc">Thanh toán bằng ví Bảo Kim (Baokim.vn)</span>
					</div>
					<div class="wc-bkp-check-box"></div>
					<div class="wc-bkp-clearfix"></div>
				</div>

	
				<div class="wc-bkp-row-fluid wc-bkp-method">
					<div class="wc-bkp-icon"><img src="<?php echo esc_url( plugins_url( 'assets/img/atm.png', WC_BAOKIM_MAIN_FILE ) ) ?>" border="0"></div>
					<div class="wc-bkp-info">
						<span class="wc-bkp-title">Ví Bảo Kim</span>
						<span class="wc-bkp-desc">Thanh toán bằng ví Bảo Kim (Baokim.vn)</span>
					</div>
					<div class="wc-bkp-check-box"></div>
					<div class="wc-bkp-clearfix"></div>
				</div>
				<!-- START FOREACH -->
				<?php foreach ( $methods as $method ) { ?>
					<?php if ( $method->type == 0 ) ?>
					
				<?php } ?>
				<!-- END FOREACH -->
			</div>
		</fieldset>
		<?php
	}
}