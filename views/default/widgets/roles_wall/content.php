<?php
/**
 * Elgg Wall Posts Profile Widget
 *
 * @package WallPosts
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2014
 * @link http://www.thinkglobalschool.com/
 *
 * @uses $vars['user_guid']
 */

$page_owner = elgg_get_page_owner_entity();

// Show wall post for logged in users
if (elgg_is_logged_in()) {
	$posts_content = elgg_view('wallposts/add', array('container_guid' => $page_owner->guid));	
}

$posts_content .= elgg_view('wallposts/list', array('owner_guid' => $page_owner->guid));

echo $posts_content;