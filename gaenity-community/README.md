# Gaenity Support Hub

Elegant, one-page community hub for Gaenity entrepreneurs and experts. The plugin delivers a polished landing experience with resource highlights, curated discussions, polls, chat, and onboarding forms—all available through shortcodes or an Elementor widget. Styling inherits the active theme while layering refined layout enhancements.

## Requirements

- WordPress 6.1 or newer (tested up to 6.5)
- PHP 7.4 or newer
- Elementor (optional) 3.7+ for the bundled widget

## Installation

1. Upload the `gaenity-community` directory to `wp-content/plugins/` or install it via the WordPress plugin uploader.
2. Activate **Gaenity Support Hub**. The plugin automatically creates a “Gaenity Support Hub” page containing the main shortcode.
3. Adjust the content or move the `[gaenity_support_hub]` shortcode to any page or Elementor template as needed.

## Shortcodes

| Shortcode | Purpose |
| --- | --- |
| `[gaenity_support_hub]` | Full one-page experience containing hero, discussions, resources, polls, experts, registration, login, chat, and contact sections. |
| `[gaenity_community block="community_home"]` | Outputs only the community discovery section with tabs and featured discussions. |
| `[gaenity_register]` | Displays the registration form with demo select options. |
| `[gaenity_login]` | Renders the sign-in form. |
| `[gaenity_chat]` | Renders the demo chat feed and quick post form. |

Each shortcode automatically loads the curated styles (`assets/css/support-hub.css`) and interactivity (`assets/js/support-hub.js`).

## Elementor

After activating the plugin, search for **Gaenity Support Hub** within Elementor’s widget panel. Drag it onto a layout to render the full shortcode without additional configuration. The widget simply wraps the `[gaenity_support_hub]` shortcode so all settings remain centralised.

## Demo Content

The plugin seeds polished placeholder data so that every section feels alive immediately after activation:

- Five featured discussions spanning multiple regions and focus areas
- Five downloadable resource summaries
- Five highlighted experts with focus tags and action buttons
- Three live poll cards with percentage breakdowns
- Five recent chat messages (including anonymous contributors)

Edit any copy by overriding the shortcode output or replacing the auto-created page with your own Elementor design.

## Styling

The CSS keeps typography and global colours inherited from the current theme. Utility custom properties provide subtle shadows, rounded corners, and responsive spacing. Feel free to override the `.gaenity-support-hub` selectors in your theme or child-theme stylesheet for bespoke branding.

## Troubleshooting

- **Missing styling** – Ensure the theme calls `wp_head()` and `wp_footer()` so the enqueued assets load. The plugin defers CSS/JS until a shortcode or the Elementor widget renders.
- **Duplicate landing page** – Delete or unpublish the generated page and place the shortcode on your desired page.
- **Elementor widget not visible** – Confirm Elementor is active, then use the search bar to locate “Gaenity Support Hub” under the *General* category.

## Changelog

### 3.0.0
- Rebuilt the plugin as a single-page support experience with refreshed shortcodes
- Added professional, responsive styling that inherits theme typography
- Bundled Elementor widget wrapper and automatic page seeding
- Populated demo discussions, resources, experts, polls, and chat feed for immediate context
