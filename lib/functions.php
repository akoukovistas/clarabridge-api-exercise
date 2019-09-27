<?php
/**
 * This is a collection of helper functions for common parts of the application.
 */

/**
 * Cheeky helper function for debugging.
 *
 * @param $item
 */
function dump( $item ) {
	echo "<pre>";
	var_dump( $item );
	echo "</pre>";
}