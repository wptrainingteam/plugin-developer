<?php
/**
 * Plugin Name: WP Learn Extra Content
 * Version: 1.0.0
 */

register_activation_hook( __FILE__, 'wp_learn_extra_content_activation' );
function wp_learn_extra_content_activation() {
	add_option( 'wp_learn_extra_content_extra_option', 'Default extra content' );
}

add_action( 'admin_init', 'wp_learn_extra_content_add_option' );
function wp_learn_extra_content_add_option() {
	add_settings_field( 'extra_option', 'Extra Option', 'wp_learn_extra_content_extra_option_field', 'general' );
	register_setting( 'general', 'wp_learn_extra_content_extra_option' );
}

function wp_learn_extra_content_extra_option_field() {
	echo '<input name="wp_learn_extra_content_extra_option" id="wp_learn_extra_content_extra_option" type="text" value="' . esc_html( get_option( 'wp_learn_extra_content_extra_option' ) ) . '" />';
}

add_filter( 'the_content', 'wp_learn_extra_content_add_extra_option' );
function wp_learn_extra_content_add_extra_option( $content ) {
	$extra_option = get_option( 'wp_learn_extra_content_extra_option' );
	if ( ! $extra_option ) {
		new WP_Error( 'wp_learn_extra_content_extra_option', 'Extra content is empty.' );

		return $content;
	}
	$content .= '<p>' . esc_html( $extra_option ) . '</p>';

	return $content;
}

register_deactivation_hook( __FILE__, 'wp_learn_extra_content_deactivation' );
function wp_learn_extra_content_deactivation() {
	delete_option( 'wp_learn_extra_content_extra_option' );
}