=== Mortify 2026 ===
Contributors: Chris Mortlock
Tags: pwa, mobile, app, wordpress, woocommerce, progressive-web-app
Requires at least: 6.4
Tested up to: 6.7
Requires PHP: 8.1
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

App-style mobile shell for WordPress with PWA support, offline mode, and WooCommerce awareness.

== Description ==

**Mortify 2026** transforms your WordPress site into a mobile-first, app-like experience.  
It creates a self-contained shell under `/app/` that behaves like a Progressive Web App (PWA), with a native-style bottom navigation bar, offline fallback, and optional WooCommerce support.

Ideal for eCommerce sites, blogs, or communities that want a lightweight mobile web app experience — without requiring a native app.

**Features**
* Progressive Web App (PWA) with offline support
* App-style layout at `/app/` route
* Customizable branding, colors, and icons
* WooCommerce integration (Shop, Cart, Account tabs)
* Automatic `/app/` page creation on activation
* Lightweight footprint (<15KB front-end)
* Compatible with all WordPress themes

== Installation ==

1. Download the latest release ZIP from the [GitHub Releases](https://github.com/your-username/mortify2026/releases) page.  
2. In your WordPress admin, go to **Plugins → Add New → Upload Plugin**.  
3. Upload the `mortify2026.zip` file and click **Activate Plugin**.  
4. Visit `/app/` on your site to see your app interface.  
5. (Optional) Configure app branding and PWA settings under **Settings → Mortify 2026**.

== Frequently Asked Questions ==

= Does it work with any theme? =
Yes — Mortify 2026 renders its app shell independently of your theme’s layout.

= Is this a single-page app (SPA)? =
No, it’s a hybrid. It uses WordPress templates and a service worker for offline caching.

= Can I publish it as a native app? =
Yes — PWAs can be installed on Android and iOS (via Safari “Add to Home Screen”).

= What about WooCommerce? =
If WooCommerce is active, Mortify 2026 automatically adds Shop, Cart, and Account tabs.

== Screenshots ==
1. App shell view on mobile
2. WooCommerce tab integration
3. Offline screen example

== Changelog ==

= 1.0.0 =
* Initial release
* PWA support (manifest, service worker)
* Offline fallback
* Bottom navigation tabs
* WooCommerce awareness

== Upgrade Notice ==
= 1.0.0 =
First public release of Mortify 2026.  
If upgrading from a previous dev build, re-activate the plugin to rebuild `/app/` routes.

== License ==
This plugin is licensed under the GPLv2 or later.  
Copyright © 2026 PulsR Labs.

