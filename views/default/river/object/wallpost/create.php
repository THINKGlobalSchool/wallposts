<?php
/**
 * Elgg Wall Post River View
 *
 * @package WallPosts
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2013
 * @link http://www.thinkglobalschool.com/
 *
 */

$object = $vars['item']->getObjectEntity();

$excerpt = strip_tags($object->description);
$excerpt = wallposts_filter($excerpt);

$subject = $vars['item']->getSubjectEntity();
$subject_link = elgg_view('output/url', array(
	'href' => $subject->getURL(),
	'text' => $subject->name,
	'class' => 'elgg-river-subject',
	'is_trusted' => true,
));

$container = $object->getContainerEntity();

if ($subject->guid == $object->container_guid) {
	$wall_text = elgg_echo('wallposts:their');
} else {
	$wall_text = $container->name . "'s";
}

$wall_text = elgg_view('output/url', array(
	'href' => $container->getURL(),
	'text' => $wall_text . "&nbsp;" . elgg_echo('wallposts:wall'),
	'class' => 'elgg-river-object',
	'is_trusted' => true,
));

$summary = elgg_echo("river:create:object:wallpost", array($subject_link, $wall_text));

echo elgg_view('river/elements/layout', array(
	'item' => $vars['item'],
	'message' => $excerpt,
	'summary' => $summary,
));