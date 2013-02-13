<?php
/**
 * Elgg Wall Posts Helper Library
 *
 * @package WallPosts
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2013
 * @link http://www.thinkglobalschool.com/
 *
 */

/**
 * Get an array of hashtags from a text string
 * 
 * @param string $text The text of a post
 * @return array
 */
function wallposts_get_hashtags($text) {
	// beginning of text or white space followed by hashtag
	// hashtag must begin with # and contain at least one character not digit, space, or punctuation
	$matches = array();
	preg_match_all('/(^|[^\w])#(\w*[^\s\d!-\/:-@]+\w*)/', $text, $matches);
	return $matches[2];
}

/**
 * Replace urls, hash tags, and @'s with links
 * 
 * @param string $text The text of a post
 * @return string
 */
function wallposts_filter($text) {
	$text = ' ' . $text;

	// email addresses
	$text = preg_replace(
				'/(^|[^\w])([\w\-\.]+)@(([0-9a-z-]+\.)+[0-9a-z]{2,})/i',
				'$1<a href="mailto:$2@$3">$2@$3</a>',
				$text);

	// links
	$text = parse_urls($text);

	// usernames
	$text = preg_replace(
				'/(^|[^\w])@([\p{L}\p{Nd}._]+)/u',
				'$1<a href="' . elgg_get_site_url() . 'profile/$2">@$2</a>',
				$text);

	// hashtags
	$text = preg_replace(
				'/(^|[^\w])#(\w*[^\s\d!-\/:-@]+\w*)/',
				'$1<a href="' . elgg_get_site_url() . 'search?q=$2&search_type=tags&subtype=wallpost">#$2</a>',
				$text);

	$text = trim($text);

	return $text;
}

/**
 * Create a new wall post.
 *
 * @param string $text        The post text
 * @param int    $user_guid   The user's guid
 * @param int    $access_id   Public/private etc
 * @param int    $parent_guid Parent post guid (if any)
 * @param string $method      The method (default: 'site')
 * @return guid or false if failure
 */
function wallposts_create_post($text, $user_guid, $access_id, $container_guid, $method = "site") {
	$post = new ElggObject();

	$post->subtype = "wallpost";
	$post->owner_guid = $user_guid;
	$post->access_id = $access_id;
	$post->container_guid = $container_guid;

	// no html tags allowed so we escape
	$post->description = htmlspecialchars($text, ENT_NOQUOTES, 'UTF-8');

	$post->method = $method; //method: site, email, api, ...

	$tags = wallposts_get_hashtags($text);
	if ($tags) {
		$post->tags = $tags;
	}

	$guid = $post->save();

	if ($guid) {
		add_to_river('river/object/wallpost/create', 'create', $post->owner_guid, $post->guid);

		// @todo Which one is a status? site or posting to your own wall?
		// let other plugins know we are setting a user status
		// $params = array(
		// 	'entity' => $post,
		// 	'user' => $post->getOwnerEntity(),
		// 	'message' => $post->description,
		// 	'url' => $post->getURL(),
		// 	'origin' => 'thewire',
		// );
		// elgg_trigger_plugin_hook('status', 'user', $params);
	}
	
	return $guid;
}