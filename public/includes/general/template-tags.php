<?php

function enlightenment_html_atts( $args = null ) {
	$defaults = array(
		'atts' => get_language_attributes(),
		'echo' => true,
	);
	$defaults = apply_filters( 'enlightenment_html_atts_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

	return enlightenment_extra_atts( $args['atts'], $args['echo'] );
}

function enlightenment_meta_charset( $args = null ) {
	$defaults = array(
		'echo' => true,
	);
	$defaults = apply_filters( 'enlightenment_meta_charset_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

	$output = sprintf( '<meta charset="%s" />%s', get_bloginfo( 'charset' ), "\n" );
	$output = apply_filters( 'enlightenment_meta_charset', $output, $args );

	if ( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_meta_viewport( $args = null ) {
	$defaults = array(
		'width'         => 'device-width',
		'height'        => '',
		'initial_scale' => '1.0',
		'minimum_scale' => '',
		'maximum_scale' => '',
		'user_scalable' => '',
		'echo'          => true,
	);
	$defaults = apply_filters( 'enlightenment_meta_viewport_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

	$content = array();

	if ( '' != $args['width'] ) {
		$content['width'] = $args['width'];
	}

	if ( '' != $args['height'] ) {
		$content['height'] = $args['height'];
	}

	if ( '' != $args['initial_scale'] ) {
		$content['initial-scale'] = $args['initial_scale'];
	}

	if ( '' != $args['minimum_scale'] ) {
		$content['minimum-scale'] = $args['minimum_scale'];
	}

	if ( '' != $args['maximum_scale'] ) {
		$content['maximum-scale'] = $args['maximum_scale'];
	}

	if ( '' != $args['user_scalable'] ) {
		$content['user-scalable'] = $args['user_scalable'];
	}

	$content = apply_filters( 'enlightenment_meta_viewport_content', $content );

	$str = '';

	foreach ( $content as $key => $value ){
		$str .= sprintf( '%s=%s', $key, $value );

		$values = array_values( $content );

		if ( $value != end( $values ) ) {
			$str .= ', ';
		}
	}

	$content = $str;

	$output = '';

	if ( ! empty( $content ) ) {
		$output = sprintf( '<meta name="viewport" content="%s" />%s', esc_attr( $content ), "\n" );
	}

	$output = apply_filters( 'enlightenment_meta_viewport', $output, $args );

	if ( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_profile_link( $args = null ) {
	$defaults = array(
		'link' => 'http://gmpg.org/xfn/11',
		'echo' => true,
	);
	$defaults = apply_filters( 'enlightenment_profile_link_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

	$output = sprintf( '<link rel="profile" href="%s" />%s', esc_url( $args['link'] ), "\n" );
	$output = apply_filters( 'enlightenment_profile_link', $output, $args );

	if ( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_pingback_link( $args = null ) {
	$defaults = array(
		'echo' => true,
		'link' => get_bloginfo( 'pingback_url' ),
	);
	$defaults = apply_filters( 'enlightenment_pingback_link_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

	$output = sprintf( '<link rel="pingback" href="%s" />%s', esc_url( $args['link'] ), "\n" );
	$output = apply_filters( 'enlightenment_pingback_link', $output, $args );

	if ( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_clearfix( $args = null ) {
	$defaults = array(
		'container'       => 'div',
		'container_class' => 'clearfix',
		'echo'            => true,
	);
	$defaults = apply_filters( 'enlightenment_clearfix_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$output  = str_replace( "\n", '', enlightenment_open_tag( $args['container'], $args['container_class'] ) );
	$output .= enlightenment_close_tag( $args['container'] );

	$output = apply_filters( 'enlightenment_clearfix', $output, $args );

	if ( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_body_extra_atts( $args = null ) {
	$defaults = array(
		'atts' => '',
		'echo' => true,
	);
	$defaults = apply_filters( 'enlightenment_body_extra_atts_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

	return enlightenment_extra_atts( $args['atts'], $args['echo'] );
}

function enlightenment_site_header_class( $args = null ) {
	$defaults = array(
		'class' => 'site-header',
		'echo'  => true,
	);
	$defaults = apply_filters( 'enlightenment_site_header_class_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

	return enlightenment_class( $args['class'], $args['echo'] );
}

function enlightenment_site_header_extra_atts( $args = null ) {
	$defaults = array(
		'atts' => array( 'role' => 'banner' ),
		'echo' => true,
	);
	$defaults = apply_filters( 'enlightenment_site_header_extra_atts_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	return enlightenment_extra_atts( $args['atts'], $args['echo'] );
}

function enlightenment_site_branding( $args = null ) {
	$defaults = array(
		'container'            => 'div',
		'container_class'      => 'site-branding',
		'container_extra_atts' => '',
		'before'               => '',
		'after'                => '',
		'format'               => '%1$s%2$s',
		'echo'                 => true,
	);
	$defaults = apply_filters( 'enlightenment_site_branding_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

	$site_title       = enlightenment_site_title( array( 'echo' => false ) );
	$site_description = enlightenment_site_description( array( 'echo' => false ) );

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'], '', $args['container_extra_atts'] );
	$output .= $args['before'];
	$output .= sprintf( $args['format'], $site_title, $site_description );
	$output .= $args['after'];
	$output .= enlightenment_close_tag( $args['container'] );

	$output = apply_filters( 'enlightenment_site_branding', $output, $args );

	if ( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_site_title( $args = null ) {
	$defaults = array(
		'container'            => 'h2',
		'container_class'      => 'site-title',
		'container_id'         => 'site-title',
		'container_extra_atts' => '',
		'echo'                 => true,
	);
	$defaults = apply_filters( 'enlightenment_site_title_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'], $args['container_extra_atts'] );
	$output .= apply_filters( 'enlightenment_site_title_format', get_bloginfo( 'name' ) ) . "\n";
	$output .= enlightenment_close_tag( $args['container'] );

	$output = apply_filters( 'enlightenment_site_title', $output, $args );

	if ( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_site_description( $args = null ) {
	$defaults = array(
		'container'            => 'h1',
		'container_class'      => 'site-description',
		'container_id'         => 'site-description',
		'container_extra_atts' => '',
		'echo'                 => true,
	);
	$defaults = apply_filters( 'enlightenment_site_description_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'], $args['container_extra_atts'] );
	$output .= apply_filters( 'enlightenment_site_description_format', get_bloginfo( 'description' ) );
	$output .= enlightenment_close_tag( $args['container'] );

	$output = apply_filters( 'enlightenment_site_description', $output, $args );

	if ( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_search_form( $args = null ) {
	$defaults = array(
		'container_class'   => 'search-form',
		'container_id'      => 'searchform',
		'before'            => '',
		'after'             => '',
		'input_class'       => 'search-query',
		'input_id'          => 's',
		'placeholder'       => __( 'Search this website', 'enlightenment' ),
		'submit_class'      => '',
		'submit_id'         => 'searchsubmit',
		'submit_extra_atts' => ' type="submit"',
		'submit'            => __( 'Search', 'enlightenment' ),
		'before_submit'     => '',
		'after_submit'      => '',
		'echo'              => true,
	);
	$defaults = apply_filters( 'enlightenment_search_form_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

	$output = enlightenment_open_tag(
		'form',
		$args['container_class'],
		$args['container_id'],
		array(
			'action' => home_url( '/' ),
			'method' => 'get',
			'role'   => 'search'
		)
	);
	$output .= $args['before'];
	$output .= sprintf(
		'<input name="s"%s%s type="text" value="%s"%s />',
		enlightenment_class( $args['input_class'] ),
		enlightenment_id( $args['input_id'] ),
		get_search_query(),
		( ! empty( $args['placeholder'] ) ? ' placeholder="' . esc_attr( $args['placeholder'] ) . '"' : '' )
	);
	$output .= $args['before_submit'];
	$output .= enlightenment_open_tag( 'button', $args['submit_class'], $args['submit_id'] , $args['submit_extra_atts'] );
	$output .= wp_kses_post( $args['submit'] );
	$output .= enlightenment_close_tag( 'button' );
	$output .= $args['after_submit'];
	$output .= $args['after'];
	$output .= enlightenment_close_tag( 'form' );

	$output = apply_filters( 'enlightenment_search_form', $output, $args );

	if ( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_page_content_class( $args = null ) {
	$defaults = array(
		'class' => 'site-main hfeed',
		'echo'  => true,
	);
	$defaults = apply_filters( 'enlightenment_page_content_class_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

	return enlightenment_class( $args['class'], $args['echo'] );
}

function enlightenment_page_content_extra_atts( $args = null ) {
	$defaults = array(
		'atts' => ' role="main"',
		'echo' => true,
	);
	$defaults = apply_filters( 'enlightenment_page_content_extra_atts_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

	return enlightenment_extra_atts( $args['atts'], $args['echo'] );
}

function enlightenment_sidebar_class( $args = null ) {
	$defaults = array(
		'class' => 'widget-area sidebar sidebar-' . enlightenment_current_sidebar_name(),
		'echo'  => true,
	);
	$defaults = apply_filters( 'enlightenment_sidebar_class_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

	return enlightenment_class( $args['class'], $args['echo'] );
}

function enlightenment_sidebar_extra_atts( $args = null ) {
	$defaults = array(
		'atts' => ' role="complementary"',
		'echo' => true,
	);
	$defaults = apply_filters( 'enlightenment_sidebar_extra_atts_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

	return enlightenment_extra_attr( $args['atts'], $args['echo'] );
}

function enlightenment_site_footer_class( $args = null ) {
	$defaults = array(
		'class' => 'site-footer',
		'echo'  => true,
	);
	$defaults = apply_filters( 'enlightenment_site_footer_class_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

	return enlightenment_class( $args['class'], $args['echo'] );
}

function enlightenment_site_footer_extra_atts( $args = null ) {
	$defaults = array(
		'atts' => ' role="contentinfo"',
		'echo' => true,
	);
	$defaults = apply_filters( 'enlightenment_site_footer_extra_atts_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

	return enlightenment_extra_atts( $args['atts'], $args['echo'] );
}

function enlightenment_copyright_notice( $args = null ) {
	$defaults = array(
		'container'       => 'div',
		'container_class' => 'copyright',
		'wrap'            => 'p',
		'format'          => '&copy; %1$s %2$s',
		'echo'            => true,
	);
	$defaults = apply_filters( 'enlightenment_copyright_notice_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

	$text = sprintf( $args['format'], date( 'Y' ), get_bloginfo( 'name' ) );

	if ( empty( $text ) ) {
		return;
	}

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'] );
	$output .= enlightenment_open_tag( $args['wrap'] );
	$output .= wp_kses_post( $text );
	$output .= enlightenment_close_tag( $args['wrap'] );
	$output .= enlightenment_close_tag( $args['container'] );

	$output = apply_filters( 'enlightenment_copyright_notice', $output, $args );

	if ( ! $args['echo'] ) {
		return $output;
	}
	echo $output;
}

function enlightenment_credit_links( $args = null ) {
	$defaults = array(
		'container'       => 'div',
		'container_class' => 'credits',
		'wrap'            => 'p',
		'format'          => __( 'Powered by %1$s and %2$s', 'enlightenment' ),
		'echo'            => true,
	);
	$defaults = apply_filters( 'enlightenment_credit_links_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

	$text = sprintf(
		$args['format'],
		sprintf(
			'<a href="%s" rel="designer">%s</a>',
			esc_url( __( 'https://enlightenmentcore.org/', 'enlightenment' ) ),
			__( 'Enlightenment Framework', 'enlightenment' )
		),
		sprintf(
			'<a href="%s" rel="generator">%s</a>',
			esc_url( __( 'https://wordpress.org/', 'enlightenment' ) ),
			__( 'WordPress', 'enlightenment' )
		)
	);

	if ( empty( $text ) ) {
		return;
	}

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'] );
	$output .= enlightenment_open_tag( $args['wrap'] );
	$output .= wp_kses_post( $text ) . "\n";
	$output .= enlightenment_close_tag( $args['wrap'] );
	$output .= enlightenment_close_tag( $args['container'] );

	$output = apply_filters( 'enlightenment_credit_links', $output, $args );

	if ( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}
