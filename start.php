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
	//elgg_load_js('elgg.wallposts');

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

	// Register actions
	$action_base = elgg_get_plugins_path() . 'wallposts/actions/wallposts';
	elgg_register_action("wallposts/add", "$action_base/add.php");
	elgg_register_action("wallposts/delete", "$action_base/delete.php");

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
			//unset($value[$idx]); // @todo disable
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
