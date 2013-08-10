<?php
/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package   HeartbeatAPIDemo
 * @author    Gabe Shackle <gabe@hereswhatidid.com>
 * @license   GPL-2.0+
 * @link      http://hereswhatidid.com
 * @copyright 2013 Gabe Shackle
 */
?>
<div class="wrap">
	<?php screen_icon(); ?>
	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
	
	<p><a href="https://github.com/hereswhatidid/wp-heartbeat-api-demo"><?php _e( 'Plugin Documentation', $this->plugin_slug ); ?></a></p>
	<form method="post" action="options.php">
		<?php settings_fields( $this->plugin_screen_hook_suffix ); ?>
		<?php do_settings_sections( $this->plugin_screen_hook_suffix );	?>
		<?php submit_button(); ?>
	</form>
	<div class="heartbeatapi-testmethods">
		<h3>jQuery Methods</h3>
		<hr />
		<p><strong>Set Heartbeat Interval:</strong> <code>wp.heartbeat.interval( <em>speed</em>, <em>ticks</em> );</code></p>
		<p>
			<a href="#" class="button setinterval-fast">Set Interval - Fast</a>
			<a href="#" class="button setinterval-slow">Set Interval - Slow</a>
			<a href="#" class="button setinterval-normal">Set Interval - Normal</a>
		</p>
		<hr />
		<p><strong>Enqueue Data to Send:</strong> <code>wp.heartbeat.enqueue( <em>handle</em>, <em>data</em>, <em>dont_overwrite</em> );</code></p>
		<p>
			<a href="#" class="button enqueue">Send Data</a>
		</p>
		<hr />
		<p><strong>Check if Enqueued:</strong> <code>wp.heartbeat.isQueued( <em>handle</em> );</code></p>
		<p>
			<a href="#" class="button isenqueued">Check if Enqueued</a>
		</p>
		<hr />
		<p><strong>Has Connection Error:</strong> <code>wp.heartbeat.hasConnectionError( );</code></p>
		<p>
			<a href="#" class="button haserror">Check for Error</a>
		</p>
		<hr />
		<p><strong>Spoof a tick:</strong> <code>wp.heartbeat.tick( <em>data</em>, <em>textStatus</em>, <em>jqXHR</em> );</code></p>
		<p>
			<a href="#" class="button spooftick">Tick!</a>
		</p>
		<hr />
		<p><strong>Spoof an error:</strong> <code>wp.heartbeat.error( <em>jqXHR</em> , <em>textStatus</em>, <em>error</em> );</code></p>
		<p>
			<a href="#" class="button spooferror">Error!</a>
		</p>
		<hr />
	</div>
</div>