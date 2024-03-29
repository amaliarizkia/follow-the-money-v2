<?php
/**
 * Login Screen
 *
 * @package BornDigital
 */

namespace BD\Password\Expiration\Admin;

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Login Screen class to manage login screen
 */
final class Login_Screen {

	/**
	 * Class constructor.
	 */
	public function __construct() {

		add_action( 'wp_login', array( $this, 'wp_login' ), 10, 2 );
		add_action( 'validate_password_reset', array( $this, 'validate_password_reset' ), 10, 2 );
		add_filter( 'login_message', array( $this, 'lost_password_message' ) );

	}

	/**
	 * Enforce password reset after user login, when applicable.
	 *
	 * @action wp_login
	 *
	 * @param string  $user_login user_login.
	 * @param WP_User $user user.
	 */
	public function wp_login( $user_login, $user ) {

		if ( ! Setup::get_user_meta( $user ) ) {

			Setup::save_user_meta( $user );

		}

		if ( ! Setup::is_expired( $user ) ) {

			return;

		}

		// phpcs:ignore
		$GLOBALS['current_user'] = $user; // Required to destroy sessions

		wp_destroy_all_sessions();

		wp_safe_redirect(
			add_query_arg(
				array(
					'action' => 'lostpassword',
					'bdpass' => 'expired',
				),
				wp_login_url()
			),
			302
		);

		exit;

	}

	/**
	 * Disallow using the same password as before on reset.
	 *
	 * @action validate_password_reset
	 *
	 * @param WP_Error $errors WP_Error object.
	 * @param WP_User  $user WP_User object.
	 */
	public function validate_password_reset( $errors, $user ) {

		$new_pass1 = filter_input( INPUT_POST, 'pass1' );
		$new_pass2 = filter_input( INPUT_POST, 'pass2' );

		if (
			! $new_pass1
			||
			! $new_pass2
			||
			$new_pass1 !== $new_pass2
			||
			! Setup::has_expirable_role( $user )
		) {

			return;

		}

		$is_same = wp_check_password( $new_pass1, $user->data->user_pass, $user->ID );

		if ( $is_same ) {

			$errors->add( 'password_already_used', esc_html__( 'You cannot reuse your old password.' ) );

		}

	}

	/**
	 * Display a custom message on the lost password login screen.
	 *
	 * @filter login_message
	 *
	 * @param string $message Message.
	 *
	 * @return string
	 */
	public function lost_password_message( $message ) {

		$action = filter_input( INPUT_GET, 'action', FILTER_SANITIZE_STRING );
		$status = filter_input( INPUT_GET, 'bdpass', FILTER_SANITIZE_STRING );

		if ( 'lostpassword' !== $action || 'expired' !== $status ) {

			return $message;

		}

		$limit = Setup::get_limit();

		return sprintf(
			// translators: %s: error message.
			'<p id="login_error">%s</p><p class="message">%s</p>',
			sprintf(
				// translators: %d: day.
				_n( 'Your password must be reset every %d day.', 'Your password must be reset every %d days.', $limit, 'themedomain' ),
				$limit
			),
			esc_html__( 'Please enter your username or e-mail below and a password reset link will be sent to you.', 'themedomain' )
		);

	}

}
