# Luna's PhotoCard

Luna's PhotoCard is a WordPress plugin for generating branded 1080x1080 news photo cards from posts.

Repository: https://github.com/RayhanSysMin/lunas-photocard  
Author: Rayhan Sardar  
Author profile: https://github.com/RayhanSysMin

## Features

- Browser-side PNG export through bundled html2canvas.
- Daily New Nation Bangla photo card template.
- Featured image, post title, date, editorial shoulder, and editorial subheading support.
- Editorial shoulder reads from `_editorial_shoulder`.
- Editorial subheading reads from `_editorial_subheading`.
- Bangla or English date rendering.
- Template options for logo, seal, colors, and CTA text.
- Editor/Admin-only shortcode button: `[daily_new_nation_bangla_photocard_button]`.
- Editor/Admin-only Elementor widget support.
- Editor/Admin-only REST endpoint with AJAX fallback.
- Bundled local assets and font files.

## Installation

1. Download the latest release ZIP from GitHub.
2. Upload the plugin folder to `/wp-content/plugins/lunas-photocard/`.
3. Activate **Luna's PhotoCard** in WordPress.
4. Open **Luna's PhotoCard > Settings**.
5. Select the active template and configure logo, colors, CTA text, date language, and button options.

## Usage

Automatic button:

Enable the auto button in settings to add the download button to single posts with featured images. The button only renders for users with Editor-level access or higher.

Shortcode:

```text
[daily_new_nation_bangla_photocard_button text="Download PhotoCard"]
```

Template files live in:

```text
templates/
```

## Requirements

- WordPress 5.8 or newer.
- PHP 7.4 or newer.
- Featured images enabled for the posts you want to render.

## Release

Current version: `1.0.12`

### 1.0.12

- Increased the Daily New Nation Bangla hero fill blur so portrait/narrow featured images keep the main photo in focus.
- Softened the blurred background layer with lower opacity and calmer saturation/brightness.

### 1.0.11

- Restricted PhotoCard download buttons to Editor-level users and above.
- Locked card data generation behind the same capability for REST, AJAX, shortcode, and Elementor paths.

### 1.0.10

- Added math-based browser fitting that measures available space and scales the title/subheading group when needed.
- Improved vertical centering between the featured image and CTA so title, shoulder, and subheading blocks keep balanced breathing room.

### 1.0.9

- Added editorial subheading support from `_editorial_subheading`.
- Added smart title/subheading density calculations for balanced Daily New Nation Bangla cards.
- Added browser-side final fitting to protect CTA spacing.

### 1.0.8

- Added visual-unit title calculation for smarter short Bangla headline layout.
- Improved vertical balance for very short no-shoulder titles.

### 1.0.7

- Increased Daily New Nation Bangla headline sizing for clearer mobile and desktop viewing.
- Tuned title buckets against the boss-approved large-title sample while keeping CTA spacing intact.

### 1.0.6

- Added compact title sizing for short Bangla headlines.
- Matched title start alignment to the approved Daily New Nation Bangla card.
- Improved live-post wrapping to avoid one-word final lines.
- Added extra short-title buckets for wide sports and health headlines.
- Preserved the existing large-title layout for longer short headlines.

## License

GPLv2 or later.
