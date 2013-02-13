<?php
/**
 * Elgg Wall Posts Add View
 *
 * @package WallPosts
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2013
 * @link http://www.thinkglobalschool.com/
 *
 */

$form = elgg_view_form('wallposts/add', array(), $vars);

echo elgg_view_module('info', elgg_echo("wallposts:label:share"), $form);