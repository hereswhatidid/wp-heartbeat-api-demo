(function ($) {
	"use strict";
	$(function () {
		var logDiv = '-----------------------------------------------------------------------------';
		if ( ! typeof wp.heartbeat === 'object' ) {
			return false;
		}
		/**
		 * Display the current interval speed every second
		 */
		var itvInterval = setInterval( function() {
			console.log( 'Current interval speed: ', wp.heartbeat.interval() );
		}, 1000 );

		/**
		 * Heartbeat send event
		 * There has to be some data sent to the server to trigger a tick
		 */
		$(document)
			.on( 'heartbeat-send.heartbeatapi-demo' , function( e, data ) {
				console.log( 'Event: heartbeat-send' );
				console.log( logDiv );

				console.log( 'Data sent: ', data );

				data['triggersend'] = 'trigger';	// needed to kick off Heartbeat Tick AJAX call
			} );

		/**
		 * Heartbeat tick event
		 */
		$(document)
			.on( 'heartbeat-tick.heartbeatapi-demo', function( e, data, jqXHR, d ) {			
				if ( data['heartbeatapi-comments'] ) {
					console.log( 'Event: heartbeat-tick' );
					console.log( 'Data received: ', data );
					console.log( 'XHR: ', d );
					$.each( data['heartbeatapi-comments'], function( index, object ) {
						console.log( 'Comment: ', object.comment_content );
					})
					console.log( logDiv );
				}					
			});

		/**
		 * Heartbeat error event
		 */
		$(document)
			.on('heartbeat-error.heartbeatapi-demo', function(e, jqXHR, textStatus, error) {
				console.log( 'Event: heartbeat-error' );
				console.log( textStatus );
				console.log( error );
				console.log( logDiv );
			});

		$( '.heartbeatapi-testmethods' )
			.on( 'click', '.setinterval-fast', function( e ) {
				e.preventDefault();

				var output = wp.heartbeat.interval( 'fast', 20 );
				console.log( 'Interval Updated: ', output );
			})
			.on( 'click', '.setinterval-slow', function( e ) {
				e.preventDefault();

				var output = wp.heartbeat.interval( 'slow', 20 );
				console.log( 'Interval Updated: ', output );
			})
			.on( 'click', '.setinterval-normal', function( e ) {
				e.preventDefault();

				var output = wp.heartbeat.interval( 'normal', 20 );
				console.log( 'Interval Updated: ', output );
			})
			.on( 'click', '.enqueue', function( e ) {
				e.preventDefault();

				var output = wp.heartbeat.enqueue( 'heartbeatapi-demo', 'thisisatest', true );

				console.log( 'Data Enqueued: ', output );
			})
			.on( 'click', '.isenqueued', function( e ) {
				e.preventDefault();

				var output = wp.heartbeat.isQueued( 'heartbeatapi-demo' );

				console.log( 'Is Enqueued: ', output );
			})
			.on( 'click', '.haserror', function( e ) {
				e.preventDefault();

				var output = wp.heartbeat.hasConnectionError( );

				console.log( 'Has Error: ', output );
			})
	});
}(jQuery));