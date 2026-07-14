=== WC Multiple Email Recipients ===
Contributors: conschneider,dinodesigns87
Donate link: http://conschneider.de
Tags: WooCommerce,emails,notification,bcc,cc
Requires at least: 5.0
Tested up to: 7.0
Requires PHP: 7.4
Stable tag: 1.5.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin lets you add up to 25 additional email addresses to be used with WooCommerce notification mails.

== Description ==

WooCommerce notification emails only get sent to the customer and admin email. Sometimes you need more than that. This plugin allows you to set up to 25 additional email addresses that can be used as additional email recipients for WooCommerce notification emails. You can select which email you want to have multiple recipients via the settings.

This plugin support WooCommerce, WooCommerce Bookings and WooCommerce Subscriptions. It is compatible with WooCommerce High-Performance Order Storage (HPOS).

The duplicated mails sent by this plugin will not show up in an email logger as a stand alone entry as they are sent using "BCC". The BCC emails will however be visible in the email header.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/plugin-name` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Use the Settings->WC Multiple Email Recipients screen to configure the plugin


== Frequently Asked Questions ==

= What kind of emails are supported? =

The following WooCommerce notification emails are supported:

= WooCommerce =

* WooCommerce New Order Mail
* WooCommerce Cancelled Order Mail
* WooCommerce Processing Order Mail
* WooCommerce Completed Order Mail
* WooCommerce Order Invoice Mail
* WooCommerce Refunded Order Mail
* WooCommerce Partial Refund Order Mail
* WooCommerce Customer Note

= WooCommerce Bookings =

* WooCommerce Bookings Cancelled Mail
* WooCommerce Bookings Confirmed Mail
* WooCommerce Bookings Manual Notification Mail
* WooCommerce Bookings Reminder Mail
* WooCommerce Bookings New Booking Mail

= WooCommerce Subscriptions =

* WooCommerce Subscriptions Completed Renewal Order Mail
* WooCommerce Subscriptions Completed Switch Order Mail
* WooCommerce Subscriptions Customer Payment Retry Mail
* WooCommerce Subscriptions Customer Processing Renewal Order Mail
* WooCommerce Subscriptions Customer Renewal Invoice Mail
* WooCommerce Subscriptions Expired Subscription Mail
* WooCommerce Subscriptions New Renewal Order Mail
* WooCommerce Subscriptions New Switch Order Mail
* WooCommerce Subscriptions Suspended Subscription Mail
* WooCommerce Subscriptions Payment Retry Mail

= How do the emails get sent? =

The duplicated mails sent by this plugin are sent using "BCC". Thus they will not show up in an email logger.

= I updated from an older version. Do I need to re-save my settings? =

No. Your existing email addresses and selections keep working as they are. The next time you save the settings page they are stored in the new format automatically.

== Screenshots ==

1. Settings page: recipient list and WooCommerce core emails
2. Settings page: WooCommerce Bookings and Subscriptions emails

== Changelog ==

= 1.5.0 =
* New: Up to 25 additional recipients, entered as a simple list (one email per line). Existing addresses from older versions are picked up automatically - no re-save needed.
* Fix: Empty email fields no longer produce malformed Bcc headers.
* Fix: PHP warnings when sending emails with settings that were never saved.
* New: Invalid email addresses are rejected on save with a notice naming them.
* New: Declared compatibility with WooCommerce High-Performance Order Storage (HPOS).
* Dev: Settings are now sanitized and escaped, text domain aligned with the plugin slug.
* Test: Compatibility tested with WordPress 7.0 and WooCommerce 10.9.

= 1.4.1 =
* Fix: Add default options values to prevent PHP Warning.

= 1.4.0 =
* Added support for public customer note notifications.

= 1.3.1 =
* Fixed id for customer_on_hold_order filter.

= 1.3 =
* Added support for partial refund email notification.

= 1.2.5 =
* Test: Compatbility tests for WordPress 5.4.x and WooCommerce 4.0.x

= 1.2.5 =
* Test: Compatbility tests for WordPress 5.03 and WooCommerce 3.5.4

= 1.2.4 =
* Test: Compatbility tests for WordPress 4.9.9

= 1.2.3 =
* Fix: Fix for missing argument of main function. Shoutout to John from digitalproductsnow dot com. Thanks!

= 1.2.3 =
* Fix: Added support to for On-Hold emails in WooCommerce. Shoutout to @dinodesigns87. Thanks!

= 1.2.2 =
* Fix: Ensure WooCommerce 3 compatibility by expanding methods.

= 1.2.1 =
* Fix: Settings did not correctly save for WooCommerce Subscriptions and WooCommerce Bookings.

= 1.2 =
* added support for WooCommerce Subscriptions.

= 1.1 =
* added support for WooCommerce Bookings.

= 1.0 =
* Initial version.
