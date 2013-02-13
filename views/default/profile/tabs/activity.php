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

$page_owner = elgg_get_page_owner_entity();

// Show wall post for for logged in users
if (elgg_is_logged_in()) {
	$posts_content = elgg_view('wallposts/add', array('container_guid' => $page_owner->guid));	
}

$db_prefix = elgg_get_config('dbprefix');

// Get the user's wall posts
$params = array(
	'limit' => 5,
	'type' => 'object',
	'subtype' => 'wallpost',
	'joins' => array(
		"JOIN {$db_prefix}entities e1 ON e1.guid = rv.object_guid",
	),
	'wheres' => array(
		"(e1.container_guid = {$page_owner->guid})",
		"(rv.view = 'river/object/wallpost/create')"
	),
);

$posts_content .= elgg_list_river($params);

if (!$posts_content) {
	$posts_content = elgg_echo('wallposts:none');
}


echo elgg_view_module('featured', elgg_echo('wallposts:label:posts', array($page_owner->name)), $posts_content);


$params = array(
	'subject_guid' => $page_owner->guid,
	'limit' => 5,
);
$river_activity = elgg_list_river($params);

echo elgg_view_module('featured', elgg_echo('wallposts:label:activity', array($page_owner->name)), $river_activity);