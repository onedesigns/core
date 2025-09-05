<?php

function enlightenment_entry_gallery( $args = null ) {
	$defaults = array(
		'container'       => 'div',
		'container_class' => 'entry-gallery',
		'echo'            => true,
	);
	$defaults = apply_filters( 'enlightenment_entry_gallery_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	global $more;
	$temp    = $more;
	$more    = true;
	$content = get_the_content();
	$more    = $temp;

	$map = array();

	$start = strpos( $content, '<!-- wp:gallery ' );
	if ( false !== $start ) {
		$map[ $start ] = 'wp:gallery';
	}

	$start = strpos( $content, '[gallery]' );
	if ( false !== $start ) {
		$map[ $start ] = 'gallery';
	}

	$start = strpos( $content, '[gallery ' );
	if ( false !== $start ) {
		$map[ $start ] = 'gallery';
	}

	$start = strpos( $content, '<!-- wp:jetpack/tiled-gallery ' );
	if ( false !== $start ) {
		$map[ $start ] = 'wp:jetpack/tiled-gallery';
	}

	$start = strpos( $content, '<!-- wp:jetpack/slideshow ' );
	if ( false !== $start ) {
		$map[ $start ] = 'wp:jetpack/slideshow';
	}

	if ( empty( $map ) ) {
		return false;
	}

	$min  = min( array_keys( $map ) );
	$html = '';

	switch ( $map[ $min ] ) {
		case 'wp:gallery':
			$start  = $min;
			$end    = strpos( $content, '<!-- /wp:gallery -->', $start ) + 20;
			$length = $end - $start;
			$block  = substr( $content, $start, $length );
			$block  = parse_blocks( $block );
			$html   = render_block( $block[0] );

			break;

		case 'gallery':
			preg_match( '/\[(\[?)(gallery)(?![\w-])([^\]\/]*(?:\/(?!\])[^\]\/]*)*?)(?:(\/)\]|\](?:([^\[]*+(?:\[(?!\/\2\])[^\[]*+)*+)\[\/\2\])?)(\]?)/s', $content, $matches );

			$html = do_shortcode( $matches[0] );

			break;

		case 'wp:jetpack/tiled-gallery':
			$start  = $min;
			$end    = strpos( $content, '<!-- /wp:jetpack/tiled-gallery -->', $start ) + 34;
			$length = $end - $start;
			$block  = substr( $content, $start, $length );
			$block  = parse_blocks( $block );
			$html   = render_block( $block[0] );
			$html   = apply_filters( 'gallery_style', $html );
			$html   = apply_filters( 'the_content', $html );

			// Hack to force Jetpack to load the tiled gallery script and style
			ob_start();
			the_content();
			ob_end_clean();

			break;

		case 'wp:jetpack/slideshow':
			$start  = $min;
			$end    = strpos( $content, '<!-- /wp:jetpack/slideshow -->', $start ) + 30;
			$length = $end - $start;
			$block  = substr( $content, $start, $length );
			$block  = parse_blocks( $block );
			$html   = render_block( $block[0] );

			// Hack to force Jetpack to load the slideshow script and style
			ob_start();
			the_content();
			ob_end_clean();

			break;
	}

	if ( empty( $html ) ) {
		return false;
	}

	$html = str_replace( ' alignleft',   '', $html );
	$html = str_replace( ' alignright',  '', $html );
	$html = str_replace( ' aligncenter', '', $html );
	$html = str_replace( ' alignwide',   '', $html );
	$html = str_replace( ' alignfull',   '', $html );

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'] );
	$output .= $html;
	$output .= enlightenment_close_tag( $args['container'] );

	$output = apply_filters( 'enlightenment_entry_gallery', $output, $args );

	if ( ! $args['echo'] ) {
		return $output;
	}
	echo $output;
}

function enlightenment_entry_image( $args = null ) {
	$defaults = array(
		'container'       => 'figure',
		'container_class' => 'entry-media',
		'caption_tag'     => 'figcaption',
		'caption_class'   => 'entry-media-caption',
		'echo'            => true,
	);
	$defaults = apply_filters( 'enlightenment_entry_gallery_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$html = '';

	if( has_post_thumbnail() ) {
		$attachment = get_post( get_post_thumbnail_id() );
		$image      = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );

		$html  = sprintf( '<a href="%s" title="%s" rel="attachment">', esc_url( $image[0] ), the_title_attribute( array( 'echo' => false ) ) );
		$html .= wp_get_attachment_image( $attachment->ID, apply_filters( 'post_thumbnail_size', 'post-thumbnail' ), false );
		$html .= '</a>';
	} else {
		global $more;
		$temp    = $more;
		$more    = true;
		$content = get_the_content();
		$more    = $temp;

		$map = array();

		$start = strpos( $content, '<!-- wp:image ' );
		if ( false !== $start ) {
			$map[ $start ] = 'wp:image';
		}

		$start = strpos( $content, '[caption]' );
		if ( false !== $start ) {
			$map[ $start ] = 'caption';
		}

		$start = strpos( $content, '[caption ' );
		if ( false !== $start ) {
			$map[ $start ] = 'caption';
		}

		$start = strpos( $content, '<img ' );
		if ( false !== $start ) {
			$map[ $start ] = 'image';
		}

		if ( empty( $map ) ) {
			// Retrieve the last image attached to the post
			$attachments = get_posts( array(
				'numberposts'    => 1,
				'post_type'      => 'attachment',
				'post_mime_type' => 'image',
				'post_parent'    => get_the_ID()
			) );

			$attachment = $attachments[0];

			if( isset( $attachment ) && ! post_password_required() ) {
				$image = wp_get_attachment_image_src( $attachment->ID, 'full' );

				$html  = '<a href="' . esc_url( $image[0] ) . '" title="' . the_title_attribute( array( 'echo' => false ) ) . '"  rel="attachment">';
				$html .= wp_get_attachment_image( $attachment->ID, apply_filters( 'post_thumbnail_size', 'post-thumbnail' ) );
				$html .= '</a>';

				if( '' != $attachment->post_excerpt ) {
					$html .= enlightenment_open_tag( $args['caption_tag'], $args['caption_class'] );
					$html .= $attachment->post_excerpt;
					$html .= enlightenment_close_tag( $args['caption_tag'] );
				}
			}
		} else {
			$min  = min( array_keys( $map ) );
			$html = '';

			switch ( $map[ $min ] ) {
				case 'wp:image':
					$start  = $min;
					$end    = strpos( $content, '<!-- /wp:image -->', $start ) + 18;
					$length = $end - $start;
					$block  = substr( $content, $start, $length );
					$block  = parse_blocks( $block );
					$html   = render_block( $block[0] );

					break;

				case 'caption':
					$html = enlightenment_get_first_caption();

					break;

				case 'image':
					$image = enlightenment_get_first_image();

					$html  = sprintf(
						'<a href="%s" title="%s"  rel="attachment">',
						esc_url( $image[0] ),
						the_title_attribute( array( 'echo' => false ) )
					);
					$html .= sprintf(
						'<img src="%s" alt="%s"%s%s />',
						esc_url( $image[0] ),
						the_title_attribute( array( 'echo' => false ) ),
						( $image[1] ? sprintf( ' width="%s"',  esc_attr( $image[1] ) ) : '' ),
						( $image[2] ? sprintf( ' height="%s"', esc_attr( $image[2] ) ) : '' )
					);
					$html .= '</a>';

					break;
			}
		}
	}

	if ( empty( $html ) ) {
		return false;
	}

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'] );
	$output .= $html;
	$output .= enlightenment_close_tag( $args['container'] );

	$output = apply_filters( 'enlightenment_entry_image', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}
	echo $output;
}

function enlightenment_entry_blockquote( $args = null ) {
	$defaults = array(
		'container'       => 'div',
		'container_class' => 'entry-media',
		'echo'            => true,
	);
	$defaults = apply_filters( 'enlightenment_entry_blockquote_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$output = '';

	global $more;
	$temp    = $more;
	$more    = true;
	$content = get_the_content();
	$more    = $temp;

	$map = array();

	$start = strpos( $content, '<!-- wp:quote ' );
	if ( false !== $start ) {
		$map[ $start ] = 'wp:quote';
	}

	$start = strpos( $content, '<!-- wp:pullquote ' );
	if ( false !== $start ) {
		$map[ $start ] = 'wp:pullquote';
	}

	$start = strpos( $content, '<blockquote>' );
	if ( false !== $start ) {
		$map[ $start ] = 'blockquote';
	}

	$start = strpos( $content, '<blockquote ' );
	if ( false !== $start ) {
		$map[ $start ] = 'blockquote';
	}

	if ( empty( $map ) ) {
		return false;
	}

	$min  = min( array_keys( $map ) );
	$html = '';

	switch ( $map[ $min ] ) {
		case 'wp:quote':
			$start  = $min;
			$end    = strpos( $content, '<!-- /wp:quote -->', $start ) + 18;
			$length = $end - $start;
			$block  = substr( $content, $start, $length );
			$block  = parse_blocks( $block );
			$html   = render_block( $block[0] );

			break;

		case 'wp:pullquote':
			$start  = $min;
			$end    = strpos( $content, '<!-- /wp:pullquote -->', $start ) + 22;
			$length = $end - $start;
			$block  = substr( $content, $start, $length );
			$block  = parse_blocks( $block );
			$html   = render_block( $block[0] );
			$html   = apply_filters( 'the_content', $html );

			break;

		case 'blockquote':
			$html = enlightenment_get_first_blockquote();

			break;
	}

	if ( empty( $html ) ) {
		return false;
	}

	$html = str_replace( ' alignleft',   '', $html );
	$html = str_replace( ' alignright',  '', $html );
	$html = str_replace( ' aligncenter', '', $html );
	$html = str_replace( ' alignwide',   '', $html );
	$html = str_replace( ' alignfull',   '', $html );

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'] );
	$output .= $html;
	$output .= enlightenment_close_tag( $args['container'] );

	$output = apply_filters( 'enlightenment_entry_blockquote', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}
	echo $output;
}

function enlightenment_entry_video( $args = null ) {
	$defaults = array(
		'container'       => 'div',
		'container_class' => 'entry-attachment',
		'echo'            => true,
	);
	$defaults = apply_filters( 'enlightenment_entry_video_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	global $more;
	$temp    = $more;
	$more    = true;
	$content = get_the_content();
	$more    = $temp;

	$map = array();

	$start = strpos( $content, '<!-- wp:video ' );
	if ( false !== $start ) {
		$map[ $start ] = 'wp:video';
	}

	$start = strpos( $content, '<!-- wp:core-embed/' );
	if ( false !== $start ) {
		$map[ $start ] = 'wp:core-embed';
	}

	$start = strpos( $content, '[video]' );
	if ( false !== $start ) {
		$map[ $start ] = 'video';
	}

	$start = strpos( $content, '[video ' );
	if ( false !== $start ) {
		$map[ $start ] = 'video';
	}

	$start = strpos( $content, '<iframe ' );
	if ( false !== $start ) {
		$map[ $start ] = 'embed';
	}

	$start = strpos( $content, '<object ' );
	if ( false !== $start ) {
		$map[ $start ] = 'embed';
	}

	$start = strpos( $content, '<embed ' );
	if ( false !== $start ) {
		$map[ $start ] = 'embed';
	}

	if ( empty( $map ) ) {
		return false;
	}

	$min  = min( array_keys( $map ) );
	$html = '';

	switch ( $map[ $min ] ) {
		case 'wp:video':
			$start  = $min;
			$end    = strpos( $content, '<!-- /wp:video -->', $start ) + 18;
			$length = $end - $start;
			$block  = substr( $content, $start, $length );
			$block  = parse_blocks( $block );
			$html   = render_block( $block[0] );

			break;

		case 'wp:core-embed':
			$start  = $min;
			$end    = strpos( $content, '<!-- /wp:core-embed/', $start );
			$end    = strpos( $content, ' -->', $end ) + 4;
			$length = $end - $start;
			$block  = substr( $content, $start, $length );
			$block  = parse_blocks( $block );
			$html   = render_block( $block[0] );
			$html   = apply_filters( 'the_content', $html );

			break;

		case 'video':
			preg_match( '/\[(\[?)(video)(?![\w-])([^\]\/]*(?:\/(?!\])[^\]\/]*)*?)(?:(\/)\]|\](?:([^\[]*+(?:\[(?!\/\2\])[^\[]*+)*+)\[\/\2\])?)(\]?)/s', $content, $matches );

			$html = do_shortcode( $matches[0] );

			break;

		case 'embed':
			$html = enlightenment_get_first_embed();

			break;
	}

	if ( empty( $html ) ) {
		return false;
	}

	$html = str_replace( ' alignleft',   '', $html );
	$html = str_replace( ' alignright',  '', $html );
	$html = str_replace( ' aligncenter', '', $html );
	$html = str_replace( ' alignwide',   '', $html );
	$html = str_replace( ' alignfull',   '', $html );

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'] );
	$output .= $html;
	$output .= enlightenment_close_tag( $args['container'] );

	$output = apply_filters( 'enlightenment_entry_video', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}
	echo $output;
}

function enlightenment_entry_audio( $args = null ) {
	$defaults = array(
		'container'       => 'div',
		'container_class' => 'entry-attachment',
		'echo'            => true,
	);
	$defaults = apply_filters( 'enlightenment_entry_audio_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	global $more;
	$temp    = $more;
	$more    = true;
	$content = get_the_content();
	$more    = $temp;

	$map = array();

	$start = strpos( $content, '<!-- wp:audio ' );
	if ( false !== $start ) {
		$map[ $start ] = 'wp:audio';
	}

	$start = strpos( $content, '<!-- wp:embed ' );
	if ( false !== $start ) {
		$map[ $start ] = 'wp:embed';
	}

	$start = strpos( $content, '<!-- wp:core-embed/' );
	if ( false !== $start ) {
		$map[ $start ] = 'wp:core-embed';
	}

	$start = strpos( $content, '[audio]' );
	if ( false !== $start ) {
		$map[ $start ] = 'audio';
	}

	$start = strpos( $content, '[audio ' );
	if ( false !== $start ) {
		$map[ $start ] = 'audio';
	}

	$start = strpos( $content, '<iframe ' );
	if ( false !== $start ) {
		$map[ $start ] = 'embed';
	}

	$start = strpos( $content, '<object ' );
	if ( false !== $start ) {
		$map[ $start ] = 'embed';
	}

	$start = strpos( $content, '<embed ' );
	if ( false !== $start ) {
		$map[ $start ] = 'embed';
	}

	if ( empty( $map ) ) {
		return false;
	}

	$min  = min( array_keys( $map ) );
	$html = '';

	switch ( $map[ $min ] ) {
		case 'wp:audio':
			$start  = $min;
			$end    = strpos( $content, '<!-- /wp:audio -->', $start ) + 18;
			$length = $end - $start;
			$block  = substr( $content, $start, $length );
			$block  = parse_blocks( $block );
			$html   = render_block( $block[0] );

			break;

		case 'wp:embed':
			$start  = $min;
			$end    = strpos( $content, '<!-- /wp:embed -->', $start );
			$length = $end - $start;
			$block  = substr( $content, $start, $length );
			$block  = parse_blocks( $block );
			$html   = render_block( $block[0] );
			$html   = apply_filters( 'the_content', $html );

			break;

		case 'wp:core-embed':
			$start  = $min;
			$end    = strpos( $content, '<!-- /wp:core-embed/', $start );
			$end    = strpos( $content, ' -->', $end ) + 4;
			$length = $end - $start;
			$block  = substr( $content, $start, $length );
			$block  = parse_blocks( $block );
			$html   = render_block( $block[0] );
			$html   = apply_filters( 'the_content', $html );

			break;

		case 'audio':
			preg_match( '/\[(\[?)(audio)(?![\w-])([^\]\/]*(?:\/(?!\])[^\]\/]*)*?)(?:(\/)\]|\](?:([^\[]*+(?:\[(?!\/\2\])[^\[]*+)*+)\[\/\2\])?)(\]?)/s', $content, $matches );

			$html = do_shortcode( $matches[0] );

			break;

		case 'embed':
			$html = enlightenment_get_first_embed();

			break;
	}

	if ( empty( $html ) ) {
		return false;
	}

	$html = str_replace( ' alignleft',   '', $html );
	$html = str_replace( ' alignright',  '', $html );
	$html = str_replace( ' aligncenter', '', $html );
	$html = str_replace( ' alignwide',   '', $html );
	$html = str_replace( ' alignfull',   '', $html );

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'] );
	$output .= $html;
	$output .= enlightenment_close_tag( $args['container'] );

	$output = apply_filters( 'enlightenment_entry_audio', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}
	echo $output;
}
