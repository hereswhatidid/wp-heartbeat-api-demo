<?php
/**
 * Plugin Name.
 *
 * @package   HeartbeatAPIDemo
 * @author    Gabe Shackle <gabe@hereswhatidid.com>
 * @license   GPL-2.0+
 * @link      http://hereswhatidid.com
 * @copyright 2013 Gabe Shackle
 */

/**
 * Plugin class.
 *
 * @package HeartbeatAPIDemo
 * @author  Gabe Shackle <gabe@hereswhatidid.com>
 */
class HeartbeatAPIDemo {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   1.0.0
	 *
	 * @var     string
	 */
	protected $version = '1.0.0';

	/**
	 * Unique identifier for your plugin.
	 *
	 * Use this value (not the variable name) as the text domain when internationalizing strings of text. It should
	 * match the Text Domain file header in the main plugin file.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_slug = 'heartbeatapi-demo';

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Slug of the plugin screen.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = null;

	/**
	 * Initialize the plugin by setting localization, filters, and administration functions.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {

		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// Add the options page and menu item.
		add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );

		if ( '1' === get_option( $this->plugin_slug . '_logevents' ) ) {
			// Load admin style sheet and JavaScript.
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

			// Load public-facing style sheet and JavaScript.
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		}

		// Define custom functionality. Read more about actions and filters: http://codex.wordpress.org/Plugin_API#Hooks.2C_Actions_and_Filters
		
		add_action( 'admin_init', array( $this, 'initialize_plugin_options' ) );
		
		add_filter( 'heartbeat_settings', array( $this, 'display_heartbeat_settings' ) );
		
		add_action( 'heartbeat_tick', array( $this, 'heartbeat_tick' ), 10, 2 );

		add_filter( 'heartbeat_received', array( $this, 'heartbeat_received' ), 10, 3 );
		add_filter( 'heartbeat_send', array( $this, 'heartbeat_send' ), 10, 2 );
		
		add_action( 'heartbeat_nopriv_tick', array( $this, 'heartbeat_tick' ), 10, 2 );

		add_filter( 'heartbeat_nopriv_received', array( $this, 'heartbeat_received' ), 10, 3 );
		add_filter( 'heartbeat_nopriv_send', array( $this, 'heartbeat_send' ), 10, 2 );


	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Fired when the plugin is activated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog.
	 */
	public static function activate( $network_wide ) {
		if ( false === get_option( $this->plugin_slug . '_logevents' ) ) {
			add_option( $this->plugin_slug . '_logevents', '' );
		}
		if ( false === get_option( $this->plugin_slug . '_autostart' ) ) {
			add_option( $this->plugin_slug . '_autostart', '1' );
		}
		if ( false === get_option( $this->plugin_slug . '_interval' ) ) {
			add_option( $this->plugin_slug . '_interval', '15' );
		}
	}

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses "Network Deactivate" action, false if WPMU is disabled or plugin is deactivated on an individual blog.
	 */
	public static function deactivate( $network_wide ) {
		delete_option( $this->plugin_slug . '_logevents' );
		delete_option( $this->plugin_slug . '_autostart' );
		delete_option( $this->plugin_slug . '_interval' );
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		$domain = $this->plugin_slug;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, FALSE, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
	}

	/**
	 * Register and enqueue admin-specific style sheet.
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_styles() {

		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( $screen->id == $this->plugin_screen_hook_suffix ) {
			wp_enqueue_style( $this->plugin_slug .'-admin-styles', plugins_url( 'css/admin.css', __FILE__ ), $this->version );
		}

	}

	/**
	 * Register and enqueue admin-specific JavaScript.
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_scripts() {

		$screen = get_current_screen();
		wp_enqueue_script( $this->plugin_slug . '-admin-script', plugins_url( 'js/admin.js', __FILE__ ), array( 'jquery' ), $this->version );

	}

	/**
	 * Register and enqueue public-facing style sheet.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_slug . '-plugin-styles', plugins_url( 'css/public.css', __FILE__ ), $this->version );
	}

	/**
	 * Register and enqueues public-facing JavaScript files.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_slug . '-plugin-script', plugins_url( 'js/public.js', __FILE__ ), array( 'jquery' ), $this->version );
	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_admin_menu() {

		$this->plugin_screen_hook_suffix = add_options_page(
			__( 'Heartbeat API Demo', $this->plugin_slug ),
			__( 'Heartbeat API Demo', $this->plugin_slug ),
			'read',
			$this->plugin_slug,
			array( $this, 'display_plugin_admin_page' )
		);

	}

	/**
	 * Initialize the plugin options
	 *
	 * @since    1.0.0
	 */

	public function initialize_plugin_options() {

		if ( false === get_option( $this->plugin_slug . '_logevents' ) ) {
			add_option( $this->plugin_slug . '_logevents' );
		}
		if ( false === get_option( $this->plugin_slug . '_autostart' ) ) {
			add_option( $this->plugin_slug . '_autostart' );
		}
		if ( false === get_option( $this->plugin_slug . '_interval' ) ) {
			add_option( $this->plugin_slug . '_interval', '15' );
		}

		add_settings_section(
			$this->plugin_slug . '_settings_section',
			__( 'HeartbeatAPI Demo Settings', $this->plugin_slug ),
			array( $this, 'settings_callback' ),
			$this->plugin_screen_hook_suffix
		);

		add_settings_field(
			$this->plugin_slug . '_logevents',
			__( 'Log events', $this->plugin_slug ),
			array( $this, 'settings_logevents_callback' ),
			$this->plugin_screen_hook_suffix,
			$this->plugin_slug . '_settings_section',
			array(
				__( 'The Heartbeat API events will be logged to your browser\'s console.', $this->plugin_slug )
			)
		);

		add_settings_field(
			$this->plugin_slug . '_autostart',
			__( 'Autostart', $this->plugin_slug ),
			array( $this, 'settings_autostart_callback' ),
			$this->plugin_screen_hook_suffix,
			$this->plugin_slug . '_settings_section',
			array(
				__( 'Start the Heartbeat automatically on page load.', $this->plugin_slug )
			)
		);

		add_settings_field(
			$this->plugin_slug . '_interval',
			__( 'Heartbeat Interval', $this->plugin_slug ),
			array( $this, 'settings_interval_callback' ),
			$this->plugin_screen_hook_suffix,
			$this->plugin_slug . '_settings_section',
			array(
				__( 'Value must be between 15-60.', $this->plugin_slug )
			)
		);

		register_setting(
			$this->plugin_screen_hook_suffix,
			$this->plugin_slug . '_logevents'
		);

		register_setting(
			$this->plugin_screen_hook_suffix,
			$this->plugin_slug . '_autostart'
		);

		register_setting(
			$this->plugin_screen_hook_suffix,
			$this->plugin_slug . '_interval'
		);

	}

	/**
	 * Display something at the beginning of the settings section
	 */
	public function settings_callback() {
		
	}
	/**
	* Callback for the Log Events field
	*/
	public function settings_logevents_callback( $args ) {

		$value = get_option( $this->plugin_slug . '_logevents' );
		$fieldname = $this->plugin_slug . '_logevents';

		$html = '<label><input type="checkbox" name="' . $fieldname . '" id="' . $fieldname . '" value="1" ' . checked( $value, 1, false ) . ' /> ';
		$html .= $args[0] . '</label>';

		echo $html;

	}
	/**
	* Callback for the Log Events field
	*/
	public function settings_autostart_callback( $args ) {

		$value = get_option( $this->plugin_slug . '_autostart' );
		$fieldname = $this->plugin_slug . '_autostart';

		$html = '<label><input type="checkbox" name="' . $fieldname . '" id="' . $fieldname . '" value="1" ' . checked( $value, 1, false ) . ' /> ';
		$html .= $args[0] . '</label>';

		echo $html;

	}
	/**
	* Callback for the Log Events field
	*/
	public function settings_interval_callback( $args ) {

		$value = get_option( $this->plugin_slug . '_interval' );
		$fieldname = $this->plugin_slug . '_interval';

		$html = '<label><input type="text" name="' . $fieldname . '" id="' . $fieldname . '" value="' . $value . '" /><br />';
		$html .= $args[0] . '</label>';

		echo $html;

	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function display_plugin_admin_page() {
		include_once( 'views/admin.php' );
	}


	/**
	 * Update the default Heartbeat interval based on plugin settings
	 */
	public function display_heartbeat_settings( $settings = null ) {

		if ( ! ( $interval = get_option( $this->plugin_slug . '_interval' ) ) ) {
			$interval = '15';
		}
		$settings['interval'] = $interval;

		$autostart = get_option( $this->plugin_slug . '_autostart' );
		$settings['autostart'] = ( $autostart === '1' );

		return $settings;
	}

	/**
	 * Heartbeat AJAX call received
	 */
	public function heartbeat_received( $response, $data, $screen_id ) {

		if ( isset( $data['triggersend'] ) ) {

			$response['increment'] = 10;
			$comments = get_comments( array( 'number' => 5 ) );
			$response['heartbeatapi-comments'] = $comments;

		}

		return $response;

	}

	public function heartbeat_send( $response, $screen_id ) {

		$response['curscreen'] = $screen_id;
		$response['increment'] = $response['increment'] + 15;

		return $response;
	}

	public function heartbeat_tick( $response, $screen_id ) {
	}
}