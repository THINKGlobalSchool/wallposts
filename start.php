<?php
/**
 * Elgg Wall Posts
 *
 * @package WallPosts
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2013
 * @link http://www.thinkglobalschool.com/
 *
 * OVERRIDES:
 * 	- profile/tabs/activity
 *  - river/elements/responses  AJAXY!
 */

elgg_register_event_handler('init', 'system', 'wallposts_init');

// Init wall posts
function wallposts_init() {
	// Register library
	elgg_register_library('elgg:wallposts', elgg_get_plugins_path() . 'wallposts/lib/wallposts.php');
	elgg_load_library('elgg:wallposts');

	// Extend main CSS
	elgg_extend_view('css/elgg', 'css/wallposts/css');

	// Register JS Lib
	$js = elgg_get_simplecache_url('js', 'wallposts/wallposts');
	elgg_register_simplecache_view('js/wallposts/wallposts');
	elgg_register_js('elgg.wallposts', $js);
	elgg_load_js('elgg.wallposts');

	// Set up tabbed_profile content
	if (elgg_is_active_plugin('tabbed_profile')) {
		// Hook handler to add/remove tabs
		elgg_register_plugin_hook_handler('tabs', 'profile', 'wallposts_profile_tab_hander');
	
		// Extend activity tab
		elgg_extend_view('profile/tabs/activity', 'wallposts/posts');
	}

	// Allow users to post to other user's container (user wall posts)
	elgg_register_plugin_hook_handler('container_permissions_check', 'object', 'wallposts_container_permission_override');

	// Modify river menu for wallposts
	elgg_register_plugin_hook_handler('register', 'menu:river', 'wallposts_river_menu_setup');

	// Extend ajax page handler
	//elgg_register_plugin_hook_handler('route', 'ajax', 'wallposts_route_ajax_handler');

	// Hook into annotataion event
	elgg_register_event_handler('create', 'annotation', 'wallposts_annotation_create_handler');

	// Register actions
	$action_base = elgg_get_plugins_path() . 'wallposts/actions/wallposts';
	elgg_register_action("wallposts/add", "$action_base/add.php");
	elgg_register_action("wallposts/delete", "$action_base/delete.php");
	elgg_register_action("wallposts/comment", "$action_base/comment.php");

	// Whitelist ajax views
	elgg_register_ajax_view('wallposts/list');
	elgg_register_ajax_view('wallposts/activity');
	elgg_register_ajax_view('river/elements/responses');

	// Register for search.
	elgg_register_entity_type('object', 'wallpost');
}

/**
 * Modify the tabbed profile tabs
 *
 * @param string $hook
 * @param string $type
 * @param bool   $value
 * @param array  $params
 * @return mixed
 */
function wallposts_profile_tab_hander($hook, $type, $value, $params) {
	// Remove comment wall
	foreach ($value as $idx => $tab) {
		if ($tab == 'commentwall') {
			unset($value[$idx]);
		}
	}
	return $value;
}

/**
 * Override permissions user containers for wall posts
 *
 * @param string $hook
 * @param string $type
 * @param bool   $value
 * @param array  $params
 * @return mixed
 */
function wallposts_container_permission_override($hook, $type, $value, $params) {
	if ($params['subtype'] == 'wallpost'                        // We're making a wall post
		&& $params['container']->guid != $params['user']->guid  // User and container aren't the same
		&& elgg_instanceof($params['container'], 'user'))       // Container is a user
	{
		return TRUE; // Allow the post to another user's wall
	}
	return $value;
}


/**
 * Modify river menu items
 *
 * @param string $hook
 * @param string $type
 * @param bool   $value
 * @param array  $params
 * @return mixed
 */
function wallposts_river_menu_setup($hook, $type, $value, $params) {
	if (elgg_is_logged_in()) {
		$item = $params['item'];
		$object = $item->getObjectEntity();

		if ($item->view == 'river/object/wallpost/create' && $object->getSubtype() == 'wallpost' && $object->canEdit()) {
			$options = array(
				'name' => 'delete_wallpost',
				'href' => elgg_add_action_tokens_to_url("action/wallposts/delete?guid=$object->guid"),
				'text' => elgg_view_icon('delete'),
				'title' => elgg_echo('delete'),
				'confirm' => elgg_echo('deleteconfirm'),
				'priority' => 200,
			);
			$value[] = ElggMenuItem::factory($options);

			if (elgg_is_admin_logged_in()) {
				foreach ($value as $idx => $item) {
					if ($item->getName() == 'delete') {
						unset($value[$idx]);
					}
				}
			}
		}
	}

	return $value;
}


/**
 * Extend ajax page handler to prevent hitting view urls directly (experimental)
 *
 * @param string $hook
 * @param string $type
 * @param bool   $return
 * @param array  $params
 * @return mixed
 */
function wallposts_route_ajax_handler($hook, $type, $return, $params) {
	if (!elgg_is_xhr()) {
		forward('', 404);
	}

	return $return;
}
/**
 * Wall post annotate handler
 *
 * @param string $event  Event name
 * @param string $type   Object type
 * @param mixed  $object Object
 *
 * @return bool
 */
function wallposts_annotation_create_handler($event, $type, $object) {
	$entity = get_entity($object->entity_guid);

	if (elgg_instanceof($entity, 'object', 'wallpost')) {
		$user = elgg_get_logged_in_user_entity();

		// Send out commented annotation to owner
		if ($entity->owner_guid != $user->guid) {

			$owner = get_entity($entity->owner_guid);

			notify_user($entity->owner_guid,
				$user->guid,
				elgg_echo('generic_comment:email:subject'),
				elgg_echo('wallposts:generic_comment:email:body', array(
					$user->name,
					$object->value,
					$owner->getURL(),
					$user->name,
					$user->getURL()
				))
			);
		}

		// Also need to notify_user the user if posted to another users wall
		$container = $entity->getContainerEntity();

		if (elgg_instanceof($container, 'user') && ($container->guid != $user->guid && $container->guid != $owner->guid)) {
			notify_user($container->guid,
				$user->guid,
				elgg_echo('generic_comment:email:subject'),
				elgg_echo('wallposts:generic_comment:email:body', array(
					$user->name,
					$object->value,
					$container->getURL(),
					$user->name,
					$user->getURL()
				))
			);
		}

		// Seriously hacky.. there's no way to modify the contents of a generic_comment notification
		global $NOTIFICATION_HANDLERS;

		// Unregister all handlers to prevent a notifications going out for wall post comments
		foreach($NOTIFICATION_HANDLERS as $method => $handler) {
			unregister_notification_handler($method);
		}
	}

	return TRUE;
}

