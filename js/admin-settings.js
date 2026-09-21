/**
 * Media picker for the Theme Settings admin page (hero slide images, etc.).
 * Scoped entirely to elements with the .dq-image-field wrapper.
 */
( function ( $ ) {
	'use strict';

	$( document ).on( 'click', '.dq-image-select', function ( event ) {
		event.preventDefault();

		var wrapper = $( this ).closest( '.dq-image-field' );
		var frame = wp.media( {
			title: 'Pilih Gambar',
			multiple: false,
			library: { type: 'image' },
			button: { text: 'Gunakan Gambar Ini' },
		} );

		frame.on( 'select', function () {
			var attachment = frame.state().get( 'selection' ).first().toJSON();
			var previewUrl = attachment.sizes && attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url;

			wrapper.find( '.dq-image-value' ).val( attachment.id );
			wrapper.find( '.dq-image-preview' ).attr( 'src', previewUrl ).show();
			wrapper.find( '.dq-image-remove' ).show();
		} );

		frame.open();
	} );

	$( document ).on( 'click', '.dq-image-remove', function ( event ) {
		event.preventDefault();

		var wrapper = $( this ).closest( '.dq-image-field' );
		wrapper.find( '.dq-image-value' ).val( '' );
		wrapper.find( '.dq-image-preview' ).hide().attr( 'src', '' );
		$( this ).hide();
	} );
} )( jQuery );
