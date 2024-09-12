<?php
/**
 * Returns an array of internal JavaScript dependencies for this block.
 * Used when enqueueing the block editorScript script in the editor.
 * Any block dependencies need to be manually added to this list.
 * The 'wp-polyfill' dependency is required for developing blocks without support ES2015+ language features and APIs
 * https://github.com/WordPress/gutenberg/tree/HEAD/packages/babel-preset-default#polyfill
 *
 */
return
	array( 'dependencies' =>
		       array(
			       'wp-block-editor',
			       'wp-blocks',
			       'wp-components',
			       'wp-element',
			       'wp-i18n',
			       'wp-polyfill'
		       ),
	       'version' => '1.0.0'
	);
