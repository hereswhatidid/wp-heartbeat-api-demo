<?php
/**
 * The WordPress Plugin Boilerplate.
 *
 * A foundation off of which to build well-documented WordPress plugins that also follow
 * WordPress coding standards and PHP best practices.
 *
 * @package   HeartbeatAPIDemo
 * @author    Gabe Shackle <email@hereswhatidid.com>
 * @license   GPL-2.0+
 * @link      http://hereswhatidid.com
 * @copyright 2013 Gabe Shackle
 *
 * @wordpress-plugin
 * Plugin Name: Heartbeat API Demo
 * Plugin URI:  http://hereswhatidid.com/heartbeat-api-demo
 * Description: A simple plugin that demonstrates some features of the new Heartbeat API
 * Version:     1.0.0
 * Author:      Gabe Shackle
 * Author URI:  http://hereswhatidid.com/
 * Text Domain: heartbeat-api-demo-locale
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /lang
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// TODO: replace `class-mediaelement-demo.php` with the name of the actual plugin's class file
require_once( plugin_dir_path( __FILE__ ) . 'class-heartbeat-api-demo.php' );

// Register hooks that are fired when the plugin is activated, deactivated, and uninstalled, respectively.
// TODO: replace HeartbeatAPIDemo with the name of the plugin defined in `class-mediaelement-demo.php`
register_activation_hook( __FILE__, array( 'HeartbeatAPIDemo', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'HeartbeatAPIDemo', 'deactivate' ) );

// TODO: replace HeartbeatAPIDemo with the name of the plugin defined in `class-mediaelement-demo.php`
HeartbeatAPIDemo::get_instance();