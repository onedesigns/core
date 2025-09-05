<?php

/**
 * Register BuddyPress Widgets.
 *
 * @since 2.0.0
 */
function enlightenment_bp_register_widgets() {
	if( bp_is_active( 'xprofile' ) ) {
		register_widget( 'Enlightenment_BP_Profile_Widget' );
	}

	if( bp_is_active( 'friends' ) ) {
		register_widget( 'Enlightenment_BP_Random_Friends_Widget' );
	}

	if( class_exists( 'RTMediaQuery' ) ) {
		register_widget( 'Enlightenment_RTMedia_Latest_Photos_Widget' );
	}
}
add_action( 'widgets_init', 'enlightenment_bp_register_widgets' );

function enlightenment_bp_widgets_enqueue_scripts( $hook_suffix ) {
	if ( 'widgets.php' == $hook_suffix ) {
		wp_enqueue_style(
			'enlightenment-bp-widgets',
			enlightenment_styles_directory_uri() . '/bp-widgets.css',
			array( 'font-awesome', 'enlightenment-dropdown' ),
			null
		);

		wp_enqueue_script(
			'enlightenment-bp-widgets',
			enlightenment_scripts_directory_uri() . '/bp-widgets.js',
			array( 'jquery', 'enlightenment-dropdown' ),
			null
		);
	}
}
add_action( 'admin_enqueue_scripts', 'enlightenment_bp_widgets_enqueue_scripts' );

class Enlightenment_BP_Profile_Widget extends WP_Widget {

	/**
	 * Class constructor.
	 *
	 * @since 2.0.0
	 */
	function __construct() {
		$widget_ops = array(
			'description'                 => __( "A short list of the displayed member's profile information.", 'enlightenment' ),
			'classname'                   => 'widget_profile_info buddypress widget',
			'customize_selective_refresh' => true,
		);
		parent::__construct( false, $name = _x( '(BuddyPress) Profile Fields', 'widget name', 'enlightenment' ), $widget_ops );

		if ( is_customize_preview() || is_active_widget( false, false, $this->id_base ) ) {
			add_action( 'bp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		}
	}

	/**
	 * Enqueue scripts.
	 *
	 * @since 2.0.0
	 */
	public function enqueue_scripts() {
	}

	/**
	 * Display the widget.
	 *
	 * @since 2.0.0
	 *
	 * @param array $args Widget arguments.
	 * @param array $instance The widget settings, as saved by the user.
	 */
	function widget( $args, $instance ) {
		if ( bp_displayed_user_id() ) {
			$user_id = bp_displayed_user_id();
		} elseif ( ! empty( $_GET['legacy-widget-preview'] ) ) {
			$user_id = get_current_user_id();
		} else {
			if ( enlightenment_has_in_call_stack( array( 'WP_REST_Widget_Types_Controller', 'get_widget_preview' ) ) ) {
				$user_id = get_current_user_id();
			} else {
				$user_id = 0;
			}
		}

		if ( ! $user_id ) {
			return;
		}

		extract( $args );
		extract( $instance );

		/**
		 * Filters the Friends widget title.
		 *
		 * @since 2.0.0
		 *
		 * @param string $title    The widget title.
		 * @param array  $instance The settings for the particular instance of the widget.
		 * @param string $id_base  Root ID for all widgets of this type.
		 */
		$title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );

		$output  = enlightenment_open_tag( 'ul' );

		if ( $show_last_active ) {
			$output .= enlightenment_open_tag( 'li', 'field_last-active' );
			$output .= sprintf( '<i class="field-icon fa%s fa-fw"></i> ', esc_attr( $last_active_icon ) );
			$output .= sprintf( '<span class="field-text">%s</span>', ucfirst( bp_get_last_activity( $user_id ) ) );
			$output .= enlightenment_close_tag( 'li' );
		}

		if ( bp_has_profile( array(
			'user_id'          => $user_id,
			'profile_group_id' => $profile_group_id,
		) ) ) {
			while ( bp_profile_groups() ) {
				bp_the_profile_group();

				while ( bp_profile_fields() ) {
					bp_the_profile_field();

					global $field;
					if ( $field->data->value == bp_core_get_user_displayname( $user_id ) ) {
						continue;
					}

					if ( bp_field_has_data() ) {
						if (
							isset( $fields_formats[ $profile_group_id ] ) &&
							isset( $fields_formats[ $profile_group_id ][ $field->id ] )
						) {
							$format = $fields_formats[ $profile_group_id ][ $field->id ];
							$format = str_replace( '%name%',  '%1$s', $format );
							$format = str_replace( '%value%', '%2$s', $format );

							if( empty( $format ) ) {
								$format = '%1$s %2$s';
							}
						} else {
							$format = '%1$s %2$s';
						}

						if (
							isset( $fields_icons[ $profile_group_id ] ) &&
							isset( $fields_icons[ $profile_group_id ][ $field->id ] )
						) {
							$icon = $fields_icons[ $profile_group_id ][ $field->id ];
						} else {
							$icon = '';
						}

						$output .= sprintf( '<li %s>', bp_get_field_css_class() );

						if ( ! empty( $icon ) ) {
							$output .= sprintf( '<i class="field-icon fa%s fa-fw"></i> ', esc_attr( $icon ) );
						}

						$output .= sprintf(
							sprintf( '<span class="field-text">%s</span>', $format ),
							sprintf( '<strong>%s:</strong>', bp_get_the_profile_field_name() ),
							bp_get_the_profile_field_value()
						);

						$output .= sprintf( '</li>' );
					}
				}
			}
		}

		$output .= enlightenment_close_tag( 'ul' );

		echo $before_widget;

		if( ! empty( $title ) ) {
			echo $before_title . esc_html( $title ) . $after_title;
		}

		echo $output;

		echo $after_widget;
	}

	/**
	 * Process a widget save.
	 *
	 * @since 2.0.0
	 *
	 * @param array $new_instance The parameters saved by the user.
	 * @param array $old_instance The parameters as previously saved to the database.
	 * @return array $instance The processed settings to save.
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		if ( ! isset( $new_instance['last_active_icon'] ) ) {
			$new_instance['last_active_icon'] = 'r fa-clock';
		}

		$instance['title']            = wp_kses( $new_instance['title'], 'strip' );
		$instance['show_last_active'] = (bool) $new_instance['show_last_active'];
		$instance['last_active_icon'] = sanitize_text_field( $new_instance['last_active_icon'] );
		$instance['profile_group_id'] = absint( $new_instance['profile_group_id'] );
		$instance['fields_formats']   = array();
		$instance['fields_icons']	  = array();

		foreach( $new_instance['fields_formats'] as $group_id => $fields ) {
			$instance['fields_formats'][ $group_id ] = array();

			foreach( $fields as $field_id => $format  ) {
				$instance['fields_formats'][ $group_id ][ $field_id ] = sanitize_text_field( $format );
			}
		}

		if ( isset( $new_instance['fields_icons'] ) ) {
			foreach( $new_instance['fields_icons'] as $group_id => $fields ) {
				$instance['fields_icons'][ $group_id ] = array();

				foreach( $fields as $field_id => $icon  ) {
					$instance['fields_icons'][ $group_id ][ $field_id ] = sanitize_text_field( $icon );
				}
			}
		}

		return $instance;
	}

	/**
	 * Render the widget edit form.
	 *
	 * @since 2.0.0
	 *
	 * @param array $instance The saved widget settings.
	 * @return void
	 */
	function form( $instance ) {
		$defaults = array(
			'title'            => '',
			'show_last_active' => true,
			'last_active_icon' => 'r fa-clock',
			'profile_group_id' => 1,
			'fields_formats'   => array(),
			'fields_icons'     => array(),
		);
		$instance = wp_parse_args( (array) $instance, $defaults );

		$title            = $instance['title'];
		$show_last_active = $instance['show_last_active'];
		$last_active_icon = $instance['last_active_icon'];
		$profile_group_id = $instance['profile_group_id'];
		$fields_formats   = $instance['fields_formats'];
		$fields_icons     = $instance['fields_icons'];

		$groups = bp_xprofile_get_groups( array(
			'user_id'           => false,
			'fetch_fields'      => true,
		) );

		$icons  = current_theme_supports( 'enlightenment-menu-icons' ) ? enlightenment_menu_icons() : array();

		$output  = '<p>';
		$output .= sprintf( '<label for="%s">', $this->get_field_id( 'title' ) );
		$output .= esc_html__( 'Title:', 'enlightenment' );
		$output .= sprintf(
			'<input class="widefat" id="%s" name="%s" type="text" value="%s" />',
			$this->get_field_id( 'title' ),
			$this->get_field_name( 'title' ),
			esc_attr( $title )
		);
		$output .= '</label>';
		$output .= '</p>';

		$output .= '<p>';
		$output .= sprintf(
			'<input id="%s" name="%s" type="checkbox" value="1" %s />',
			$this->get_field_id( 'show_last_active' ),
			$this->get_field_name( 'show_last_active' ),
			checked( $show_last_active, true, false )
		);
		$output .= "\n";
		$output .= sprintf(
			'<label for="%s">%s</label>',
			$this->get_field_id( 'show_last_active' ),
			__( 'Show last activity time', 'enlightenment' )
		);
		$output .= '</p>';

		if ( current_theme_supports( 'enlightenment-menu-icons' ) ) {
			$output .= sprintf(
				'<label for="%s">%s</label>',
				$this->get_field_id( 'last_active_icon' ),
				__( 'Last activity time icon', 'enlightenment' )
			);
			$output .= '<br />';
			$output .= sprintf(
				'<div class="wrapper-dropdown" id="wrapper-dropdown-%s">',
				$this->get_field_id( 'last_active_icon' )
			);
			$output .= sprintf(
				'<span class="current">%s</span>',
				( empty( $last_active_icon ) ? '' : '<i class="fa' . esc_attr( $last_active_icon ) . ' fa-fw"></i> ' ) .
				$icons[ $last_active_icon ]
			);

			$output .= '<ul class="dropdown">';
			foreach( $icons as $icon => $name ) {
				$output .= sprintf(
					'<li><a href="#" data-icon="%s"><i class="fa%s fa-fw"></i> %s</a></li>',
					esc_attr( $icon ),
					esc_attr( $icon ),
					esc_html( $name )
				);
			}
			$output .= '</ul>';
			$output .= '</div>';
			$output .= sprintf(
				'<input class="edit-profile-field-icon" id="%s" name="%s" type="hidden" value="%s" />',
				$this->get_field_id( 'last_active_icon' ),
				$this->get_field_name( 'last_active_icon' ),
				esc_attr( $last_active_icon )
			);
		}

		$output .= '<p>';
		$output .= sprintf(
			'<label for="%s">%s</label>',
			$this->get_field_id( 'profile_group_id' ),
			__( 'Profile group to show:', 'enlightenment' )
		);
		$output .= '<br />';
		$output .= sprintf(
			'<select class="widefat" name="%s" id="%s">',
			$this->get_field_name( 'profile_group_id' ),
			$this->get_field_id( 'profile_group_id' )
		);
		foreach( $groups as $group ) {
			$output .= sprintf(
				'<option value="%s" %s>%s</option>',
				$group->id,
				selected( $profile_group_id, $group->id, false ),
				$group->name
			);
		}
		$output .= '</select>';
		$output .= '</p>';

		$output .= sprintf( '<h3>%s</h3>', __( 'Customize Fields', 'enlightenment' ) );

		$output .= sprintf(
			'<p class="description">%s</p>',
			__( '<code>%name%</code> will be replaced with the field name, <code>%value%</code> with the field value.', 'enlightenment' )
		);

		foreach( $groups as $group ) {
			$output .= sprintf(
				'<div class="profile-group%s" id="profile-group-%s">',
				( $profile_group_id == $group->id ? '' : ' hidden' ),
				$group->id
			);

			foreach( $group->fields as $field ) {
				if( 1 == $field->id && 1 == $field->group_id ) {
					continue;
				}

				if( isset( $fields_formats[ $group->id ][ $field->id ] ) ) {
					$format = $fields_formats[ $group->id ][ $field->id ];
				} else {
					$format = '%name% %value%';
				}

				if( isset( $fields_icons[ $group->id ][ $field->id ] ) ) {
					$icon = $fields_icons[ $group->id ][ $field->id ];
				} else {
					$icon = '';
				}

				$output .= '<fieldset>';
				$output .= sprintf( '<legend>%s</legend>', $field->name );
				$output .= '<p>';
				$output .= sprintf(
					'<label for="%s">%s</label>',
					$this->get_field_id( sprintf( 'fields_formats[%s][%s]', $group->id, $field->id ) ),
					_x( 'Format', 'xprofile field', 'enlightenment' )
				);
				$output .= '<br />';
				$output .= sprintf(
					'<input class="widefat" id="%s" name="%s" type="text" value="%s" />',
					$this->get_field_id( sprintf( 'fields_formats[%s][%s]', $group->id, $field->id ) ),
					$this->get_field_name( sprintf( 'fields_formats[%s][%s]', $group->id, $field->id ) ),
					esc_attr( $format )
				);
				$output .= '</p>';

				if ( current_theme_supports( 'enlightenment-menu-icons' ) ) {
					$output .= '<div>';
					$output .= sprintf(
						'<label for="%s">%s</label>',
						$this->get_field_id( sprintf( 'fields_formats[%s][%s]', $group->id, $field->id ) ),
						_x( 'Icon', 'xprofile field', 'enlightenment' )
					);
					$output .= '<br />';
					$output .= sprintf(
						'<div class="wrapper-dropdown" id="wrapper-dropdown-%s">',
						$this->get_field_id( sprintf( 'fields_icons[%s][%s]', $group->id, $field->id ) )
					);
					$output .= sprintf(
						'<span class="current">%s</span>',
						( empty( $icon ) ? '' : '<i class="fa' . esc_attr( $icon ) . ' fa-fw"></i> ' ) . $icons[$icon]
					);

					$output .= '<ul class="dropdown">';
					foreach( $icons as $class => $name ) {
						$output .= sprintf(
							'<li><a href="#" data-icon="%s"><i class="fa%s fa-fw"></i> %s</a></li>',
							esc_attr( $class ),
							esc_attr( $class ),
							esc_html( $name )
						);
					}
					$output .= '</ul>';
					$output .= '</div>';
					$output .= sprintf(
						'<input class="edit-profile-field-icon" id="%s" name="%s" type="hidden" value="%s" />',
						$this->get_field_id( sprintf( 'fields_icons[%s][%s]', $group->id, $field->id ) ),
						$this->get_field_name( sprintf( 'fields_icons[%s][%s]', $group->id, $field->id ) ),
						esc_attr( $icon )
					);
					$output .= '</div>';
				}

				$output .= '</fieldset>';
			}

			$output .= '</div>';
		}

		echo $output;
	}

}

class Enlightenment_BP_Random_Friends_Widget extends WP_Widget {

	/**
	 * Class constructor.
	 *
	 * @since 2.0.0
	 */
	function __construct() {
		$widget_ops = array(
			'description'                 => __( "Show avatars of the displayed member's friends.", 'enlightenment' ),
			'classname'                   => 'widget_user_friends buddypress widget',
			'customize_selective_refresh' => true,
		);
		parent::__construct( false, $name = _x( '(BuddyPress) Random Friends', 'widget name', 'enlightenment' ), $widget_ops );

		if ( is_customize_preview() || is_active_widget( false, false, $this->id_base ) ) {
			add_action( 'bp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		}
	}

	/**
	 * Enqueue scripts.
	 *
	 * @since 2.0.0
	 */
	public function enqueue_scripts() {
	}

	/**
	 * Display the widget.
	 *
	 * @since 2.0.0
	 *
	 * @param array $args Widget arguments.
	 * @param array $instance The widget settings, as saved by the user.
	 */
	function widget( $args, $instance ) {
		if ( bp_displayed_user_id() ) {
			$user_id = bp_displayed_user_id();
		} elseif ( ! empty( $_GET['legacy-widget-preview'] ) ) {
			$user_id = get_current_user_id();
		} else {
			if ( enlightenment_has_in_call_stack( array( 'WP_REST_Widget_Types_Controller', 'get_widget_preview' ) ) ) {
				$user_id = get_current_user_id();
			} else {
				$user_id = 0;
			}
		}

		if ( ! $user_id ) {
			return;
		}

		extract( $args );

		/**
		 * Filters the Friends widget title.
		 *
		 * @since 2.0.0
		 *
		 * @param string $title    The widget title.
		 * @param array  $instance The settings for the particular instance of the widget.
		 * @param string $id_base  Root ID for all widgets of this type.
		 */
		$title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );

		$users = new BP_User_Query( array(
			'type'     => 'random',
			'per_page' => $instance['per_page'],
			'user_id'  => $user_id,
		) );

		if ( empty( $users->results ) ) {
			return;
		}

		$count = 0;

		$output = enlightenment_open_tag( 'ul', 'friends-list' );

		foreach ( $users->results as $user ) {
			$count++;

			$avatar_type = apply_filters( 'enblightenment_bp_random_friends_widget_avatar_type', 'thumb', $count );
			$avatar_size = apply_filters( 'enblightenment_bp_random_friends_widget_avatar_size', 96, $count );

			$output .= enlightenment_open_tag( 'li', 'user member friend' );

			$output .= sprintf(
				'<a href="%1$s" title="%2$s" class="%3$s">',
				bp_members_get_user_url( $user->ID ),
				$user->fullname,
				'item-avatar'
			);
			$output .= bp_core_fetch_avatar( array(
				'item_id' => $user->ID,
				'type'    => $avatar_type,
				'width'   => $avatar_size,
				'height'  => $avatar_size,
				'email'   => $user->user_email,
			) );
			$output .= '</a>';

			$output .= enlightenment_close_tag( 'li' );
		}

		$output .= enlightenment_close_tag( 'ul' );

		echo $before_widget;

		if ( ! empty( $title ) ) {
			echo $before_title . esc_html( $title ) . $after_title;
		}

		echo $output;

		echo $after_widget;
	}

	/**
	 * Process a widget save.
	 *
	 * @since 2.0.0
	 *
	 * @param array $new_instance The parameters saved by the user.
	 * @param array $old_instance The parameters as previously saved to the database.
	 * @return array $instance The processed settings to save.
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['title']    = wp_kses( $new_instance['title'], 'strip' );
		$instance['per_page'] = absint( $new_instance['per_page'] );

		return $instance;
	}

	/**
	 * Render the widget edit form.
	 *
	 * @since 2.0.0
	 *
	 * @param array $instance The saved widget settings.
	 * @return void
	 */
	function form( $instance ) {
		$defaults = array(
			'title'    => __( 'Friends', 'enlightenment' ),
			'per_page' => 8,
		);
		$instance = wp_parse_args( (array) $instance, $defaults );

		$title    = $instance['title'];
		$per_page = $instance['per_page'];

		$output  = '<p>';
		$output .= sprintf( '<label for="%s">', $this->get_field_id( 'title' ) );
		$output .= esc_html__( 'Title:', 'enlightenment' );
		$output .= sprintf(
			'<input class="widefat" id="%s" name="%s" type="text" value="%s" />',
			$this->get_field_id( 'title' ),
			$this->get_field_name( 'title' ),
			esc_attr( $title )
		);
		$output .= '</label>';
		$output .= '</p>';

		$output .= '<p>';
		$output .= sprintf( '<label for="%s">', $this->get_field_id( 'per_page' ) );
		$output .= esc_html__( 'Max friends to show:', 'enlightenment' );
		$output .= sprintf(
			' <input class="widefat" id="%s" name="%s" type="number" value="%s" />',
			$this->get_field_id( 'per_page' ),
			$this->get_field_name( 'per_page' ),
			absint( $per_page )
		);
		$output .= '</label>';
		$output .= '</p>';

		echo $output;
	}

}

if( ! class_exists( 'RTMediaQuery' ) ) {
	return;
}

class Enlightenment_RTMedia_Latest_Photos_Widget extends WP_Widget {

	/**
	 * Class constructor.
	 *
	 * @since 2.0.0
	 */
	function __construct() {
		$widget_ops = array(
			'description'                 => __( "Show thumbnails of the displayed member's latest photos.", 'enlightenment' ),
			'classname'                   => 'widget_latest_photos buddypress widget',
			'customize_selective_refresh' => true,
		);
		parent::__construct( false, $name = _x( '(BuddyPress) Latest Photos', 'widget name', 'enlightenment' ), $widget_ops );

		if ( is_customize_preview() || is_active_widget( false, false, $this->id_base ) ) {
			add_action( 'bp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		}
	}

	/**
	 * Enqueue scripts.
	 *
	 * @since 2.0.0
	 */
	public function enqueue_scripts() {
	}

	/**
	 * Display the widget.
	 *
	 * @since 2.0.0
	 *
	 * @param array $args Widget arguments.
	 * @param array $instance The widget settings, as saved by the user.
	 */
	function widget( $args, $instance ) {
		if ( bp_displayed_user_id() ) {
			$user_id = bp_displayed_user_id();
		} elseif ( ! empty( $_GET['legacy-widget-preview'] ) ) {
			$user_id = get_current_user_id();
		} else {
			if ( enlightenment_has_in_call_stack( array( 'WP_REST_Widget_Types_Controller', 'get_widget_preview' ) ) ) {
				$user_id = get_current_user_id();
			} else {
				$user_id = 0;
			}
		}

		if ( ! $user_id ) {
			return;
		}

		extract( $args );

		/**
		 * Filters the Friends widget title.
		 *
		 * @since 2.0.0
		 *
		 * @param string $title    The widget title.
		 * @param array  $instance The settings for the particular instance of the widget.
		 * @param string $id_base  Root ID for all widgets of this type.
		 */
		$title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );

		global $rtmedia_interaction, $rtmedia_query;

		if ( empty( $rtmedia_interaction->routes ) ) {
			$rtmedia_interaction->set_routers();
		}

		$rtmedia_query = new RTMediaQuery( array(
			'context'     => 'profile',
			'context_id'  => $user_id,
			'per_page'    => $instance['per_page'],
			'media_title' => false,
		) );

		if ( ! have_rtmedia() ) {
			return;
		}

		$output  = '';

		$output .= enlightenment_open_tag( 'div', 'rtmedia-container' );
		$output .= enlightenment_open_tag( 'ul', 'rtmedia-list rtmedia-list-media rtm-gallery-list clearfix' );

		while ( have_rtmedia() ) {
			rtmedia();

			$output .= enlightenment_open_tag( 'li', 'rtmedia-list-item', rtmedia_id() );
			$output .= enlightenment_open_tag( 'div', 'rtmedia-item-thumbnail' );
			$output .= sprintf(
				'<a href="%1$s" title="%2$s" class="%3$s">',
				get_rtmedia_permalink( rtmedia_id() ),
				rtmedia_title(),
				apply_filters( 'rtmedia_gallery_list_item_a_class', 'rtmedia-list-item-a' )
			);
			$output .= sprintf(
				'<img src="%1$s" alt="%2$s" />',
				rtmedia_image( 'rt_media_thumbnail', false, false ),
				rtmedia_image_alt( false, false )
			);
			$output .= '</a>';
			$output .= enlightenment_close_tag( 'div' );
			$output .= enlightenment_close_tag( 'li' );
		}

		$output .= enlightenment_close_tag( 'ul' );
		$output .= enlightenment_close_tag( 'div' );

		echo $before_widget;

		if ( ! empty( $title ) ) {
			echo $before_title . esc_html( $title ) . $after_title;
		}

		echo $output;

		echo $after_widget;
	}

	/**
	 * Process a widget save.
	 *
	 * @since 2.0.0
	 *
	 * @param array $new_instance The parameters saved by the user.
	 * @param array $old_instance The parameters as previously saved to the database.
	 * @return array $instance The processed settings to save.
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['title']    = wp_kses( $new_instance['title'], 'strip' );
		$instance['per_page'] = absint( $new_instance['per_page'] );

		return $instance;
	}

	/**
	 * Render the widget edit form.
	 *
	 * @since 2.0.0
	 *
	 * @param array $instance The saved widget settings.
	 * @return void
	 */
	function form( $instance ) {
		$defaults = array(
			'title'    => __( 'Latest Photos', 'enlightenment' ),
			'per_page' => 8,
		);
		$instance = wp_parse_args( (array) $instance, $defaults );

		$title    = $instance['title'];
		$per_page = $instance['per_page'];

		$output  = '<p>';
		$output .= sprintf( '<label for="%s">', $this->get_field_id( 'title' ) );
		$output .= esc_html__( 'Title:', 'enlightenment' );
		$output .= sprintf(
			'<input class="widefat" id="%s" name="%s" type="text" value="%s" />',
			$this->get_field_id( 'title' ),
			$this->get_field_name( 'title' ),
			esc_attr( $title )
		);
		$output .= '</label>';
		$output .= '</p>';

		$output .= '<p>';
		$output .= sprintf( '<label for="%s">', $this->get_field_id( 'per_page' ) );
		$output .= esc_html__( 'Max photos to show:', 'enlightenment' );
		$output .= sprintf(
			' <input class="widefat" id="%s" name="%s" type="number" value="%s" />',
			$this->get_field_id( 'per_page' ),
			$this->get_field_name( 'per_page' ),
			absint( $per_page )
		);
		$output .= '</label>';
		$output .= '</p>';

		echo $output;
	}

}
