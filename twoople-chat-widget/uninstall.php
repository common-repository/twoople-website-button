<?php
/**
 * @package Internals
 *
 * Code used when the plugin is removed (not just deactivated but actively deleted through the WordPress Admin).
 */

if( !defined( 'ABSPATH') && !defined('WP_UNINSTALL_PLUGIN') )
    exit();

foreach ( array('twoople_widget_position', 'twoople_widget_username', 'twoople_widget_header', 'twoople_widget_style') as $option) {
	delete_option( $option );
}
