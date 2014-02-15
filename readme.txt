=== WP Like System ===
Contributors: HenriqueSilverio
Tags: like, dislike, rating, post
Requires at least: 3.0
Tested up to: 3.8
Stable tag: 1.1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Rating system for posts, based on Facebook likes. Its not dependent of Facebook.

== Description ==

A rating system for WordPress posts, based on Facebook likes. Allow users like (and undo) your blog posts.

The users likes are saved directly on your database, like a WordPress native component. So, users can give you a feed back without a Facebook account.

= Easy to use =

No comes with a bunch of unnecessary settings. Simply install, paste the plugin snippet in your theme loop and its works as expected.

= Easy to customize =

WP Like System comes with a clean HTML markup and some CSS class, making easy for replace default styles to adjust better in your theme whenever necessary.

= Ready to translations =

Currently, this plugin has been translated in the following languages:

* English US and Brazilian Portuguese - by [HenriqueSilverio](https://github.com/HenriqueSilverio)
* Germany DE - by [patrickhempel](https://github.com/patrickhempel)

Feel free to contact me if you wish translate he in your language.

Download the files, translate and send a pull request on [Github repository](https://github.com/HenriqueSilverio/wp-like-system) or mail me: <contato@henriquesilverio.com>.

== Installation ==

* Upload plugin files to your plugins folder, or install using WordPress built-in Add New Plugin installer;
* Activate the plugin;

= Display likes directly =

Use this function in your theme loop:

	<?php
		if( function_exists( 'has_wpls_show_likes' ) ) {
			has_wpls_show_likes( get_the_ID() );
		}
	?>

== Frequently Asked Questions ==

= What is the plugin license? =

* This plugin is released under a GPL license.

== Screenshots ==

1. Front-End display 01.
2. Front-End display 02.
3. Front-End display 03.

== Changelog ==

= 1.1.0 =

* Added Germany language.

= 1.0.0 =

* Initial release.

== License ==

WP Like System is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

WP Like System is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

Get a copy of the GNU General Public License in <http://www.gnu.org/licenses/>.
