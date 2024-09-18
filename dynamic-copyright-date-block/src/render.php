<?php
$block_props = get_block_wrapper_attributes();
$starting_year = $attributes['startingYear'];
$current_year = date( 'Y' );
?>
<p <?php echo $block_props?>>
	Copyright © <?php echo $starting_year?> - <?php echo $current_year; ?>
</p>
