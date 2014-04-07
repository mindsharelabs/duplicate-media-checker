=== Duplicate Media Checker ===
Contributors: sekatsim, mindshare
Donate link: http://mind.sh/are/donate/
Tags: media, media library, upload, uploads, attachments, duplicate, attachment, organize, clean
Requires at least: 3.4.1
Tested up to: 3.8.1
Stable tag: 0.1

Keep your media library tidy by preventing uploads of new files if the file already exists.

== Description ==

When a media file is uploaded to the Media Library, Duplicate Media Checker will check to see if the file already exists. If it exists, DMC will prompt the user to either use the existing file, or create a new copy.

<h4>Features</h4>

* Configurable default actions for duplicate files
* Supports scanning all existing media and removing duplicate files.

== Installation ==

1. Upload the `wp-ultimate-search` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Add a shortcode to a post, use the template tag in your theme, or use the sidebar widget.

To use the shortcode:
Place `[wp-ultimate-search-bar]` where you'd like the search bar, and `[wp-ultimate-search-results]` where you'd like the results.

To use the template tag:
Put `wp_ultimate_search_bar()` where you'd like the search bar, and `wp_ultimate_search_results()` where you'd like the results.

For additional information, [visit our website](http://mindsharelabs.com/)

== Frequently Asked Questions ==

= How do I customize the search results template? =

When a search is executed, the plugin first looks in your current theme directory for the file wpus-results-template.php. If no file is found, it falls back to the default results template, located in /wp-ultimate-search/views/wpus-results-template.php.


== Screenshots ==

1. Search bar with results.

`/assets/screenshot-1.jpg`


== Changelog ==

= 0.1 =
* First public release
