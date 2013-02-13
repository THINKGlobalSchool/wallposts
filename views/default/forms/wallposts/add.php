<?php
/**
 * Elgg Wall Posts Add Form
 *
 * @package WallPosts
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2013
 * @link http://www.thinkglobalschool.com/
 *
 */

elgg_load_js('elgg.wallposts');

// Sort out group access
if ($group = elgg_extract('group', $vars) && elgg_instanceof($group, 'group')) {	
	$access_input = elgg_view('input/hidden', array(
		'name' => 'access_id', 
		'value' => $group->group_acl,
	));
	$container_guid = $group->guid;
} else {
	$access_input = "<label>" . elgg_echo("wallposts:for") . "</label>";
	$access_input .= elgg_view('input/access', array(
		'name' => 'access_id', 
		'value' => (int)get_default_access(),
		'style' => 'float: none;',
	));
	$container_guid = elgg_extract('container_guid', $vars, elgg_get_site_entity()->guid);
}

$tips_label = elgg_echo("wallposts:label:tips");

$container_hidden = elgg_view('input/hidden', array(
	'name' => 'container_guid',
	'value' => $container_guid,
));

$body_input = elgg_view('input/plaintext', array(
	'class' => 'wallpost-body mtm',
	'name' => 'body',
));

$post_input = elgg_view('input/submit', array(
	'value' => elgg_echo('wallposts:post'),
	'id' => 'wallposts-submit-button',
));

$content = <<<HTML
	$body_input
	<div class='mts'>
		$container_hidden
		<div class='elgg-subtext'>$tips_label</div><br />
		$access_input
		$post_input
	</div>
HTML;

echo $content;