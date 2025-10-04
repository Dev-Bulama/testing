# Gaenity Support Styler

A helper plugin authored by **Skillscore IT Solutions and Training** that forces the Gaenity Support Hub front-end assets to load everywhere. Activate this plugin alongside the main Gaenity Support Hub experience to guarantee that Elementor previews and regular pages inherit the polished styling and JavaScript interactions.

## Usage

1. Upload both the `gaenity-community` plugin and this `gaenity-support-styler` helper plugin to `wp-content/plugins/`.
2. Activate **Gaenity Support Hub** first, then activate **Gaenity Support Styler**.
3. Add the `[gaenity_support_hub]` shortcode or the individual shortcodes (`[gaenity_community]`, `[gaenity_register]`, `[gaenity_login]`, `[gaenity_chat]`) to any page.
4. The styler plugin will automatically enqueue the required CSS and JavaScript assets on every page load and inside Elementor.

## Fallback assets

If the primary plugin's copies of `assets/css/support-hub.css` or `assets/js/support-hub.js` are missing, the helper ships with identical fallbacks so the interface remains consistent.

## Development

- Version: 1.0.0
- Requires WordPress 5.8+
- Requires PHP 7.4+

Feel free to customise the bundled styles if you would like to adjust the design globally.
