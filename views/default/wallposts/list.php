<?php
/**
 * Elgg Wall Posts List View
 *
 * @package WallPosts
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2013
 * @link http://www.thinkglobalschool.com/
 *
 * @uses $vars['owner_guid'] Wall Posts Owner
 */

$owner_guid = elgg_extract('owner_guid', $vars, elgg_get_logged_in_user_guid());

$owner = get_entity($owner_guid);

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
		"(e1.container_guid = {$owner_guid})",
		"(rv.view = 'river/object/wallpost/create')"
	),
	'base_url' => elgg_get_site_url() . 'ajax/view/wallposts/list?owner_guid=' . $owner_guid,
);

$posts_content .= elgg_list_river($params);

if (!$posts_content) {
	$posts_content = elgg_echo('wallposts:none', array($owner->name));
}

echo "<div class='_wp-ajax-container' id='_wp-posts-content'>{$posts_content}</div>"; 