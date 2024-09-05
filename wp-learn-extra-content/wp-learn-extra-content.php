<?php
/*
Plugin Name: WP Learn Extra Content
Version: 1.0.0
*/

namespace WP_Learn\Extra_Content;

add_action('admin_init', __NAMESPACE__ . '\add_option');
function add_option() {
	add_settings_field('wp_learn_extra_option', 'Extra Option', __NAMESPACE__ . '\extra_option_field', 'general');
	register_setting('general', 'wp_learn_extra_option');
}
function extra_option_field() {
	echo '<input name="wp_learn_extra_option" id="wp_learn_extra_option" type="text" value="' . get_option('wp_learn_extra_option') . '" />';
}

add_filter( 'the_content', __NAMESPACE__ . '\add_extra_option' );
function add_extra_option( $content ) {
	$extra_option = get_option('wp_learn_extra_option');
	if ( ! $extra_option ) {
		new \WP_Error( 'wp_learn_extra_option', 'Extra content is empty.' );
		return $content;
	}
	$content .= '<p>' . $extra_option . '</p>';
	return $content;
}