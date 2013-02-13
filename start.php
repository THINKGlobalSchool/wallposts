<?php
/**
 * Elgg Wall Posts
 *
 * @package WallPosts
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2013
 * @link http://www.thinkglobalschool.com/
 *
 */

elgg_register_event_handler('init', 'system', 'wallposts_init');

// Init wall posts
function wallposts_init() {
	// Register library
	elgg_register_library('elgg:wallposts', elgg_get_plugins_path() . 'wallposts/lib/wallposts.php');

	// Extend main CSS
	elgg_extend_view('css/elgg', 'css/wallposts/css');

	// Register JS Lib
	$js = elgg_get_simplecache_url('js', 'wallposts/wallposts');
	elgg_register_simplecache_view('js/wallposts/wallposts');
	elgg_register_js('elgg.wallposts', $js);
	//elgg_load_js('elgg.wallposts');
}