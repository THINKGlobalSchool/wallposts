<?php
/**
 * Elgg Wall Posts Activity List View
 *
 * @package WallPosts
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2013
 * @link http://www.thinkglobalschool.com/
 *
 * @uses $vars['user_guid']
 */

$user_guid = elgg_extract('user_guid', $vars, elgg_get_logged_in_user_guid());

$params = array(
	'subject_guid' => $user_guid,
	'limit' => 5,
	'base_url' => elgg_get_site_url() . 'ajax/view/wallposts/activity?user_guid=' . $user_guid,
);
$river_content = elgg_list_river($params);

echo "<div class='_wp-ajax-container' id='_wp-river-content'>{$river_content}</div>"; 