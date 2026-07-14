<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Checkbox option keys and labels, grouped by section.
 * Keys are kept exactly as stored by previous versions.
 */
function wcme_checkbox_sections() {
	return array(
		__( 'WooCommerce Core', 'wc-multiple-email-recipients' ) => array(
			'enable_new'           => __( 'WooCommerce New Order Mail', 'wc-multiple-email-recipients' ),
			'enable_cancelled'     => __( 'WooCommerce Cancelled Order Mail', 'wc-multiple-email-recipients' ),
			'enable_processing'    => __( 'WooCommerce Processing Order Mail', 'wc-multiple-email-recipients' ),
			'enable_completed'     => __( 'WooCommerce Completed Order Mail', 'wc-multiple-email-recipients' ),
			'enable_invoice'       => __( 'WooCommerce Order Invoice Mail', 'wc-multiple-email-recipients' ),
			'enable_on_hold'       => __( 'WooCommerce On Hold Mail <i>(This email only gets triggered when the order gets set from pending or failed to on-hold)</i>', 'wc-multiple-email-recipients' ),
			'enable_refunded'      => __( 'WooCommerce Refunded Order Mail (full and partial refunds)', 'wc-multiple-email-recipients' ),
			'enable_customer_note' => __( 'WooCommerce Customer Note <i>(This email only gets triggered for public notes / Notes to the customer.)</i>', 'wc-multiple-email-recipients' ),
		),
		__( 'WooCommerce Bookings', 'wc-multiple-email-recipients' ) => array(
			'enable_booking_cancelled'    => __( 'WooCommerce Bookings Cancelled Mail', 'wc-multiple-email-recipients' ),
			'enable_booking_confirmed'    => __( 'WooCommerce Bookings Confirmed Mail', 'wc-multiple-email-recipients' ),
			'enable_booking_notification' => __( 'WooCommerce Bookings Manual Notification Mail', 'wc-multiple-email-recipients' ),
			'enable_booking_reminder'     => __( 'WooCommerce Bookings Reminder Mail', 'wc-multiple-email-recipients' ),
			'enable_new_booking'          => __( 'WooCommerce Bookings New Booking Mail', 'wc-multiple-email-recipients' ),
		),
		__( 'WooCommerce Subscriptions', 'wc-multiple-email-recipients' ) => array(
			'enable_customer_completed_renewal_order' => __( 'WooCommerce Subscriptions Completed Renewal Order Mail', 'wc-multiple-email-recipients' ),
			'enable_customer_completed_switch_order'  => __( 'WooCommerce Subscriptions Completed Switch Order Mail', 'wc-multiple-email-recipients' ),
			'enable_customer_payment_retry'           => __( 'WooCommerce Subscriptions Customer Payment Retry Mail', 'wc-multiple-email-recipients' ),
			'customer_processing_renewal_order'       => __( 'WooCommerce Subscriptions Customer Processing Renewal Order Mail', 'wc-multiple-email-recipients' ),
			'enable_customer_renewal_invoice'         => __( 'WooCommerce Subscriptions Customer Renewal Invoice Mail', 'wc-multiple-email-recipients' ),
			'expired_subscription'                    => __( 'WooCommerce Subscriptions Expired Subscription Mail', 'wc-multiple-email-recipients' ),
			'enable_new_renewal_order'                => __( 'WooCommerce Subscriptions New Renewal Order Mail', 'wc-multiple-email-recipients' ),
			'enable_new_switch_order'                 => __( 'WooCommerce Subscriptions New Switch Order Mail', 'wc-multiple-email-recipients' ),
			'enable_suspended_subscription'           => __( 'WooCommerce Subscriptions Suspended Subscription Mail', 'wc-multiple-email-recipients' ),
			'enable_payment_retry'                    => __( 'WooCommerce Subscriptions Payment Retry Mail', 'wc-multiple-email-recipients' ),
		),
	);
}

function wcme_options_page() {

	$options = get_option( 'wcme_settings' );
	if ( ! is_array( $options ) ) {
		$options = array();
	}

	// Prefill the textarea: new 'emails' list, or the legacy email_1..email_5 fields.
	if ( isset( $options['emails'] ) ) {
		$emails_value = $options['emails'];
	} else {
		$legacy = array();
		for ( $i = 1; $i <= 5; $i++ ) {
			if ( ! empty( $options[ 'email_' . $i ] ) ) {
				$legacy[] = $options[ 'email_' . $i ];
			}
		}
		$emails_value = implode( "\n", $legacy );
	}
	?>
	<div class="wrap">
		<h2><?php _e( 'WC Multiple Recipients for E-Mail', 'wc-multiple-email-recipients' ); ?></h2>

		<form method="post" action="options.php">

			<?php settings_fields( 'wcme_settings_group' ); ?>

			<h4><?php printf( __( 'Enter your additional E-Mail recipients, one per line (up to %d).', 'wc-multiple-email-recipients' ), WCME_MAX_EMAILS ); ?></h4>
			<p>
				<textarea id="wcme_settings_emails" name="wcme_settings[emails]" rows="8" cols="70" placeholder="woo@sendtome.com"><?php echo esc_textarea( $emails_value ); ?></textarea>
			</p>

			<h4><?php _e( 'Select the WooCommerce Mails you want to have multiple recipients', 'wc-multiple-email-recipients' ); ?></h4>

			<?php foreach ( wcme_checkbox_sections() as $section_title => $checkboxes ) : ?>
				<h5><?php echo esc_html( $section_title ); ?></h5>
				<?php foreach ( $checkboxes as $key => $label ) : ?>
					<p>
						<input name="wcme_settings[<?php echo esc_attr( $key ); ?>]" value="0" type="hidden">
						<input type="checkbox" id="wcme_settings_<?php echo esc_attr( $key ); ?>" name="wcme_settings[<?php echo esc_attr( $key ); ?>]" value="1"<?php checked( ! empty( $options[ $key ] ) ); ?> />
						<label class="description" for="wcme_settings_<?php echo esc_attr( $key ); ?>"><?php echo wp_kses( $label, array( 'i' => array() ) ); ?></label>
					</p>
				<?php endforeach; ?>
			<?php endforeach; ?>

			<p class="submit">
				<input type="submit" class="button-primary" value="<?php esc_attr_e( 'Save Options', 'wc-multiple-email-recipients' ); ?>" />
			</p>

		</form>

	</div>
	<?php
}

/**
 * Sanitize settings on save. Invalid email addresses are dropped and reported,
 * never silently discarded.
 */
function wcme_sanitize_settings( $input ) {
	$output = array();

	if ( ! is_array( $input ) ) {
		return $output;
	}

	$valid    = array();
	$rejected = array();

	foreach ( preg_split( '/[\r\n,]+/', (string) ( $input['emails'] ?? '' ) ) as $email ) {
		$email = trim( $email );
		if ( '' === $email ) {
			continue;
		}
		if ( is_email( $email ) ) {
			$valid[] = $email;
		} else {
			$rejected[] = $email;
		}
	}

	$valid = array_values( array_unique( $valid ) );

	if ( count( $valid ) > WCME_MAX_EMAILS ) {
		$rejected = array_merge( $rejected, array_slice( $valid, WCME_MAX_EMAILS ) );
		$valid    = array_slice( $valid, 0, WCME_MAX_EMAILS );
	}

	$output['emails'] = implode( "\n", $valid );

	if ( ! empty( $rejected ) ) {
		add_settings_error(
			'wcme_settings',
			'wcme_invalid_emails',
			sprintf(
				__( 'The following entries were not saved (invalid email address or above the limit of %1$d): %2$s', 'wc-multiple-email-recipients' ),
				WCME_MAX_EMAILS,
				esc_html( implode( ', ', $rejected ) )
			)
		);
	}

	foreach ( wcme_checkbox_sections() as $checkboxes ) {
		foreach ( $checkboxes as $key => $label ) {
			$output[ $key ] = empty( $input[ $key ] ) ? 0 : 1;
		}
	}

	return $output;
}

function wcme_add_options_link() {
	add_options_page( 'WC Multiple Recipients for Email', 'WC Multiple Email Recipients', 'manage_options', 'wcme-options', 'wcme_options_page' );
}
add_action( 'admin_menu', 'wcme_add_options_link' );

function wcme_register_settings() {
	register_setting( 'wcme_settings_group', 'wcme_settings', array( 'sanitize_callback' => 'wcme_sanitize_settings' ) );
}
add_action( 'admin_init', 'wcme_register_settings' );
