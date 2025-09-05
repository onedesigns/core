<?php

function enlightenment_the_loop( $args = null ) {
	$defaults = array(
		'container'            => is_singular() ? '' : 'div',
		'container_class'      => 'entries-list',
		'container_id'         => '',
		'container_extra_atts' => '',
		'entry_tag'            => 'article',
		'entry_class'          => '',
		'entry_id'             => 'post-%s',
		'entry_extra_atts'     => '',
		'header_tag'           => 'header',
		'header_class'         => 'entry-header',
		'echo'                 => true,
	);
	$defaults = apply_filters( 'enlightenment_the_loop_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

	if ( ! $args['echo'] ) {
		ob_start();
	}

	do_action( 'enlightenment_before_the_loop' );

	if ( have_posts() ) {
		global $enlightenment_post_counter;
		$enlightenment_post_counter = 0;

		do_action( 'enlightenment_before_entries_list' );

		echo enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'], $args['container_extra_atts'] );

		do_action( 'enlightenment_before_entries' );

		while ( have_posts() ) {
			the_post();

			$enlightenment_post_counter++;

			$slug = apply_filters( 'enlightenment_content_template_slug', 'content' );
			$name = apply_filters( 'enlightenment_content_template_name', get_post_format() );

			if ( '' != locate_template( array( '$slug-$name.php', '$slug.php' ), false, false ) ) {
				/*
				 * Include the Post-Format-specific template for the content.
				 * If you want to override this in a child theme, then include a
				 * file called content-__.php (where __ is the Post Format name)
				 * and that will be used instead.
				 */
				get_template_part( $slug, $name );
			} else {
				do_action( 'enlightenment_before_entry' );

				$entry_class = implode( ' ', get_post_class() ) . ' ' . $args['entry_class'];
				$entry_id    = sprintf( $args['entry_id'], get_the_ID() );

				echo enlightenment_open_tag( $args['entry_tag'], $entry_class, $entry_id, $args['entry_extra_atts'] );

				do_action( 'enlightenment_before_entry_header' );
				if ( has_action( 'enlightenment_entry_header' ) ) {
					echo enlightenment_open_tag( $args['header_tag'], $args['header_class'] );
					do_action( 'enlightenment_entry_header' );
					echo enlightenment_close_tag( $args['header_tag'] );
				}
				do_action( 'enlightenment_after_entry_header' );

				do_action( 'enlightenment_before_entry_content' );
				do_action( 'enlightenment_entry_content', '(more&hellip;)' );
				do_action( 'enlightenment_after_entry_content' );

				do_action( 'enlightenment_before_entry_footer' );
				do_action( 'enlightenment_entry_footer' );
				do_action( 'enlightenment_after_entry_footer' );

				echo enlightenment_close_tag( $args['entry_tag'] );

				do_action( 'enlightenment_after_entry' );
			}
		}

		do_action( 'enlightenment_after_entries' );

		echo enlightenment_close_tag( $args['container'] );

		do_action( 'enlightenment_after_entries_list' );

		unset( $GLOBALS['enlightenment_post_counter'] );
	} else {
		do_action( 'enlightenment_no_entries' );
	}

	do_action( 'enlightenment_after_the_loop' );

	if ( ! $args['echo'] ) {
		$output = ob_get_clean();

		return $output;
	}
}

function enlightenment_post_extra_atts( $args = null ) {
	$defaults = array(
		'atts' => '',
		'echo' => true,
	);

	// Backwards compatibility code, to be removed in a future version
	$the_loop_args = apply_filters( 'enlightenment_the_loop_args', array( 'entry_extra_atts' => '' ) );
	if ( ! empty( $the_loop_args['entry_extra_atts'] ) ) {
		$defaults['atts'] = $the_loop_args['entry_extra_atts'];
	}

	$defaults = apply_filters( 'enlightenment_post_extra_atts_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

	return enlightenment_extra_atts( $args['atts'], $args['echo'] );
}

function enlightenment_entry_title( $args = null ) {
	$defaults = array(
		'container'            => is_singular() ? 'h1' : 'h2',
		'container_class'      => 'entry-title',
		'container_id'         => '',
		'container_extra_atts' => '',
		'link_to_post'         => ! is_singular() || enlightenment_is_custom_loop(),
		'before'               => '',
		'after'                => '',
		'echo'                 => true,
	);
	$defaults = apply_filters( 'enlightenment_entry_title_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

	$before  = enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'], $args['container_extra_atts'] );
	if ( $args['link_to_post'] ) {
		$before .= sprintf(
			'<a href="%s" title="%s" rel="bookmark">',
			apply_filters( 'the_permalink', get_permalink() ),
			the_title_attribute( array( 'echo' => 0 ) )
		);
	}
	$before .= $args['before'];

	$after  = $args['after'];
	if ( $args['link_to_post'] ) {
		$after .= '</a>';
	}
	$after .= enlightenment_close_tag( $args['container'] );

	return the_title( $before, $after, $args['echo'] );
}

function enlightenment_entry_meta( $args = null ) {
	if ( in_array( get_post_type(), apply_filters( 'enlightenment_entry_meta_skipped_post_types', array( 'wp_router_page' ) ) ) ) {
		return;
	}

	$defaults = array(
		'container'       => 'div',
		'container_class' => 'entry-meta',
		'format'          => __( 'Posted on %1$s by %2$s', 'enlightenment' ),
		'echo'            => true,
	);
	$defaults = apply_filters( 'enlightenment_entry_meta_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

	$post_date          = enlightenment_entry_date( array( 'echo' => false ) );
	$post_author        = enlightenment_author_posts_link( array( 'echo' => false ) );
	$categories_list    = enlightenment_categories_list( array( 'echo' => false ) );
	$tags_list          = enlightenment_tags_list( array( 'echo' => false ) );
	$comments_link      = enlightenment_comments_link( array( 'echo' => false ) );
	$edit_post_link     = enlightenment_edit_post_link( array( 'echo' => false ) );
	$image_size         = enlightenment_meta_image_size( array( 'echo' => false ) );
	$author_avatar      = enlightenment_author_avatar( array( 'echo' => false ) );
	$author_description = apply_filters( 'the_author_description', get_the_author_meta( 'description' ), false );

	$meta = sprintf(
		$args['format'],
		$post_date,
		$post_author,
		$categories_list,
		$tags_list,
		$comments_link,
		$edit_post_link,
		$image_size,
		$author_avatar,
		$author_description
	);

	$output = '';

	if ( ! empty( $meta ) ) {
		$output .= enlightenment_open_tag( $args['container'], $args['container_class'] );
		$output .= $meta;
		$output .= enlightenment_close_tag( $args['container'] );
	}

	$output = apply_filters( 'enlightenment_entry_meta_output', $output, $args );

	if ( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_author_posts_link( $args = null ) {
	$defaults = array(
		'container'              => 'span',
		'container_class'        => 'entry-author',
		'author_container'       => 'span',
		'author_class'           => 'author vcard',
		'author_extra_atts'      => '',
		'before'                 => '',
		'after'                  => '',
		'format'                 => '%s',
		'author_link_extra_atts' => ' href="%1$s" title="%2$s" rel="author"',
		'echo'                   => true,
	);
	$defaults = apply_filters( 'enlightenment_author_posts_link_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

	$post_author  = enlightenment_open_tag( $args['author_container'], $args['author_class'], '', $args['author_extra_atts'] );
	$post_author .= enlightenment_open_tag(
		'a',
		'url fn n',
		'',
		sprintf(
			$args['author_link_extra_atts'],
			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
			esc_attr( sprintf( __( 'Posts by %s', 'enlightenment' ), get_the_author() ) )
		)
	);
	$post_author .= esc_html( get_the_author() );
	$post_author .= enlightenment_close_tag( 'a' );
	$post_author .= enlightenment_close_tag( $args['author_container'] );

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'] );
	$output .= sprintf( $args['format'], $post_author );
	$output .= enlightenment_close_tag( $args['container'] );

	$output = apply_filters( 'enlightenment_author_posts_link', $output, $args );

	if ( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_author_avatar( $args = null ) {
	$defaults = array(
		'container'       => 'span',
		'container_class' => 'author-avatar',
		'user_id'         => get_the_author_meta( 'ID' ),
		'link_to_archive' => true,
		'avatar_size'     => 48,
		'echo'            => true,
	);
	$defaults = apply_filters( 'enlightenment_author_avatar_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

	$output = '';

	if ( ! $args['user_id']  ) {
		return;
	}

	if ( ! $args['avatar_size']  ) {
		return;
	}

	$output .= enlightenment_open_tag( $args['container'], $args['container_class'] );

	if ( $args['link_to_archive']  ) {
		$output .= sprintf(
			'<a href="%1$s" title="%2$s" rel="author">',
			esc_url( get_author_posts_url( $args['user_id'] ) ),
			esc_attr( sprintf( __( 'Posts by %s', 'enlightenment' ), get_the_author_meta( 'display_name', $args['user_id'] ) ) )
		);
	}

	$output .= get_avatar( get_the_author_meta( 'user_email' ), $args['avatar_size'] );

	if ( $args['link_to_archive']  ) {
		$output .= '</a>';
	}

	$output .= enlightenment_close_tag( $args['container'] );

	$output = apply_filters( 'enlightenment_author_avatar', $output, $args );

	if ( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_author_social_links( $args = null ) {
	$defaults = array(
		'container'       => 'ul',
		'container_class' => 'author-social-links',
		'link_container'  => 'li',
		'link_class'      => 'author-social-link',
		'echo'            => true,
		'echo'            => true,
	);
	$defaults = apply_filters( 'enlightenment_author_social_links_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

	$social_links = enlightenment_get_author_social_links();

	if ( empty( $social_links ) ) {
		return false;
	}

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'] );

	foreach( $social_links as $service => $data ) {
		$class = sprintf( '%s author-social-link-%s', $args['link_class'], $service );

		$output .= enlightenment_open_tag( $args['link_container'], $class );
		$output .= sprintf( '<a href="%s">%s</a>', esc_url( $data['url'] ), $data['label'] );
		$output .= enlightenment_close_tag( $args['link_container'] );
	}

	$output .= enlightenment_close_tag( $args['container'] );

	$output  = apply_filters( 'enlightenment_author_social_links', $output, $args );

	if ( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_entry_date( $args = null ) {
	$defaults = array(
		'container'                 => 'span',
		'container_class'           => 'entry-date',
		'before'                    => '',
		'after'                     => '',
		'format'                    => '%s',
		'published_time_extra_atts' => '',
		'wrap_permalink'            => is_singular() ? false : true,
		'echo'                      => true,
	);
	$defaults = apply_filters( 'enlightenment_entry_date_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

	$time_string = '<time class="published updated" datetime="%1$s"%5$s>%2$s</time>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<time class="published" datetime="%1$s"%5$s>%2$s</time> <time class="updated" datetime="%3$s">%4$s</time>';
	}

	$time_string = sprintf(
		$time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( 'c' ) ),
		esc_html( get_the_modified_date() ),
		$args['published_time_extra_atts']
	);

	$post_date = $time_string;

	if ( $args['wrap_permalink'] ) {
		$post_date = sprintf( '<a href="%1$s" rel="bookmark">%2$s</a>', esc_url( get_permalink() ), $post_date );
	}

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'] );
	$output .= sprintf( $args['format'], $post_date );
	$output .= enlightenment_close_tag( $args['container'] );

	$output = apply_filters( 'enlightenment_entry_date', $output, $args );

	if ( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_categories_list( $args = null ) {
	$defaults = array(
		'container'       => 'span',
		'container_class' => 'entry-category',
		'before'          => '',
		'after'           => '',
		'format'          => '%s',
		'sep'             => ', ',
		'echo'            => true,
	);
	$defaults = apply_filters( 'enlightenment_categories_list_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

	if ( ! in_array( get_post_type(), get_taxonomy( 'category' )->object_type ) ) {
		return;
	}

	$categories_list = get_the_category_list( $args['sep'] );

	$output = '';
	if ( ! empty( $categories_list ) ) {
		$output .= enlightenment_open_tag( $args['container'], $args['container_class'] );
		$output .= $args['before'];
		$output .= sprintf( $args['format'], $categories_list );
		$output .= $args['after'];
		$output .= enlightenment_close_tag( $args['container'] );
	}
	$output = apply_filters( 'enlightenment_categories_list', $output, $args );

	if ( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_tags_list( $args = null ) {
	$defaults = array(
		'container'       => 'span',
		'container_class' => 'entry-tags',
		'before'          => '',
		'after'           => '',
		'format'          => '%s',
		'sep'             => ', ',
		'echo'            => true,
	);
	$defaults = apply_filters( 'enlightenment_tags_list_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

	if ( ! in_array( get_post_type(), get_taxonomy( 'post_tag' )->object_type ) ) {
		return;
	}

	$tags_list = get_the_tag_list( '', $args['sep'] );

	$output = '';
	if( ! empty( $tags_list ) ) {
		$output .= enlightenment_open_tag( $args['container'], $args['container_class'] );
		$output .= $args['before'];
		$output .= sprintf( $args['format'], $tags_list );
		$output .= $args['after'];
		$output .= enlightenment_close_tag( $args['container'] );
	}
	$output = apply_filters( 'enlightenment_tags_list', $output, $args );

	if ( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_terms_list( $args = null ) {
	$defaults = array(
		'container'       => 'span',
		'container_class' => 'entry-terms entry-%s-terms',
		'before'          => '',
		'after'           => '',
		'taxonomy'        => '',
		'format'          => '%s',
		'sep'             => ', ',
		'echo'            => true,
	);
	$defaults = apply_filters( 'enlightenment_terms_list_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

	if ( empty( $args['taxonomy'] ) ) {
		_doing_it_wrong( __FUNCTION__, __( 'Taxonomy parameter is required.', 'enlightenment' ), '2.0.0' );
		return;
	}

	$taxonomy = get_taxonomy( $args['taxonomy'] );
	if ( ! $taxonomy instanceof WP_Taxonomy) {
		return;
	}

	if ( ! in_array( get_post_type(), $taxonomy->object_type ) ) {
		return;
	}

	$args['container_class'] = sprintf( $args['container_class'], $args['taxonomy'] );

	$tags_list = get_the_term_list( get_the_ID(), $args['taxonomy'], '', $args['sep'], '' );

	$output = '';
	if ( ! empty( $tags_list ) ) {
		$output .= enlightenment_open_tag( $args['container'], $args['container_class'] );
		$output .= $args['before'];
		$output .= sprintf( $args['format'], $tags_list );
		$output .= $args['after'];
		$output .= enlightenment_close_tag( $args['container'] );
	}
	$output = apply_filters( 'enlightenment_terms_list', $output, $args );

	if ( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_meta_image_size( $args = null ) {
	$defaults = array(
		'container'       => 'span',
		'container_class' => 'entry-image-size',
		'before'          => '',
		'after'           => '',
		'format'          => __( 'Original image: %s pixels', 'enlightenment' ),
		'echo'            => true,
	);
	$defaults = apply_filters( 'enlightenment_meta_image_size_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

	if ( ! wp_attachment_is_image() ) {
		return '';
	}

	$metadata = wp_get_attachment_metadata();

	$link  = '<a href="' . wp_get_attachment_url() . '" title="' . __( 'Link to full-size image', 'enlightenment' ) . '">';
	$link .= $metadata['width'] . '&times;' . $metadata['height'];
	$link .= '</a>';

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'] );
	$output .= $args['before'];
	$output .= sprintf( $args['format'], $link );
	$output .= $args['after'];
	$output .= enlightenment_close_tag( $args['container'] );

	$output = apply_filters( 'enlightenment_meta_image_size', $output, $args );

	if ( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_comments_link( $args = null ) {
	$defaults = array(
		'container'       => 'span',
		'container_class' => 'entry-comments',
		'before'          => '',
		'after'           => '',
		'format'          => array(
			'zero' => __( 'no comments', 'enlightenment' ),
			'one'  => __( '1 Comment', 'enlightenment' ),
			'more' => __( '% Comments', 'enlightenment' ),
		),
		'echo'            => true,
	);
	$defaults = apply_filters( 'enlightenment_comments_link_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

	if ( post_password_required() || ! ( comments_open() || get_comments_number() ) ) {
		return;
	}

	list( $zero, $one, $more ) = array_values( $args['format'] );
	ob_start();
	comments_popup_link( $zero, $one, $more );
	$comments_link = ob_get_clean();

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'] );
	$output .= $args['before'];
	$output .= $comments_link;
	$output .= $args['after'];
	$output .= enlightenment_close_tag( $args['container'] );

	$output = apply_filters( 'enlightenment_comments_link', $output, $args );

	if ( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_edit_post_link( $args = null ) {
	$defaults = array(
		'container'       => 'span',
		'container_class' => 'entry-edit-link',
		'before'          => '',
		'after'           => '',
		'format'          => __( 'Edit', 'enlightenment' ),
		'echo'            => true,
	);
	$defaults = apply_filters( 'enlightenment_edit_post_link_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

	ob_start();
	edit_post_link(
		$args['format'],
		sprintf( '<%s class="%s">', esc_attr( $args['container'] ), esc_attr( $args['container_class'] ) ),
		sprintf( '</%s>', esc_attr( $args['container'] ) )
	);
	$output = ob_get_clean();

	$output = apply_filters( 'enlightenment_edit_post_link', $output, $args );

	if ( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

/**
 * Display the post content.
 *
 * @since 2.0.0
 *
 * @param array $args Optional. Filter function arguments.
 */
function enlightenment_post_content( $args = null ) {
	$defaults = array(
		'container'            => 'div',
		'container_class'      => 'entry-content',
		'container_id'         => '',
		'container_extra_atts' => '',
		'more_link_text'       => null,
		'strip_teaser'         => false,
		'echo'                 => true,
	);
	$defaults = apply_filters( 'enlightenment_post_content_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

	$content = get_the_content( $args['more_link_text'], $args['strip_teaser'] );

	/**
	 * Filters the post content.
	 *
	 * @since 0.71
	 *
	 * @param string $content Content of the current post.
	 */
	$content = apply_filters( 'the_content', $content );
	$content = str_replace( ']]>', ']]&gt;', $content );

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'], $args['container_extra_atts'] );
	$output .= $content . "\n";
	$output .= enlightenment_close_tag( $args['container'] );

	$output = apply_filters( 'enlightenment_post_content', $output, $args );

	if ( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

/**
 * Display the post excerpt.
 *
 * @since 2.0.0
 *
 * @param array $args Optional. Filter function arguments.
 */
function enlightenment_post_excerpt( $args = null ) {
	$defaults = array(
		'container'            => 'div',
		'container_class'      => 'entry-summary',
		'container_id'         => '',
		'container_extra_atts' => '',
		'echo'                 => true,
	);
	$defaults = apply_filters( 'enlightenment_post_excerpt_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

	/**
	 * Filters the displayed post excerpt.
	 *
	 * @since 0.71
	 *
	 * @see get_the_excerpt()
	 *
	 * @param string $post_excerpt The post excerpt.
	 */
	$excerpt = apply_filters( 'the_excerpt', get_the_excerpt() );

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'], $args['container_extra_atts'] );
	$output .= $excerpt . "\n";
	$output .= enlightenment_close_tag( $args['container'] );

	$output = apply_filters( 'enlightenment_post_excerpt', $output, $args );

	if ( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_author_hcard( $args = null ) {
	$defaults = array(
		'container'              => 'aside',
		'container_class'        => 'entry-author-info',
		'title_container'        => 'h4',
		'avatar_size'            => 96,
		'title_class'            => 'author vcard',
		'title_format'           => '%s',
		'author_name_container'  => 'span',
		'author_name_class'      => 'fn',
		'bio_container'          => 'div',
		'bio_class'              => 'author-bio',
		'social_links_container' => 'ul',
		'social_links_class'     => 'author-social-links',
		'social_link_container'  => 'li',
		'social_link_class'      => 'author-social-link',
		'echo'                   => true,
	);
	$defaults = apply_filters( 'enlightenment_author_hcard_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

	global $authordata;
	if ( ! is_object( $authordata ) ) {
		return false;
	}

	$author_bio   = apply_filters( 'the_author_description', get_the_author_meta( 'description' ), false );
	$social_links = enlightenment_author_social_links( array( 'echo' => false ) );

	if ( empty( $author_bio ) && empty( $social_links ) ) {
		return;
	}

	$avatar      = '';
	$name        = '';
	$title       = '';
	$description = '';

	if ( $args['avatar_size']  ) {
		$avatar = sprintf(
			'<a href="%1$s" title="%2$s" rel="author">%3$s</a>',
			esc_url( get_author_posts_url( $authordata->ID, $authordata->user_nicename ) ),
			esc_attr( sprintf( __( 'Posts by %s', 'enlightenment' ), get_the_author() ) ),
			get_avatar( get_the_author_meta( 'user_email' ), $args['avatar_size'] )
		);
		$avatar = apply_filters( 'enlightenment_author_hcard_avatar', $avatar, $args );
	}

	$name  = enlightenment_open_tag( $args['author_name_container'], $args['author_name_class'] );
	$name  = sprintf(
		'<a href="%1$s" title="%2$s" rel="author">%3$s</a>',
		esc_url( get_author_posts_url( $authordata->ID, $authordata->user_nicename ) ),
		esc_attr( sprintf( __( 'Posts by %s', 'enlightenment' ), get_the_author() ) ),
		get_the_author()
	);
	$name .= enlightenment_close_tag( $args['author_name_container'] );

	$title  = enlightenment_open_tag( $args['title_container'], $args['title_class'] );
	$title .= sprintf( $args['title_format'], $name );
	$title .= enlightenment_close_tag( $args['title_container'] );

	$description = '';
	if ( ! empty( $author_bio ) ) {
		$description .= enlightenment_open_tag( $args['bio_container'], $args['bio_class'] );
		$description .= $author_bio;
		$description .= enlightenment_close_tag( $args['bio_container'] );
	}
	$description  = apply_filters( 'enlightenment_author_bio', $description, $args );

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'] );
	$output .= $avatar;
	$output .= $title;
	$output .= $description;
	$output .= $social_links;
	$output .= enlightenment_close_tag( $args['container'] );

	$output = apply_filters( 'enlightenment_author_hcard', $output, $args );

	if ( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_no_posts_found( $args = null ) {
	$defaults = array(
		'container'       => 'div',
		'container_class' => 'page-content',
		'echo'            => true,
	);
	$defaults = apply_filters( 'enlightenment_no_posts_found_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

	$message = '';



	if ( is_home() && current_user_can( 'publish_posts' ) ) {
		$message = wpautop( sprintf(
			wp_kses(
				__( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'enlightenment' ),
				array(
					'a' => array(
						'href' => array(),
					),
				)
			),
			esc_url( admin_url( 'post-new.php' ) )
		) );
	} else {
		if ( is_home() ) {
			$message = esc_html__( 'There are no posts to display. Perhaps searching can help.', 'enlightenment' );
		} elseif ( is_post_type_archive() ) {
			$labels  = get_post_type_labels( get_queried_object() );
			$message = sprintf(
				esc_html__( 'There are no %s to display. Perhaps searching can help.', 'enlightenment' ),
				strtolower( $labels->name )
			);
		} elseif ( is_search() ) {
			$message = esc_html__( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'enlightenment' );
		} elseif ( is_author() ) {
			$message = sprintf(
				esc_html__( '%s hasn&rsquo;t written anything yet. Perhaps searching can help.', 'enlightenment' ),
				get_the_author_meta( 'display_name', get_queried_object()->ID )
			);
		} elseif ( is_category() || is_tag() || is_tax() ) {
			$queried_object = get_queried_object();
			$taxonomy       = get_taxonomy( $queried_object->taxonomy );
			$labels         = get_taxonomy_labels( $taxonomy );
			$message        = sprintf(
				esc_html__( 'The %1$s %2$s is empty. Perhaps searching can help.', 'enlightenment' ),
				strtolower( $labels->singular_name ),
				sprintf( '&ldquo;%s&rdquo;', $queried_object->name )
			);
		} else {
			$message = esc_html__( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'enlightenment' );
		}

		$message  = wpautop( $message );
		$message .= get_search_form( array( 'echo' => false ) );
	}

	$message = apply_filters( 'enlightenment_no_posts_found_message', $message, $args );
	$output  = '';

	if ( ! empty( $message ) ) {
		$output .= enlightenment_open_tag( $args['container'], $args['container_class'] );
		$output .= $message;
		$output .= enlightenment_close_tag( $args['container'] );
	}

	$output = apply_filters( 'enlightenment_no_posts_found', $output, $args );

	if ( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}
