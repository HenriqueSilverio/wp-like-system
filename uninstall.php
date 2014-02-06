<?php
/**
* Fired when the plugin is uninstalled.
*
* @package WP Like System
* @author Henrique Silvério <contato@henriquesilverio.com>
* @license GPL-2.0+
* @link http://blog.henriquesilverio.com
* @copyright 2014 Henrique Silvério
*/

// If uninstall not called from WordPress, then exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// Delete all the plugin meta values
delete_post_meta_by_key( 'voted_IP' );
delete_post_meta_by_key( 'votes_count' );
