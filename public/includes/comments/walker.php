<?php
/**
 * Comment API: Walker_Comment class
 *
 * @package Enlightenment_Framework
 * @subpackage Comments
 * @since 1.0.0
 */

/**
 * Core walker class used to create an HTML list of comments.
 *
 * @since 1.0.0
 *
 * @see Walker_Comment
 */
class Enlightenment_Walker_Comment extends Walker_Comment {

	/**
	 * Starts the list before the elements are added.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @see Walker_Comment::start_lvl()
	 * @global int $comment_depth
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int    $depth  Optional. Depth of the current comment. Default 0.
	 * @param array  $args   Optional. Uses 'style' argument for type of HTML list. Default empty array.
	 */
	function start_lvl( &$output, $depth = 0, $args = array() ) {
		$GLOBALS['comment_depth'] = $depth + 1;

		switch ( $args['style'] ) {
			case 'ol':
				$output .= '<ol class="children">' . "\n";
				break;
			case 'ul':
				$output .= '<ul class="children">' . "\n";
				break;
			case 'div':
			default:
				break;
		}
	}

	/**
	 * Ends the list of items after the elements are added.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @see Walker_Comment::end_lvl()
	 * @global int $comment_depth
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int    $depth  Optional. Depth of the current comment. Default 0.
	 * @param array  $args   Optional. Will only append content if style argument value is 'ol' or 'ul'.
	 *                       Default empty array.
	 */
	function end_lvl( &$output, $depth = 0, $args = array() ) {
		$GLOBALS['comment_depth'] = $depth + 1;

		switch ( $args['style'] ) {
			case 'ol':
				$output .= "</ol><!-- .children -->\n";
				break;
			case 'ul':
				$output .= "</ul><!-- .children -->\n";
				break;
			case 'div':
			default:
				break;
		}
	}

	/**
	 * Ends the element output, if needed.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @see Walker_Comment::end_el()
	 * @see wp_list_comments()
	 *
	 * @param string     $output  Used to append additional content. Passed by reference.
	 * @param WP_Comment $comment The current comment object. Default current comment.
	 * @param int        $depth   Optional. Depth of the current comment. Default 0.
	 * @param array      $args    Optional. An array of arguments. Default empty array.
	 */
	function end_el( &$output, $comment, $depth = 0, $args = array() ) {
		if ( ! empty( $args['end-callback'] ) ) {
			ob_start();
			call_user_func( $args['end-callback'], $comment, $args, $depth );
			$output .= ob_get_clean();
			return;
		}

		ob_start();
		do_action( 'enlightenment_after_comment_end_callback', $comment, $args );
		$output .= ob_get_clean();

		if( 'ul' == $args['style'] || 'ol' == $args['style'] ) {
			$output .= "</li><!-- #comment-## -->\n";
		} else {
			if( current_theme_supports( 'html5', 'comment-list' ) ) {
				$output .= "</article><!-- #comment-## -->\n";
			} else {
				$output .= "</div><!-- #comment-## -->\n";
			}
		}

		ob_start();
		do_action( 'enlightenment_after_comment', $comment, $args );
		$output .= ob_get_clean();
	}

}
