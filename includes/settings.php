<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

return apply_filters(
	'wc_baokim_payment_settings',
	array(
		'enabled' => array(
			'title' => __( 'Enable/Disable', 'baokim-payment' ),
			'label' => __( 'Enable Bao Kim Payment', 'baokim-payment' ),
			'type' => 'checkbox',
			'default' => 'yes',
		),
		'api_key_title' => array(
			'title' => __( 'Setup your API', 'wc-gateway-baokim-payment' ),
			'type' => 'title',
			'description' => sprintf( __( '<a href="%1$s" target="_blank">Sign up</a> for a Bao Kim account, and <a href="%2$s" target="_blank">get your API keys</a>. Click here to read more about <a href="%3$s" target="_blank">Test Mode</a>', 'woocommerce-gateway-stripe' ), 'https://vnid.net/register?site=baokim', 'https://www.baokim.vn/api-key/create-api-key', 'https://developer.baokim.vn/payment/#mi-trng-sandboxtest' )
		),
		'testmode' => array(
			'title' => __( 'Test mode', 'wc-gateway-baokim-payment' ),
			'label' => __( 'Enable Test Mode', 'wc-gateway-baokim-payment' ),
			'type' => 'checkbox',
			'description' => __( 'Place the payment gateway in test mode using test API keys.', 'woocwc-gateway-baokim-payment' ),
			'default' => 'yes',
			'desc_tip' => true,
		),
		'api_key' => array(
			'title' => __( 'API key', 'wc-gateway-baokim-payment' ),
			'type' => 'text',
			'description' => __( 'Get your API key from your Bao Kim account.', 'wc-gateway-baokim-payment' ),
			'default' => '',
			'desc_tip' => true,
		),
		'api_secret' => array(
			'title' => __( 'API secret', 'wc-gateway-baokim-payment' ),
			'type' => 'password',
			'description' => __( 'Get your API secret from your Bao Kim account.', 'wc-gateway-baokim-payment' ),
			'default' => '',
			'desc_tip' => true,
		),
		'test_api_key' => array(
			'title' => __( 'Test API key', 'wc-gateway-baokim-payment' ),
			'type' => 'text',
			'description' => __( 'Get your API key from your Bao Kim account.', 'wc-gateway-baokim-payment' ),
			'default' => '',
			'desc_tip' => true,
		),
		'test_api_secret' => array(
			'title' => __( 'Test API secret', 'wc-gateway-baokim-payment' ),
			'type' => 'password',
			'description' => __( 'Get your API secret from your Bao Kim account.', 'wc-gateway-baokim-payment' ),
			'default' => '',
			'desc_tip' => true,
		),
		'logging' => array(
			'title'       => __( 'Logging', 'woocommerce-gateway-baokim-payment' ),
			'label'       => __( 'Log debug messages', 'woocommerce-gateway-baokim-payment' ),
			'type'        => 'checkbox',
			'description' => __( 'Save debug messages to the WooCommerce System Status log.', 'woocommerce-gateway-baokim-payment' ),
			'default'     => 'no',
			'desc_tip'    => true,
		),
		'method_setup' => array(
			'title' => __( 'Setup your payment method', 'woocommerce-gateway-baokim-payment' ),
			'type' => 'title',
			'description' => __( 'Setup your payment method, eg: accept ATM, VISA...', 'woocommerce-gateway-baokim-payment' )
		),
		'accept_bank' => array(
			'title' => __( 'Accept/Reject', 'woocommerce-gateway-baokim-payment' ),
			'type' => 'checkbox',
			'label' => __( 'ATM card', 'woocommerce-gateway-baokim-payment' ),
			'description' => __( 'Accept payment by ATM card.', 'woocommerce-gateway-baokim-payment' ),
			'default'     => 'no',
			'desc_tip'    => true,
		),
		'accept_cc' => array(
			'title' => __( 'Accept/Reject', 'woocommerce-gateway-baokim-payment' ),
			'type' => 'checkbox',
			'label' => __( 'Credit Card', 'woocommerce-gateway-baokim-payment' ),
			'description' => __( 'Accept payment by Credit Card.', 'woocommerce-gateway-baokim-payment' ),
			'default'     => 'no',
			'desc_tip'    => true,
		),
		'accept_qrpay' => array(
			'title' => __( 'Accept/Reject', 'woocommerce-gateway-baokim-payment' ),
			'type' => 'checkbox',
			'label' => __( 'QR Code', 'woocommerce-gateway-baokim-payment' ),
			'description' => __( 'Accept payment by QR Code.', 'woocommerce-gateway-baokim-payment' ),
			'default'     => 'no',
			'desc_tip'    => true,
		),
	)
);