<?php
/**
 * Elgg Wall Posts English Language Translation
 *
 * @package WallPosts
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2013
 * @link http://www.thinkglobalschool.com/
 *
 */

$english = array(
	// General/Entity
	'wallposts' => 'Wall Posts',
	'wallposts:post' => 'Post',
	'wallposts:for' => "For: ",
	'wallposts:their' => "their own",
	'item:object:wallposts' => 'Wall Posts',

	// Page titles 

	// Labels
	'wallposts:label:share' => 'Something to say? Something to share? Go for it. WALL POSTS', // @todo
	'wallposts:label:tips' => 'Tip: Tag posts by putting a # in front of a word.',
	'wallposts:label:activity' => 'Activity',
	'wallposts:label:posts' => 'Wall Posts',
	'wallposts:label:owneractivity' => '%s\'s Activity',
	'wallposts:label:ownerposts' => '%s\'s Wall Posts',

	// Notifications

	// Messages
	'wallposts:success:create' => 'Successfully posted to %s\'s wall',
	'wallposts:success:delete' => 'Successfully deleted wall post',

	'wallposts:blank' => 'Sorry, you need to enter some text before we can post this',
	'wallposts:none' => 'None',
	'wallposts:error:create' => 'There was an error creating the wall post',
	'wallposts:error:delete' => 'There was an error deleting the wall post',


	// River
	'river:comment:object:wallpost' => '%s commented on a wall post',
	'river:create:object:wallpost' => "%s posted to %s wall",
);

add_translation('en',$english);
