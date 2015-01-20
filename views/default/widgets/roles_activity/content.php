<?php
/**
 * Elgg Wall Posts Activity Widget
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

$river_content = elgg_view('wallposts/activity', array('user_guid' => $page_owner->guid));

echo $river_content;