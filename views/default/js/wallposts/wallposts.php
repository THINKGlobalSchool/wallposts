<?php
/**
 * Elgg Wall Posts JS Library
 *
 * @package WallPosts
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2013
 * @link http://www.thinkglobalschool.com/
 *
 */
?>
//<script>
elgg.provide('elgg.wallposts');

// Init
elgg.wallposts.init = function() {

}

elgg.register_hook_handler('init', 'system', elgg.wallposts.init);