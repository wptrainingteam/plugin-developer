<?php
/**
 * Plugin Name:       Copyright Date Block
 * Description:       Copyright Date Block using React with plain JavaScript
 * Requires at least: 6.6
 * Requires PHP:      7.0
 * Version:           1.0.0
 * Author:            The WordPress Contributors
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       copyright-date-block
 *
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Hooks the block registration on the `init` action.
 */
add_action( 'init', 'rps_copyright_date_block_block_init' );
/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 * @return void
 */
function rps_copyright_date_block_block_init() {
	register_block_type( __DIR__ . '/block' );
}
