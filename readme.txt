=== Plugin Name ===
Contributors: iainredmill, kk71
Tags: keyboard, password, protect
Requires at least: 4.6
Tested up to: 6.4.1
Stable tag: 1.3.10
License: GPLv3 or later


This plugin adds an onscreen keyboard powered by Javascript/JQuery to your page on demand.

== Description ==

This plugin adds an onscreen keyboard powered by Javascript/JQuery, ported from Samuel Deering's work on github with his permission under the above licence by Iain Redmill, now working within WordPress, allowing mouse based input or input when working from a touch screen device. Keyboard settings etc can be modified by editing the file. To add a keyboard, enter [oskb] within your page where you wish it to appear.

Apart from improving the working of the older version, the on-screen keyboard for password protected posts and pages in different flavors has been added recently.
Onscreen keyboard for password protected posts and pages. Standard keyboard can still be used.
Onscreen numerical keyboard for password protected posts and pages where password is numerical i.e. a pincode. Furthermore: 12 digit password is submitted automatically.  (Passwords longer than 12 characters, of which the first 12 characters are numbers, are therefore impossible.) Standard keyboard can still be used (but in that case the auto-submit won't work properly - you have to hit the submit button). General passwords can still be used by using standard keyboard.


== Installation ==

1. Upload 'onscreenkeyboard.zip' and extract to the `/wp-content/plugins/on-screen-keyboard` directory
or use the wordpress plugin installer
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Place `[oskb]` in your page at the point you wish a keyboard to appear

== Frequently Asked Questions ==

= I've installed v1.0 but the keyboard fails to pop up =

There is a bug in v1.0, preventing the plugin triggering, please upgrade to v1.2, where this is fixed.

== Screenshots ==

1. Password protected page
2. Settings

== Upgrade Notice ==

Version 1.3:  Numerical or standard keyboard can be used for entering password protected posts.
Version 1.2:  Installer bug fixed, which prevented plugin working - please upgrade if you are having problems making the keyboard appear.

== Changelog ==

= 1.3 =
* Numerical or standard keyboard can be used for entering password protected posts.
= 1.2 =
* Installer debugged, now installs, and keyboard displays properly.
= 1.1 =
* Installer bug fixed, which prevented plugin working (superseeded version).
= 1.0 =
* Plugin Launched
