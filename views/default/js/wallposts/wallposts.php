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
	// Make activity modules pagination load via ajax
	$(document).delegate('._wp-ajax-container .elgg-pagination a','click', function(event) {
		$container = $(this).closest('._wp-ajax-container');

		var height = $container.height();

		$container.html("<div style='height: " + height + "px' class='elgg-ajax-loader'></div>").css({
			'height': height,
		}).load($(this).attr('href'), function() {
			$(this).css({'height':'auto'});
		});

		event.stopPropagation(); // Don't propagate the click event.. this messes with popups, etc
		event.preventDefault();
	});

	// Load more comments links
	$(document).delegate('a._wp-load-more-comments', 'click', function(event) {
		var $_this = $(this);
		// Get more comments
		elgg.get($(this).attr('href'), {
			data: {

			},
			success: function(data) {
				// Objectify data
				var $data = $(data);

				// Find responses element
				var $responses = $_this.closest('.elgg-river-responses');

				// Extract new comments
				var $new_comments = $($data.filter('ul.elgg-river-comments').html());

				// Append to comment list
				$new_comments.prependTo($responses.find('.elgg-river-comments')).hide().slideDown();

				// Find more link and replace it with new one (if any)
				$responses.find('.wallposts-river-more').replaceWith($data.filter('.wallposts-river-more'));
			}, 
			error: function(xhr, ajaxOptions, thrownError) {
				console.log(xrh.status);
				console.log(thrownError);
			}
		});

		event.preventDefault();
	});

	// Delegate a click event for river comment icon
	$(document).delegate('.elgg-menu-river li.elgg-menu-item-comment a', 'click', function(event) {
		// If we've activated the comments
		if ($(this).hasClass('elgg-state-active')) {
			// Find the comment input
			var $comment_input = $(this).closest('.elgg-river-item').find('.elgg-river-responses input[name=generic_comment]');

			// Animate scrolling to the input
			$('html, body').animate({scrollTop: $comment_input.offset().top - 150}, 500);

			// Focus the input
			$comment_input.focus();
		}
	});

	// Delegate a submit handler for river comment submit
	$(document).delegate('.elgg-river-responses form.elgg-form-comments-add', 'submit', function(event) {
		var $_this = $(this);

		var $submit = $(this).find('input[type=submit]');

		$submit.attr('disabled', 'DISABLED');

		elgg.action('wallposts/comment', {
			data: $(this).serialize(),
			success: function(data) {
				if (data.status == 0) {
					// If this was the first comment, we'll have the whole responses view
					if (data.output.river_first_comment) {
						var $responses = $(data.output.river_first_comment);
						$responses.prependTo($_this.parent()).hide().slideDown();
					} 

					// This was a new comment (with existing comments) append it to the list
					if (data.output.river_new_comment) {
						var $river_comment = $(data.output.river_new_comment);
						var $new_comment = $($river_comment.filter('ul.elgg-river-comments').html());
						$new_comment.appendTo($_this.parent().find('ul.elgg-river-comments')).hide().slideDown();
					}

					$_this.find('input[name=generic_comment]').val('');
				}

				$submit.removeAttr('disabled');
			}
		});
		event.preventDefault();
	});
}

elgg.register_hook_handler('init', 'system', elgg.wallposts.init);