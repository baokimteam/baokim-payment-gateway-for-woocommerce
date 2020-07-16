jQuery( function( $ ) {
	'use strict';

	var wc_baokim_payment = {
		init: function() {
			$( document.body ).on( 'change', '#woocommerce_baokim_payment_gateway_testmode', this.handleOnChange );
			
			$( '#woocommerce_baokim_payment_gateway_testmode' ).change();
		},

		handleOnChange: function() {
			let test_api_secret = $( '#woocommerce_baokim_payment_gateway_test_api_secret' ).parents( 'tr' ).eq( 0 ),
				test_api_key = $( '#woocommerce_baokim_payment_gateway_test_api_key' ).parents( 'tr' ).eq( 0 ),
				test_merchant_id = $( '#woocommerce_baokim_payment_gateway_test_merchant_id' ).parents( 'tr' ).eq( 0 ),
				merchant_id = $( '#woocommerce_baokim_payment_gateway_merchant_id' ).parents( 'tr' ).eq( 0 ),
				api_secret = $( '#woocommerce_baokim_payment_gateway_api_secret' ).parents( 'tr' ).eq( 0 ),
				api_key = $( '#woocommerce_baokim_payment_gateway_api_key' ).parents( 'tr' ).eq( 0 );

			if ( $( this ).is( ':checked' ) ) {
				test_api_secret.show();
				test_merchant_id.show();
				test_api_key.show();
				api_secret.hide();
				api_key.hide();
				merchant_id.hide();
			} else {
				test_api_secret.hide();
				test_merchant_id.hide();
				test_api_key.hide();
				api_secret.show();
				api_key.show();
				merchant_id.show();
			}
		}
	};

	wc_baokim_payment.init();
} )