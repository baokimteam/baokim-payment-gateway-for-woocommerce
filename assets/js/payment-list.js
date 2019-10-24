jQuery( function( $ ) {
	'use strict';
	
	var self;
	const payment_list = {
		form: '',

		init() {
			self = this;
			
			$( document.body ).on( 'click', '.wc-bkp-method-header', this.selectPayment );
			$( document.body ).on( 'click', '.wc-bkp-bank-item', this.selectBank );
		},
		selectPayment() {
			self.toggleActiveSubList( this );
			self.toggleChecked( this );
		},
		toggleActiveSubList( method ) {
			let bankList = $( method ).next( '.wc-bkp-bank-list' );

			if ( bankList.length < 0 ) {
				return;
			}

			if ( bankList.hasClass( 'active' ) ) {
				$( '.wc-bkp-bank-list' ).removeClass( 'active' );
				$( '.wc-bkp-bank-item' ).removeClass( 'active' );
			} else {
				$( '.wc-bkp-bank-list' ).removeClass( 'active' );
				bankList.addClass( 'active' );
			}
		},
		toggleChecked( method ) {
			let checkBox = $( method ).find( '.wc-bkp-check-box' );
			if ( checkBox.hasClass( 'checked' ) ) {
				$( '.wc-bkp-check-box' ).removeClass( 'checked' );
				$( '#wc-bkp-bpm-id' ).val('');
			} else {
				$( '.wc-bkp-check-box' ).removeClass( 'checked' );
				checkBox.addClass( 'checked' );
				self.getBpmId( $( method ) );
			}
		},
		selectBank() {
			if ( $( this ).hasClass( 'active' ) ) {
				$( '.wc-bkp-bank-item' ).removeClass( 'active' );
			} else {
				$( '.wc-bkp-bank-item' ).removeClass( 'active' );
				$( this ).addClass( 'active' );
				self.getBpmId( $( this ) );
			}
		},
		getBpmId( method ) {
			if ( method.hasClass( 'wc-bkp-bpm-id' ) ) {
				method.addClass( 'bpm-id-chosen' );
			} else {
				$( '.wc-bkp-bpm-id' ).removeClass( 'bpm-id-chosen' );
				method.find( '.wc-bkp-bpm-id' ).addClass( 'bpm-id-chosen' );
			}
		}
	}

	payment_list.init();
} )