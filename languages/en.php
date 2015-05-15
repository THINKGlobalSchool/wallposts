<?php
/**
 * Elgg Wall Posts English Language Translation
 *
 * @package WallPosts
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2015
 * @link http://www.thinkglobalschool.org/
 *
 */

return array(
	// General/Entity
	'item:object:wallpost' => 'Wall Posts',
	'wallposts' => 'Wall Posts',
	'wallposts:post' => 'Post',
	'wallposts:for' => "For: ",
	'wallposts:their' => "their own",
	'item:object:wallposts' => 'Wall Posts',
	'wallposts:wall' => 'wall',

	// Page titles 

	// Labels
	'wallposts:label:share' => 'Post to %s\'s wall', // @todo
	'wallposts:label:tips' => 'Tip: Tag posts by putting a # in front of a word.',
	'wallposts:label:activity' => 'Activity',
	'wallposts:label:posts' => 'Wall Posts',
	'wallposts:label:owneractivity' => '%s\'s Activity',
	'wallposts:label:ownerposts' => '%s\'s Wall Posts',

	// Notifications
	'wallposts:generic_comment:email:body' => "You have a new wall comment from %s. It reads:

%s


To view your wall, click here:

%s

To view %s's profile, click here:

%s

You cannot reply to this email.",

	'wallposts:create:email:subject' => "You have a new wall post!",

	'wallposts:create:email:body' => "You have a new wall post from %s. It reads:

%s


To view your wall, click here:

%s

To view %s's profile, click here:

%s

You cannot reply to this email.",

	'mentions:notification_types:object:wallpost' => 'a wall post',

	// Messages
	'wallposts:success:create' => 'Successfully posted to %s\'s wall',
	'wallposts:success:delete' => 'Successfully deleted wall post',

	'wallposts:blank' => 'Sorry, you need to enter some text before we can post this',
	'wallposts:none' => 'No one has posted on %s\'s wall',
	'wallposts:error:create' => 'There was an error creating the wall post',
	'wallposts:error:delete' => 'There was an error deleting the wall post',


	// River
	'river:comment:object:wallpost' => '%s commented on a wall post',
	'river:create:object:wallpost' => "%s posted to %s",
	'river:comments:wallposts:more' => "+%s more comments",

	// Widgets
	'wallposts:widget:wall' => 'Wall Post',
	'wallposts:widget:activity' => 'Activity',
);
