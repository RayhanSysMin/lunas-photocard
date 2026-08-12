=== Luna's PhotoCard ===
Contributors: rayhansysmin
Tags: news, elementor, social media, photo card, bangla
Requires at least: 5.8
Tested up to: 6.8
Requires PHP: 7.4
Stable tag: 1.0.10
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Generate branded 1080x1080 photo cards from posts.

== Description ==

Luna's PhotoCard creates social-ready news cards from WordPress posts. It supports featured images, post titles, editorial shoulders from `_editorial_shoulder`, editorial subheadings from `_editorial_subheading`, dates, logos, template options, Elementor, and shortcode buttons.

Project: https://github.com/RayhanSysMin/lunas-photocard
Author: Rayhan Sardar

== Key Features ==

* Daily New Nation Bangla branded template
* Editable HTML/CSS template files
* Editorial shoulder support from `_editorial_shoulder`
* Frontend export through bundled html2canvas
* Elementor widget
* `[daily_new_nation_bangla_photocard_button]` shortcode
* Local font and script assets
* REST endpoint with AJAX fallback
* Bangla or English date rendering

== Installation ==

1. Upload `lunas-photocard` to `/wp-content/plugins/`.
2. Activate from Plugins -> Installed Plugins.
3. Go to DNN PhotoCard -> Settings.
4. Choose a template and configure logo, colors, and CTA text.

== Usage ==

Automatic button:
Enable the auto button to add a PhotoCard download button to single posts with featured images.

Shortcode:

`[daily_new_nation_bangla_photocard_button text="Download PhotoCard"]`

Template files:

`/wp-content/plugins/lunas-photocard/templates/`

== Frequently Asked Questions ==

= Does this support the news shoulder? =

Yes. The card reads `_editorial_shoulder` from post meta and hides the shoulder line when it is empty.

= Does it require Elementor? =

No. Elementor is optional. Use the shortcode or automatic button without Elementor.

= Where are generated images stored? =

Images render in the browser and download directly. Nothing is uploaded to the server.

= Does it load external assets? =

No. Fonts and html2canvas are bundled locally.

== Changelog ==

= 1.0.10 =
* Added math-based browser fitting that measures available space and scales the title/subheading group when needed.
* Improved vertical centering between the featured image and CTA so title, shoulder, and subheading blocks keep balanced breathing room.

= 1.0.9 =
* Added editorial subheading support from `_editorial_subheading`.
* Added smart title/subheading density calculations for balanced Daily New Nation Bangla cards.
* Added browser-side final fitting to protect the CTA area from long title and subheading combinations.

= 1.0.8 =
* Added visual-unit title calculation for smarter short Bangla headline layout.
* Improved vertical balance for very short no-shoulder titles so one-line headlines do not sit too close to the image.

= 1.0.7 =
* Increased Daily New Nation Bangla headline sizing for clearer mobile and desktop viewing.
* Tuned short, medium, and long title buckets to match the boss-approved large-title sample while keeping CTA spacing intact.

= 1.0.6 =
* Added calmer compact-title sizing for short Bangla headlines with numerals or tight wording.
* Matched title start alignment to the approved Daily New Nation Bangla card.
* Improved live-post wrapping to avoid one-word final lines in compact and medium headlines.
* Added extra short-title buckets for wide sports and health headlines found in live-site testing.
* Preserved the existing large-title layout for longer short headlines.

= 1.0.5 =
* Fixed html entity rendering in titles and other card text.
* Hardened hero image rendering for html2canvas downloads.

= 1.0.4 =
* Fixed downloaded hero photos stretching in html2canvas by rendering the hero image through CSS background layers.

= 1.0.3 =
* Replaced the CTA arrow glyph with a cleaner CSS-drawn chevron.

= 1.0.2 =
* Enlarged CTA text and added the down arrow under it.
* Preserved hero image aspect ratio with a blurred fill background.

= 1.0.1 =
* Added Bangla/English date language setting.
* Bumped plugin version.

= 1.0.0 =
* Rebranded as Luna's PhotoCard.
* Added Daily New Nation Bangla template.
* Added editorial shoulder support.
* Added REST card endpoint with AJAX fallback.
* Added frontend card-data preload for faster generation.
* Added request-scoped template caching.
* Added tighter post access checks and admin capability checks.
* Reset plugin metadata, version, and author.

== Upgrade Notice ==

= 1.0.10 =
Improves title/subheading font scaling and vertical centering in the available space below the featured image.

= 1.0.9 =
Adds subheading rendering below the title with smart title/subheading fitting.

= 1.0.8 =
Improves centering and balance for very short Daily New Nation Bangla headlines.

= 1.0.7 =
Bigger Daily New Nation Bangla card headlines for better readability on phones and desktop screens.

= 1.0.6 =
Improves short-headline spacing and title balance while preserving the existing approved layout.

== Credits ==

Developed and maintained by Rayhan Sardar.

== License ==

This plugin is licensed under the GNU General Public License v2 or later.
