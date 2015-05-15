<?php
/**
 * River item footer
 *
 * @uses $vars['item'] ElggRiverItem
 * @uses $vars['responses'] Alternate override for this item
 */

// allow river views to override the response content
$responses = elgg_extract('responses', $vars, false);

$offset = elgg_extract('offset', $vars, 0);
$limit = elgg_extract('limit', $vars, 3);

if ($responses) {
	echo $responses;
	return true;
}

$item = $vars['item'];
/* @var ElggRiverItem $item */

if (!$vars['item']) {
	$entity_guid = get_input('entity_guid');
	$object = get_entity($entity_guid);
} else {
	$object = $item->getObjectEntity();	
}

// annotations and comments do not have responses
if ($item->annotation_id != 0 || !$object ) {
	return true;
}

$comment_count = $object->countComments();

if ($comment_count) {
	$comments = elgg_get_entities(array(
		'type' => 'object',
		'subtype' => 'comment',
		'container_guid' => $object->getGUID(),
		'limit' => $limit,
		'offset' => $offset,
		'order_by' => 'e.time_created desc',
		'distinct' => false,
	));

	// why is this reversing it? because we're asking for the 3 latest
	// comments by sorting desc and limiting by 3, but we want to display
	// these comments with the latest at the bottom.
	$comments = array_reverse($comments);

	echo elgg_view_entity_list($comments, array('list_class' => 'elgg-river-comments'));
	if ($comment_count > (count($comments) + $offset)) {
		$num_more_comments = $comment_count - (count($comments) + $offset);

		$next_offset = $limit + $offset;

		$url = elgg_get_site_url() . "ajax/view/river/elements/responses?entity_guid={$object->guid}&limit=5&offset={$next_offset}";

		$params = array(
			'href' => $url,
			'text' => elgg_echo('river:comments:more', array($num_more_comments)),
			'is_trusted' => true,
			'class' => '_wp-load-more-comments'
		);
		$link = elgg_view('output/url', $params);
		echo "<div class=\"elgg-river-more wallposts-river-more\">$link</div>";
	}
}

// inline comment form
$form_vars = array('id' => "comments-add-{$object->getGUID()}", 'class' => 'hidden');
$body_vars = array('entity' => $object, 'inline' => true);
echo elgg_view_form('comment/save', $form_vars, $body_vars);
