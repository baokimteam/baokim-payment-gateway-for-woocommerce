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
		'webhook_url' => array(
			'title' => __( 'Webhook url', 'wc-gateway-baokim-payment' ),
			'type' => 'text',
			'description' => __( 'URL to received webhook', 'wc-gateway-baokim-payment' ),
			'default' => '',
			'desc_tip' => true,
		)
	)
);