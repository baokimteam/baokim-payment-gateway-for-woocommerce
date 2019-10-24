<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use \Firebase\JWT\JWT;

/**
 * WC_BaoKimPayment_API class.
 *
 * Communicates with Bao Kim API.
 */
class WC_BaoKimPayment_API {
	
	/**
	 * Bao Kim API settings
	 */
	const TEST_ENDPOINT = 'https://sandbox-api.baokim.vn';
	const ENDPOINT = 'https://api.baokim.vn';
	const TIMEOUT = 30.0;
	const TIME_EXPIRED = 84600;

	/**
	 * Errors code
	 */
	const ERR_NONE = 0;
	const INVALID_TOKEN = 5;
	const SYSTEM_ERR = 99;
	

	/**
	 * JWT token
	 *
	 * @var string
	 */
	private static $_jwt = '';

	/**
	 * API secret.
	 * @var string
	 */
	private static $_api_secret = '';

	/**
	 * API key.
	 * @var string
	 */
	private static $_api_key = '';

	/**
	 * Set API secret.
	 * @param string $key
	 */
	public static function set_api_secret ( $api_secret ) {
		self::$_api_secret = $api_secret;
	}

	/**
	 * Get API secret.
	 * @return string
	 */
	public static function get_api_secret (): string {
		if ( ! self::$_api_secret ) {
			$options = WC_BaoKimPayment_Option::get_options();

			if ( isset( $options['testmode'], $options['api_key'] ) ) {
				self::set_api_secret( 'yes' === $options['testmode'] ? $options['test_api_secret'] : $options['api_secret'] );
			}
		}
		return self::$_api_secret;
	}

	/**
	 * Set API key.
	 * @param string $key
	 */
	public static function set_api_key ( $api_key ) {
		self::$_api_key = $api_key;
	}

	/**
	 * Get API key.
	 * @return string
	 */
	public static function get_api_key (): string {
		if ( ! self::$_api_key ) {
			$options = WC_BaoKimPayment_Option::get_options();

			if ( self::is_testmode_on() && isset( $options['api_key'] ) ) {
				self::set_api_key( 'yes' === $options['testmode'] ? $options['test_api_key'] : $options['api_key'] );
			}
		}
		return self::$_api_key;
	}
	
	/**
	 * Set JWT
	 *
	 * @param string $jwt
	 */
	public static function set_jwt( $jwt ) {
		self::$_jwt = $jwt;
	}

	/**
	 * Get JWT token
	 *
	 * @return string
	 */
	public static function get_jwt(): string {
		if ( ! self::$_jwt ) {
			self::set_jwt( self::generate_jwt_token() );
		}

		return self::$_jwt;
	}

	/**
	 * Generate JWT token
	 *
	 * @return string
	 */
	public static function generate_jwt_token(): string {
		$issued_at = time();
		$expired_at = $issued_at + self::TIME_EXPIRED;
		$token = array(
			'iat' => $issued_at,
			'exp' => $expired_at,
			'iss' => self::get_api_key(),
			'aud' => self::is_testmode_on() ? self::TEST_ENDPOINT : self::ENDPOINT
		);

		/**
		 * Generate JWT token by using Firebase\JWT
		 */
		$jwt = JWT::encode( $token, self::get_api_secret(), 'HS256' );
		self::set_jwt( $jwt );

		return $jwt;
	}

	/**
	 * Request API
	 *
	 * @param string $method
	 * @param string $uri
	 * @param array $params
	 * @param string $msg
	 * @return object
	 */
	public function request( $method, $uri, $params = array(), $msg = '' ) {
		if ( self::is_testmode_on() ) {
			$url = self::TEST_ENDPOINT . $uri . '?jwt=' . self::get_jwt();
		} else {
			$url = self::ENDPOINT . $uri . '?jwt=' . self::get_jwt();
		}

		if ( empty( $msg ) ) {
			$msg = 'There was a problem connecting to the Bao Kim API endpoint.';
		}
		
		$res = wp_safe_remote_post(
			$url,
			array(
				'method'  => $method,
				'timeout' => self::TIMEOUT,
				'body' => $params
			)
		);

		if ( is_wp_error( $res ) || empty( $res['body'] ) || $res['response']['code'] != 200 ) {
			WC_BaoKimPayment_Logger::log($res['body']);

			throw new WC_BaoKimPayment_Exception( print_r( $res, true ), __( $msg, 'woocommerce-gateway-baokim-payment' ) );
		}

		return json_decode( $res['body'] );
	}

	/**
	 * Is testmode on?
	 *
	 * @return boolean
	 */
	public static function is_testmode_on() {
		if ( empty( WC_BaoKimPayment_Option::get_option( 'testmode' ) ) )
			return false;

		return 'yes' === WC_BaoKimPayment_Option::get_option( 'testmode' );
	}
}