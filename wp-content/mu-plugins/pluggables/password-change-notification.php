<?php
/**
 * Disable notifications of password change
 *
 * @package BornDigital
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

// https://wordpress.stackexchange.com/questions/206353/disable-email-notification-after-change-of-password
if ( ! function_exists( 'wp_password_change_notification' ) ) {
	/**
	 * Disable email notification after user reset his own password
	 *
	 * @see https://developer.wordpress.org/reference/functions/wp_update_user/
	 *
	 * @param object $user User object.
	 * @return boolean
	 */
	function wp_password_change_notification( $user ) {
		// if this is regular password change notification, disable it.
		return false;
	}
}

// disable email notification after admin change user's password.
add_filter( 'send_password_change_email', '__return_false' );
