<?php

class Enlightenment_Custom_Query extends WP_Widget {

	/**
	 * Default instance.
	**/
	protected $default_instance = array();

	/**
	 * Register widget with WordPress.
	**/
	public function __construct() {
		$this->default_instance = array(
			'title'                 => '',
			'tagline'               => '',
			'type'                  => 'list',
			'grid'                  => enlightenment_default_grid(),
			'query'                 => 'sticky_posts',
			'post_type'             => 'post',
			'p'                     => null,
			'page_id'               => null,
			'pages'                 => array(),
			'images'                => array(),
			'author'                => null,
			'taxonomy'              => 'category',
			'term'                  => null,
			'posts_per_page'        => 5,
			'leading_posts'         => 0,
			'link_to_archive'       => false,
			'link_to_archive_label' => __( 'See all posts &rarr;', 'enlightenment' ),
		);

		parent::__construct(
			'enlightenment-custom-query', // Base ID
			__( 'Custom Query', 'enlightenment' ), // Name
			array(
				'description'           => __( 'Display a custom query of content', 'enlightenment' ),
				'show_instance_in_rest' => true,
			) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	**/
	public function widget( $args, $instance ) {
		global $enlightenment_custom_widget_instance, $enlightenment_custom_lead_posts, $enlightenment_custom_grid;

		$instance = wp_parse_args( $instance, $this->default_instance );

		if (
			'carousel' == $instance['type']
			&&
			( 'post_type' == $instance['query'] || 'page' == $instance['query'] )
		) {
			$instance['type'] = 'list';
		}

		$enlightenment_custom_widget_instance = $instance;
		$enlightenment_custom_lead_posts      = $instance['leading_posts'];
		$enlightenment_custom_grid            = $instance['grid'];

		$title   = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );
		$tagline = apply_filters( 'enlightenment_custom_query_widget_tagline', $instance['tagline'] );
		$query   = array();

		switch ( $instance['query'] ) {
			case 'sticky_posts' :
				$sticky_posts = get_option( 'sticky_posts' );

				if ( empty( $sticky_posts ) ) {
					return;
				}

				$query['post__in'] = $sticky_posts;

				break;

			case 'post_type_archive' :
				$query['post_type']      = $instance['post_type'];
				$query['posts_per_page'] = $instance['posts_per_page'];

				break;

			case 'post_type' :
				$query['post_type'] = $instance['post_type'];
				$query['p']         = $instance['p'];

				break;

			case 'page' :
				$query['post_type'] = 'page';
				$query['page_id']   = $instance['page_id'];

				break;

			case 'pages' :
				if ( empty( $instance['pages'] ) ) {
					return;
				}

				$query['post_type']      = 'page';
				$query['post__in']       = $instance['pages'];
				$query['posts_per_page'] = -1;

				break;

			case 'gallery' :
				if ( empty( $instance['images'] ) ) {
					return;
				}

				$query['post_type']      = 'attachment';
				$query['post_mime_type'] = 'image';
				$query['post__in']       = $instance['images'];
				$query['post_status']    = 'inherit';
				$query['posts_per_page'] = -1;

				break;

			case 'author' :
				$query['author']         = $instance['author'];
				$query['posts_per_page'] = $instance['posts_per_page'];

				break;

			case 'taxonomy' :
				if ( 'category' == $instance['taxonomy'] ) {
					$instance['taxonomy'] = 'category_name';
				} elseif ( 'post_tag' == $instance['taxonomy'] ) {
					$instance['taxonomy'] = 'tag';
				}

				$query[ $instance['taxonomy'] ] = $instance['term'];
				$query['posts_per_page']        = $instance['posts_per_page'];

				break;
		}

		$query['ignore_sticky_posts'] = true;

		$loop_args = array(
			'query_name'  => 'custom_query_widget_' . $instance['type'],
			'query_args'  => $query,
			'entry_class' => 'custom-entry',
		);

		if ( 'slider' == $instance['type'] || 'carousel' == $instance['type'] ) {
			$loop_args['container']        = 'ul';
			$loop_args['container_class']  = 'slides';
			$loop_args['entry_tag']        = 'li';
			$loop_args['entry_class']     .= ' slide';
		}

		echo $args['before_widget'];

		if ( ! empty( $title ) ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}

		if ( ! empty( $tagline ) ) {
			echo enlightenment_open_tag( 'div', 'tagline' ) . wpautop( $tagline ) . enlightenment_close_tag( 'div' );
		}

		$class = sprintf(
			'custom-query-has-type-%s custom-query-has-query-%s%s',
			$instance['type'],
			$instance['query'],
			( 'slider' == $instance['type'] || 'carousel' == $instance['type'] ? ' flexslider' : '' )
		);

		if (
			current_theme_supports( 'enlightenment-grid-loop' )
			&&
			'slider' != $instance['type']
			&&
			( 'post_type' != $instance['query'] && 'page' != $instance['query'] )
		) {
			if ( current_theme_supports( 'enlightenment-bootstrap' ) ) {
				$breakpoints = enlightenment_bootstrap_breakpoints();
				$prefixes    = enlightenment_bootstrap_breakpoint_prefixes();

				foreach ( $breakpoints as $breakpoint => $label ) {
					if ( 'inherit' == $instance['grid'][ $breakpoint ] ) {
						continue;
					}

					$class .= sprintf( ' custom-query-has-grid%s-%s', $prefixes[ $breakpoint ], $instance['grid'][ $breakpoint ] );
				}
			} else {
				$class .= sprintf( ' custom-query-has-grid-%s', $instance['grid'] );
			}
		}

		echo enlightenment_open_tag( 'div', $class );

		echo enlightenment_custom_loop( $loop_args );

		echo enlightenment_close_tag( 'div' );

		unset( $GLOBALS['enlightenment_custom_widget_instance'] );
		unset( $GLOBALS['enlightenment_custom_lead_posts'] );
		unset( $GLOBALS['enlightenment_custom_grid'] );

		if ( $instance['link_to_archive'] ) {
			if ( 'post_type_archive' == $instance['query'] ) {
				if ( 'post' == $instance['post_type'] ) {
					if ( 'posts' == get_option( 'show_on_front' ) ) {
						$link = home_url( '/' );
					} else {
						$link = get_permalink( get_option( 'page_for_posts' ) );
					}
				} else {
					$link = get_post_type_archive_link( $instance['post_type'] );
				}
			} elseif ( 'author' == $instance['query'] ) {
				$link = get_author_posts_url( $instance['author'] );
			} elseif ( 'taxonomy' == $instance['query'] ) {
				if ( 'category_name' == $instance['taxonomy'] ) {
					$instance['taxonomy'] = 'category';
				} elseif ( 'tag' == $instance['taxonomy'] ) {
					$instance['taxonomy'] = 'post_tag';
				}

				$link = get_term_link( $instance['term'], $instance['taxonomy'] );
			}

			if ( isset( $link ) && is_string( $link ) ) {
				echo enlightenment_open_tag( 'div', 'custom-archive-permalink-wrap' );
				printf( '<a class="custom-archive-permalink" href="%s">%s</a>', esc_url( $link ), esc_attr( $instance['link_to_archive_label'] ) );
				echo enlightenment_close_tag( 'div' );
			}
		}

		echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		$instance = wp_parse_args( $instance, $this->default_instance );

		if (
			'carousel' == $instance['type']
			&&
			( 'post_type' == $instance['query'] || 'page' == $instance['query'] )
		) {
			$instance['type'] = 'list';
		}

		echo enlightenment_open_tag( 'div', 'enlightenment-custom-query-widget' );

		if ( ! function_exists( 'enlightenment_input' ) ) {
			require_once( enlightenment_general_directory() . '/form-controls.php' );
		}

		echo enlightenment_open_tag( 'p' );
		enlightenment_input( array(
			'label' => __( 'Title:', 'enlightenment' ),
			'name'  => $this->get_field_name( 'title' ),
			'class' => 'widefat',
			'id'    => $this->get_field_id( 'title' ),
			'value' => $instance['title'],
		) );
		echo enlightenment_close_tag( 'p' );

		echo enlightenment_open_tag( 'p' );
		enlightenment_textarea( array(
			'label' => __( 'Tagline:', 'enlightenment' ),
			'name'  => $this->get_field_name( 'tagline' ),
			'class' => 'widefat',
			'id'    => $this->get_field_id( 'tagline' ),
			'value' => $instance['tagline'],
		) );
		echo enlightenment_close_tag( 'p' );

		$options = array(
			'list'     => __( 'List', 'enlightenment' ),
			'gallery'  => __( 'Gallery', 'enlightenment' ),
			'slider'   => __( 'Slider', 'enlightenment' ),
			'carousel' => __( 'Carousel', 'enlightenment' ),
		);
		echo enlightenment_open_tag( 'p', 'type' );
		$select = enlightenment_select_box( array(
			'label'   => __( 'Type:', 'enlightenment' ),
			'name'    => $this->get_field_name( 'type' ),
			'class'   => 'widefat',
			'id'      => $this->get_field_id( 'type' ),
			'options' => $options,
			'value'   => $instance['type'],
		), false );
		if ( 'post_type' == $instance['query'] || 'page' == $instance['query'] ) {
			$select = str_replace( '<option value="carousel">', '<option value="carousel" style="display: none;">', $select );
		}
		echo $select;
		echo enlightenment_close_tag( 'p' );

		$queries = enlightenment_custom_queries();
		$options = array();

		foreach ( $queries as $query => $atts ) {
			$options[$query] = $atts['name'];
		}

		echo enlightenment_open_tag( 'p', 'query' );
		enlightenment_select_box( array(
			'label'   => __( 'Query:', 'enlightenment' ),
			'name'    => $this->get_field_name( 'query' ),
			'class'   => 'widefat',
			'id'      => $this->get_field_id( 'query' ),
			'options' => $options,
			'value'   => $instance['query'],
		) );
		echo enlightenment_close_tag( 'p' );

		$options    = array();
		$post_types = enlightenment_custom_post_types();

		foreach ( $post_types as $post_type => $atts ) {
			$options[$post_type] = $atts['name'];
		}

		echo enlightenment_open_tag( 'p', 'post-types' . ( 'post_type_archive' == $instance['query'] || 'post_type' == $instance['query'] ? ' show' : '' ) );
		enlightenment_select_box( array(
			'label'   => __( 'Post Type:', 'enlightenment' ),
			'name'    => $this->get_field_name( 'post_type' ),
			'class'   => 'widefat',
			'id'      => $this->get_field_id( 'post_type' ),
			'options' => $options,
			'value'   => $instance['post_type'],
		) );
		echo enlightenment_close_tag( 'p' );

		$options = array();
		$posts   = get_posts( array(
			'post_type' => esc_attr( $instance['post_type'] ),
			'posts_per_page' => -1,
		) );

		foreach ( $posts as $post ) {
			$options[$post->ID] = get_the_title( $post->ID );
		}

		echo enlightenment_open_tag( 'p', 'post-type' . ( 'post_type' == $instance['query'] ? ' show' : '' ) );
		enlightenment_select_box( array(
			'label'   => __( 'Post:', 'enlightenment' ),
			'name'    => $this->get_field_name( 'p' ),
			'class'   => 'widefat',
			'id'      => $this->get_field_id( 'p' ),
			'options' => $options,
			'value'   => $instance['p'],
		) );
		echo enlightenment_close_tag( 'p' );

		$options = array();
		$posts = get_posts( array(
			'posts_per_page' => -1,
			'post_type' => 'page',
		) );

		foreach ( $posts as $post ) {
			$options[$post->ID] = get_the_title( $post->ID );
		}

		echo enlightenment_open_tag( 'p', 'page' . ( 'page' == $instance['query'] ? ' show' : '' ) );
		enlightenment_select_box( array(
			'label'   => __( 'Page:', 'enlightenment' ),
			'name'    => $this->get_field_name( 'page_id' ),
			'class'   => 'widefat',
			'id'      => $this->get_field_id( 'page_id' ),
			'options' => $options,
			'value'   => $instance['page_id'],
		) );
		echo enlightenment_close_tag( 'p' );

		$boxes = array();
		$posts = get_posts( array(
			'posts_per_page' => -1,
			'post_type' => 'page',
		) );

		foreach ( $posts as $post ) {
			$boxes[] = array(
				'label'   => get_the_title( $post->ID ),
				'name'    => $this->get_field_name( 'pages' ) . '[]',
				'value'   => $post->ID,
				'checked' => in_array( $post->ID, $instance['pages'] ),
			);
		}

		echo enlightenment_open_tag( 'div', 'pages' . ( 'pages' == $instance['query'] ? ' show' : '' ) );
		echo '<label>' . __( 'Select Pages:', 'enlightenment' ) . '</label><br />';
		echo enlightenment_open_tag( 'div', 'pages-inner' );
		enlightenment_checkboxes( array(
			'boxes' => $boxes,
		) );
		echo enlightenment_close_tag( 'div' );
		echo enlightenment_close_tag( 'div' );

		echo enlightenment_open_tag( 'div', 'image-gallery' . ( 'gallery' == $instance['query'] ? ' show' : '' ) );
		enlightenment_upload_media( array(
			'name' => $this->get_field_name( 'images' ),
			'upload_button_text'   => __( 'Choose Images', 'enlightenment' ),
			'uploader_title'       => __( 'Insert Images', 'enlightenment' ),
			'uploader_button_text' => __( 'Select', 'enlightenment' ),
			'remove_button_text'   => '',
			'mime_type'            => 'image',
			'multiple'             => true,
			'value'                => implode( ',', $instance['images'] ),
		) );
		echo enlightenment_close_tag( 'div' );

		$options = array();
		$authors = get_users( array(
			'has_published_posts' => array( 'post' ),
		) );

		foreach ( $authors as $author ) {
			$options[$author->ID] = $author->display_name;
		}

		echo enlightenment_open_tag( 'p', 'author' . ( 'author' == $instance['query'] ? ' show' : '' ) );
		enlightenment_select_box( array(
			'label'   => __( 'Select Author:', 'enlightenment' ),
			'name'    => $this->get_field_name( 'author' ),
			'class'   => 'widefat',
			'id'      => $this->get_field_id( 'author' ),
			'options' => $options,
			'value'   => $instance['author'],
		) );
		echo enlightenment_close_tag( 'p' );

		$taxonomies = get_taxonomies( array( 'public' => true ), 'objects' );
		$options    = array();

		foreach ( $taxonomies as $name => $taxonomy ) {
			if ( 'post_format' == $name ) {
				$taxonomy->labels->singular_name = __( 'Post Format', 'enlightenment' );
			}
			$options[$name] = $taxonomy->labels->singular_name;
		}

		echo enlightenment_open_tag( 'p', 'taxonomy' . ( 'taxonomy' == $instance['query'] ? ' show' : '' ) );
		enlightenment_select_box( array(
			'label'   => __( 'Taxonomy:', 'enlightenment' ),
			'name'    => $this->get_field_name( 'taxonomy' ),
			'class'   => 'widefat',
			'id'      => $this->get_field_id( 'taxonomy' ),
			'options' => $options,
			'value'   => $instance['taxonomy'],
		) );
		echo enlightenment_close_tag( 'p' );

		$terms   = get_terms( $instance['taxonomy'] );
		$options = array();

		foreach ( $terms as $term ) {
			$options[ $term->slug ] = $term->name;
		}

		echo enlightenment_open_tag( 'p', 'term' . ( 'taxonomy' == $instance['query'] ? ' show' : '' ) );
		enlightenment_select_box( array(
			'label'   => __( 'Term:', 'enlightenment' ),
			'name'    => $this->get_field_name( 'term' ),
			'class'   => 'widefat',
			'id'      => $this->get_field_id( 'term' ),
			'options' => $options,
			'value'   => $instance['term'],
		) );
		echo enlightenment_close_tag( 'p' );

		if ( current_theme_supports( 'enlightenment-grid-loop' ) ) {
			$options = array();

			foreach ( enlightenment_grid_columns() as $grid => $atts ) {
				$options[] = array(
					'image' => $atts['image'],
					'label' => $atts['name'],
					'value' => $grid,
				);
			}

			$show = true;

			if (
				'slider' == $instance['type']
				||
				'post_type' == $instance['query']
				||
				'page' == $instance['query']
			) {
				$show = false;
			}

			$class = 'grid' . ( $show ? ' show' : '' );

			if ( current_theme_supports( 'enlightenment-bootstrap' ) ) {
				$breakpoints = enlightenment_bootstrap_breakpoints();
				$prev_label  = '';

				foreach ( $breakpoints as $breakpoint => $label ) {
					$opts = $options;

					if ( 'smartphone-portrait' != $breakpoint ) {
						$opts = array_merge( array(
							array(
								'label' => sprintf( __( 'Inherit from %s', 'enlightenment' ), $prev_label ),
								'value' => 'inherit',
							),
						), $opts );
					}

					echo enlightenment_open_tag( 'div', $class );
					enlightenment_image_radio_buttons( array(
						'legend'  => sprintf( _x( 'Grid for %s', 'Custom queries widget Bootstrap grid breakpoints', 'enlightenment' ), $label ),
						'name'    => $this->get_field_name( sprintf( 'grid[%s]', $breakpoint ) ),
						'buttons' => $opts,
						'value'   => $instance['grid'][ $breakpoint ],
					) );
					echo enlightenment_close_tag( 'div' );

					$prev_label = $label;
				}
			} else {
				echo enlightenment_open_tag( 'div', $class );
				enlightenment_image_radio_buttons( array(
					'legend'  => __( 'Grid:', 'enlightenment' ),
					'name'    => $this->get_field_name( 'grid' ),
					'buttons' => $options,
					'value'   => $instance['grid'],
				) );
				echo enlightenment_close_tag( 'div' );
			}
		}

		echo enlightenment_open_tag( 'p', 'posts-count' . ( 'post_type_archive' == $instance['query'] || 'author' == $instance['query'] || 'taxonomy' == $instance['query'] ? ' show' : '' ) );
		enlightenment_input( array(
			'label' => __( 'Total number of entries:', 'enlightenment' ),
			'name'  => $this->get_field_name( 'posts_per_page' ),
			'class' => 'widefat',
			'id'    => $this->get_field_id( 'posts_per_page' ),
			'type'  => 'number',
			'value' => $instance['posts_per_page'],
			'min'   => 0,
			'size'  => 3,
		) );
		echo enlightenment_close_tag( 'p' );

		echo enlightenment_open_tag( 'p', 'lead-posts' . ( ( 'slider' != $instance['type'] && 'carousel' != $instance['type'] ) && ( 'sticky_posts' == $instance['query'] || 'post_type_archive' == $instance['query'] || 'pages' == $instance['query'] || 'gallery' == $instance['query'] || 'author' == $instance['query'] || 'taxonomy' == $instance['query'] ) ? ' show' : '' ) );
		enlightenment_input( array(
			'label' => __( 'Number of leading entries:', 'enlightenment' ),
			'name'  => $this->get_field_name( 'leading_posts' ),
			'class' => 'widefat',
			'id'    => $this->get_field_id( 'leading_posts' ),
			'type'  => 'number',
			'value' => $instance['leading_posts'],
			'min'   => 0,
			'size'  => 3,
		) );
		echo enlightenment_close_tag( 'p' );

		echo enlightenment_open_tag( 'p', 'archive-link' . ( 'post_type_archive' == $instance['query'] || 'author' == $instance['query'] || 'taxonomy' == $instance['query'] ? ' show' : '' ) );
		enlightenment_checkbox( array(
			'label'   => __( 'Show Link to Archive', 'enlightenment' ),
			'name'    => $this->get_field_name( 'link_to_archive' ),
			'id'      => $this->get_field_id( 'link_to_archive' ),
			'checked' => $instance['link_to_archive'],
		) );
		echo enlightenment_close_tag( 'p' );

		echo enlightenment_open_tag( 'p', 'archive-link-label' . ( ( 'post_type_archive' == $instance['query'] || 'author' == $instance['query'] || 'taxonomy' == $instance['query'] ) && $instance['link_to_archive'] ? ' show' : '' ) );
		enlightenment_input( array(
			'label' => __( 'Link to Archive Label:', 'enlightenment' ),
			'name'  => $this->get_field_name( 'link_to_archive_label' ),
			'class' => 'widefat',
			'id'    => $this->get_field_id( 'link_to_archive_label' ),
			'value' => $instance['link_to_archive_label'],
		) );
		echo enlightenment_close_tag( 'p' );

		echo enlightenment_close_tag( 'div' );
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance   = array();
		$types      = array( 'list', 'gallery', 'slider', 'carousel' );
		$queries    = array( 'sticky_posts', 'post_type_archive', 'post_type', 'page', 'pages', 'gallery', 'author', 'taxonomy' );
		$post_types = enlightenment_custom_post_types();
		$post       = get_post( $new_instance['p'] );
		$page       = get_post( $new_instance['page_id'] );
		$authors    = get_users( array(
			'has_published_posts' => array( 'post' ),
		) );;

		if (
			'carousel' == $new_instance['type']
			&&
			( 'post_type' == $new_instance['query'] || 'page' == $new_instance['query'] )
		) {
			$new_instance['type'] = 'list';
		}

		$instance['title']     = ! empty( $new_instance['title'] ) ? wp_kses( $new_instance['title'], 'strip' ) : '';
		$instance['tagline']   = ! empty( $new_instance['tagline'] ) ? wp_kses_post( $new_instance['tagline'] ) : '';
		$instance['type']      = in_array( $new_instance['type'], $types ) ? $new_instance['type'] : $old_instance['type'];
		$instance['query']     = in_array( $new_instance['query'], $queries ) ? $new_instance['query'] : $old_instance['query'];
		$instance['post_type'] = array_key_exists( $new_instance['post_type'], $post_types ) ? $new_instance['post_type'] : $old_instance['post_type'];
		$instance['p']         = $post->post_type == $instance['post_type'] ? $new_instance['p'] : $old_instance['p'];
		$instance['page_id']   = 'page' == $page->post_type ? $new_instance['page_id'] : $old_instance['page_id'];

		if ( current_theme_supports( 'enlightenment-grid-loop' ) ) {
			if ( current_theme_supports( 'enlightenment-bootstrap' ) ) {
				$breakpoints = enlightenment_bootstrap_breakpoints();

				foreach ( $breakpoints as $breakpoint => $label ) {
					if ( 'inherit' == $new_instance['grid'][ $breakpoint ] && 'smartphone-portrait' == $breakpoint ) {
						$instance['grid'][ $breakpoint ] = $old_instance['grid'][ $breakpoint ];
					} elseif ( 'inherit' == $new_instance['grid'][ $breakpoint ] ) {
						$instance['grid'][ $breakpoint ] = $new_instance['grid'][ $breakpoint ];
					} else {
						$instance['grid'][ $breakpoint ] = array_key_exists( $new_instance['grid'][ $breakpoint ], enlightenment_grid_columns() ) ? $new_instance['grid'][ $breakpoint ] : $old_instance['grid'][ $breakpoint ];
					}
				}
			} else {
				$instance['grid'] = array_key_exists( $new_instance['grid'], enlightenment_grid_columns() ) ? $new_instance['grid'] : $old_instance['grid'];
			}
		}

		if ( empty( $new_instance['pages'] ) ) {
			$instance['pages'] = array();
		} else {
			$post__in = array();

			foreach ( $new_instance['pages'] as $post_id ) {
				$post__in[] = $post_id;
			}

			$posts = get_posts( array(
				'post__in'       => $post__in,
				'post_type'      => 'page',
				'posts_per_page' => -1,
			) );

			$instance['pages'] = array();

			foreach ( $posts as $post ) {
				$instance['pages'][] = $post->ID;
			}
		}

		if ( empty( $new_instance['images'] ) ) {
			$instance['images'] = array();
		} else {
			if ( ! is_array( $new_instance['images'] ) ) {
				$new_instance['images'] = explode( ',', $new_instance['images'] );
			}

			$posts = get_posts( array(
				'post__in'       => $new_instance['images'],
				'post_type'      => 'attachment',
				'post_mime_type' => 'image',
				'posts_per_page' => -1,
				'post_status'    => 'inherit',
			) );

			foreach ( $posts as $post ) {
				$instance['images'][] = $post->ID;
			}
		}

		$valid = false;
		foreach ( $authors as $author ) {
			if ( $author->ID == $new_instance['author'] ) {
				$valid = true;
				break;
			}
		}
		$instance['author'] = $valid ? $new_instance['author'] : $old_instance['author'];

		$instance['taxonomy'] = taxonomy_exists( $new_instance['taxonomy'] ) ? $new_instance['taxonomy'] : $old_instance['taxonomy'];
		$instance['term']     = term_exists( $new_instance['term'], $instance['taxonomy'] ) ? $new_instance['term'] : $old_instance['term'];

		$instance['posts_per_page'] = ! empty( $new_instance['posts_per_page'] ) ? intval( $new_instance['posts_per_page'] ) : $old_instance['posts_per_page'];
		$instance['leading_posts']  = is_numeric( $new_instance['leading_posts'] ) ? intval( $new_instance['leading_posts'] ) : $old_instance['leading_posts'];

		$instance['link_to_archive']       = isset( $new_instance['link_to_archive'] );
		$instance['link_to_archive_label'] = ! empty( $new_instance['link_to_archive_label'] ) ? wp_kses( $new_instance['link_to_archive_label'], 'strip' ) : '';

		return $instance;
	}

} // class Enlightenment_Custom_Query
