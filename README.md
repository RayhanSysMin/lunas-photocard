# Luna's PhotoCard

Luna's PhotoCard is a WordPress plugin for generating branded 1080x1080 news photo cards from posts.

Repository: https://github.com/RayhanSysMin/lunas-photocard  
Author: Rayhan Sardar  
Author profile: https://github.com/RayhanSysMin

## Features

- Browser-side PNG export through bundled html2canvas.
- Daily New Nation Bangla photo card template.
- Featured image, post title, date, and editorial shoulder support.
- Editorial shoulder reads from `_editorial_shoulder`.
- Bangla or English date rendering.
- Template options for logo, seal, colors, and CTA text.
- Shortcode button: `[daily_new_nation_bangla_photocard_button]`.
- Elementor widget support.
- REST endpoint with AJAX fallback.
- Bundled local assets and font files.

## Installation

1. Download the latest release ZIP from GitHub.
2. Upload the plugin folder to `/wp-content/plugins/lunas-photocard/`.
3. Activate **Luna's PhotoCard** in WordPress.
4. Open **Luna's PhotoCard > Settings**.
5. Select the active template and configure logo, colors, CTA text, date language, and button options.

## Usage

Automatic button:

Enable the auto button in settings to add the download button to single posts with featured images.

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

Current version: `1.0.6`

### 1.0.6

- Added compact title sizing for short Bangla headlines.
- Matched title start alignment to the approved Daily New Nation Bangla card.
- Improved live-post wrapping to avoid one-word final lines.
- Added extra short-title buckets for wide sports and health headlines.
- Preserved the existing large-title layout for longer short headlines.

## License

GPLv2 or later.
