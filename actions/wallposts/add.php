<?php
/**
 * Elgg Wall Posts Add Action
 *
 * @package WallPosts
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2013
 * @link http://www.thinkglobalschool.com/
 *
 */

// Don't filter since we strip and filter escapes some characters
$body = get_input('body', '', false);

$access_id = get_input('access_id', ACCESS_LOGGED_IN);
$method = 'site';
$container_guid = (int) get_input('container_guid');

// Get container from guid
$container = get_entity($container_guid);

// Make sure container is an entity/group, or default to site
if (!elgg_instanceof($container, 'user') && !elgg_instanceof($container, 'group')) {
	$container = elgg_get_site_entity();
}

// Make sure the post isn't blank
if (empty($body)) {
	register_error(elgg_echo("wallposts:blank"));
	forward(REFERER);
}

// Create post
$guid = wallposts_create_post($body, elgg_get_logged_in_user_guid(), $access_id, $container->guid, $method);
if (!$guid) {
	register_error(elgg_echo("wallposts:error:create"));
	forward(REFERER);
}

// @todo notifications

// Send response to original poster if not already registered to receive notification
// if ($parent_guid) {
// 	thewire_send_response_notification($guid, $parent_guid, $user);
// 	$parent = get_entity($parent_guid);
// 	forward("thewire/thread/$parent->wire_thread");
// }

system_message(elgg_echo("wallposts:success:create"));
forward(REFERER);
