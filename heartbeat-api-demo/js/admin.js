(function ($) {
	"use strict";
	$(function () {
		$(document)
			.on( 'heartbeat-tick', function( event, data, textStatus, jqXHR ) {
				console.log( 'Heartbeat Tick');
				console.log( 'Event: ', event );
				console.log( 'Data: ', data );
				console.log( 'Text Status: ', textStatus );
				console.log( 'jqXHR: ', jqXHR );
				console.log( '------------------------------------------------------------' );
			} )
			.on( 'heartbeat-send', function( event, data ) {
				console.log( 'Heartbeat Send');
				console.log( 'Event: ', event );
				console.log( 'Data: ', data );
				console.log( '------------------------------------------------------------' );
			} )
			.on( 'heartbeat-connection-lost', function( error ) {
				console.log( 'Heartbeat Connection Lost');
				console.log( 'Error: ', error );
				console.log( '------------------------------------------------------------' );
			} )
			.on( 'heartbeat-connection-restored', function(  ) {
				console.log( 'Heartbeat Connection Restored');
				console.log( '------------------------------------------------------------' );
			} )

		var intervalDisplay = setInterval( function() {
			if ( typeof wp === 'object' ) {
				console.log( 'Current Heartbeat Interval Time: ', wp.heartbeat.interval() );
			}
		}, 1000 );

		$( '.interval-fast' )
			.on( 'click', function( e ) {
				e.preventDefault();

				wp.heartbeat.interval( 'fast', 10 );

				alert( 'Heartbeat Interval set to "fast"' );
			} );
		$( '.interval-slow' )
			.on( 'click', function( e ) {
				e.preventDefault();

				wp.heartbeat.interval( 'slow', 10 );

				alert( 'Heartbeat Interval set to "slow"' );
			} );

		$( '.enqueue-data' )
			.on( 'click', function( e ) {
				e.preventDefault();

				wp.heartbeat.enqueue( 'mytestdata', $( '#enqueue-value' ).val(), true );

				alert( 'Heartbeat Data Enqueued.  Value set to "' + $( '#enqueue-value' ).val() + '"' );
			} );
	});
}(jQuery));