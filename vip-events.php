<?php
/**
 * @package VIP_EVENTS
 */
/*
Plugin Name: VIP Events
Plugin URI: http://example.com/
Description: This plugin is for creating the events.
Version: 1.0.0
Author: Vipul Shrivastva
Author URI: http://example.com/
License: GPLv2 or later
Text Domain: VIP EVENTS
*/
// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

define( 'ALW_EVENTS_VERSION', '1.0.0' );
define( 'ALW_EVENTS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'ALW_EVENTS_PLUGIN_URL_DIR', plugin_dir_path( __FILE__ ) );

require_once( ALW_EVENTS_PLUGIN_URL_DIR . 'class.front.alw_events.php' );
if ( is_admin() ) {
	require_once( ALW_EVENTS_PLUGIN_URL_DIR . 'class.admin.alw_events.php' );
}