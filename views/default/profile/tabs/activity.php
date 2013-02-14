<?php
/**
 * Elgg Wall Posts Activity Tab
 *
 * @package WallPosts
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2013
 * @link http://www.thinkglobalschool.com/
 *
 * @uses $vars['entity'] The user entity
 */

//elgg_view('profile/status', array("entity" => $vars['entity']));

elgg_load_js('elgg.wallposts');

$page_owner = elgg_get_page_owner_entity();

// Show wall post for for logged in users
if (elgg_is_logged_in()) {
	$posts_content = elgg_view('wallposts/add', array('container_guid' => $page_owner->guid));	
}

$posts_content .= elgg_view('wallposts/list', array('owner_guid' => $page_owner->guid));

echo elgg_view_module('featured', elgg_echo('wallposts:label:posts', array($page_owner->name)), $posts_content);

$river_content = elgg_view('wallposts/activity', array('user_guid' => $page_owner->guid));

echo elgg_view_module('featured', elgg_echo('wallposts:label:activity', array($page_owner->name)), $river_content);