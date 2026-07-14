<?php

/* our functions for controlling the mail sending */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Maximum number of additional recipients.
 */
define( 'WCME_MAX_EMAILS', 25 );

/**
 * Map of WooCommerce email ids to the wcme_settings option key that enables them.
 * Option key names are kept exactly as stored by previous versions so existing
 * settings keep working without a re-save.
 */
function wcme_email_option_map() {
	return array(
		// WooCommerce core
		'new_order'                         => 'enable_new',
		'cancelled_order'                   => 'enable_cancelled',
		'customer_processing_order'         => 'enable_processing',
		'customer_completed_order'          => 'enable_completed',
		'customer_invoice'                  => 'enable_invoice',
		'customer_refunded_order'           => 'enable_refunded',
		'customer_partially_refunded_order' => 'enable_refunded',
		'customer_on_hold_order'            => 'enable_on_hold',
		'customer_note'                     => 'enable_customer_note',
		// WooCommerce Bookings
		'booking_cancelled'                 => 'enable_booking_cancelled',
		'booking_confirmed'                 => 'enable_booking_confirmed',
		'booking_notification'              => 'enable_booking_notification',
		'booking_reminder'                  => 'enable_booking_reminder',
		'new_booking'                       => 'enable_new_booking',
		// WooCommerce Subscriptions
		'customer_completed_renewal_order'  => 'enable_customer_completed_renewal_order',
		'customer_completed_switch_order'   => 'enable_customer_completed_switch_order',
		'customer_payment_retry'            => 'enable_customer_payment_retry',
		'customer_processing_renewal_order' => 'customer_processing_renewal_order', // legacy key name, no enable_ prefix
		'customer_renewal_invoice'          => 'enable_customer_renewal_invoice',
		'expired_subscription'              => 'expired_subscription', // legacy key name, no enable_ prefix
		'new_renewal_order'                 => 'enable_new_renewal_order',
		'new_switch_order'                  => 'enable_new_switch_order',
		'suspended_subscription'            => 'enable_suspended_subscription',
		'payment_retry'                     => 'enable_payment_retry',
	);
}

/**
 * The configured additional recipients as a clean array of valid email addresses.
 * Reads the 'emails' list (one per line) introduced in 1.5.0 and falls back to
 * the legacy email_1..email_5 fields when the new key has never been saved.
 */
function wcme_get_emails() {
	$options = get_option( 'wcme_settings' );

	if ( isset( $options['emails'] ) ) {
		$emails = preg_split( '/[\r\n,]+/', (string) $options['emails'] );
	} else {
		// Legacy storage from versions before 1.5.0.
		$emails = array();
		for ( $i = 1; $i <= 5; $i++ ) {
			if ( ! empty( $options[ 'email_' . $i ] ) ) {
				$emails[] = $options[ 'email_' . $i ];
			}
		}
	}

	$emails = array_filter( array_map( 'trim', $emails ), 'is_email' );

	return array_slice( array_values( array_unique( $emails ) ), 0, WCME_MAX_EMAILS );
}

/**
 * Add our recipients as Bcc to the enabled WooCommerce emails.
 */
function wcme_multiple_recipients( $headers = '', $id = '' ) {
	$options = get_option( 'wcme_settings' );
	$map     = wcme_email_option_map();

	if ( isset( $map[ $id ] ) && ! empty( $options[ $map[ $id ] ] ) ) {
		$emails = wcme_get_emails();
		if ( ! empty( $emails ) ) {
			$headers .= 'Bcc: ' . implode( ',', $emails ) . "\r\n";
		}
	}

	return $headers;
}

// Hook me in! Only when WooCommerce is active.
add_action( 'plugins_loaded', function() {
	if ( class_exists( 'WooCommerce' ) ) {
		add_filter( 'woocommerce_email_headers', 'wcme_multiple_recipients', 10, 2 );
	}
} );
