<?php
/**
 * Elgg Wall Posts Custom Responses
 *
 * @package WallPosts
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2013
 * @link http://www.thinkglobalschool.com/
 *
 * @uses $vars['item']        ElggRiverItem
 * @uses $vars['entity_guid'] Optional entity guid
 * @uses $vars['offset']      Optional offset
 * @uses $vars['limit']       Optional limit
 * @uses $vars['use_form']	  Optional include the comments form (default true)
 */



// allow river views to override the response content
$responses = elgg_extract('responses', $vars, false);
if ($responses) {
	echo $responses;
	return true;
}

$item = elgg_extract('item', $vars);
$offset = elgg_extract('offset', $vars, 0);
$limit = elgg_extract('limit', $vars, 3);
$use_form = elgg_extract('use_form', $vars, TRUE);

if (!$vars['item']) {
	$entity_guid = elgg_extract('entity_guid', $vars);
	$object = get_entity($entity_guid);
} else {
	$object = $item->getObjectEntity();	

	// annotations do not have comments
	if ($item->annotation_id != 0 || !$object) {
		return true;
	}
}

$comment_count = $object->countComments();

$options = array(
	'guid' => $object->getGUID(),
	'annotation_name' => 'generic_comment',
	'limit' => $limit,
	'offset' => $offset,
	'order_by' => 'n_table.time_created desc'
);

$comments = elgg_get_annotations($options);

if ($comments) {
	// why is this reversing it? because we're asking for the 3 latest
	// comments by sorting desc and limiting by 3, but we want to display
	// these comments with the latest at the bottom.
	$comments = array_reverse($comments);

?>
	<span class="elgg-river-comments-tab"><?php echo elgg_echo('comments'); ?></span>

<?php

	if ($comment_count > (count($comments) + $offset)) {
		$num_more_comments = $comment_count - (count($comments) + $offset);

		$next_offset = $limit + $offset;

		$href = elgg_get_site_url() . "ajax/view/river/elements/responses?entity_guid={$object->guid}&limit=5&offset={$next_offset}";

		$params = array(
			'href' => $href,
			'text' => elgg_echo('river:comments:wallposts:more', array($num_more_comments)),
			'is_trusted' => true,
			'class' => '_wp-load-more-comments'
		);
		$link = elgg_view('output/url', $params);
		echo "<div class=\"elgg-river-more wallposts-river-more\">$link</div>";
	}
	echo elgg_view_annotation_list($comments, array('list_class' => 'elgg-river-comments'));
}

if ($use_form) {
	// inline comment form
	$form_vars = array('id' => "comments-add-{$object->getGUID()}", 'class' => 'hidden');
	$body_vars = array('entity' => $object, 'inline' => true);
	echo elgg_view_form('comments/add', $form_vars, $body_vars);
}
