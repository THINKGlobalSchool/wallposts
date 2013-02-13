<?php
/**
 * Elgg Wall Posts Delete Action
 *
 * @package WallPosts
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2013
 * @link http://www.thinkglobalschool.com/
 *
 */

// Get input data
$guid = (int) get_input('guid');

// Make sure we actually have permission to edit
$post = get_entity($guid);
if ($post->getSubtype() == "wallpost" && $post->canEdit()) {
	// Delete it
	if ($post->delete()) {
		// Success message
		system_message(elgg_echo("wallposts:success:deleted"));
	} else {
		register_error(elgg_echo("wallposts:error:deleted"));
	}
	forward(REFERER);
}
