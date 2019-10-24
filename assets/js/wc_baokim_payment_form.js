jQuery( function( $ ) {
	'use strict';

	const wc_baokim_payment_form = {
		init() {
			if ( $( 'form.woocommerce-checkout' ).length ) {
				this.form = $( 'form.woocommerce-checkout' );
			}
			this.form.on( 'submit', this.onSubmit );
		},
		isBaoKimChosen() {
			return $( '#payment_method_baokim_payment_gateway' ).is( ':checked' );
		},
		isBpmSelected() {
			if ( ! $( '.bpm-id-chosen' ).length ) {
				return false;
			}

			if ( ( $( '#wc-bkp-visa' ).hasClass( 'checked' ) ||  $( '#wc-bkp-visa' ).hasClass( 'checked' ) ) && $( '.bpm-id-chosen' ).data( 'bpm_id' ) === 0 ) {
				return false;
			}

			return true;
		},
		onSubmit() {
			if ( ! wc_baokim_payment_form.isBaoKimChosen() 
				&& wc_baokim_payment_form.isBpmSelected() 
			) {
				return true;
			}
			
			wc_baokim_payment_form.reset();

			let bpmId = $( '.bpm-id-chosen' ).data( 'bpm_id' );
			wc_baokim_payment_form.form.append(
				$( '<input type="hidden" />' )
					.addClass( 'bpm-id' )
					.attr( 'name', 'wc_bpm_id' )
					.val( bpmId )
			);

			return false;
		},

		/**
		 * Removes all Bao Kim Payment hidden fields with IDs from the form.
		 */
		reset: function() {
			$( '.bpm-id' ).remove();
		},
	}
	
	wc_baokim_payment_form.init();
} )