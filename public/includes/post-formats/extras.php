<?php

function enlightenment_get_first_caption() {
	global $more;
	$more    = true;
	$content = apply_filters( 'the_content', get_the_content( '', true ) );
	$more    = false;

	if( '' != $content ) {
		$document = new DOMDocument();
		libxml_use_internal_errors( true );
		$document->loadHTML( $content );
		libxml_clear_errors();

		$classname = 'wp-caption';
		$finder    = new DomXPath( $document );
		$images    = $finder->query( "//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]" );

		for( $i = 0; $i < $images->length; $i++ ) {
			$image    = $images->item($i);
			$document = new DOMDocument();

			$document->appendChild( $document->importNode( $image, true ) );

			return $document->saveHTML();
		}
	}

	return false;
}

function enlightenment_get_first_image() {
	global $more;
	$more    = true;
	$content = apply_filters( 'the_content', get_the_content( '', true ) );
	$more    = false;

	if( '' != $content ) {
		$document = new DOMDocument();
		libxml_use_internal_errors( true );
		$document->loadHTML( $content );
		libxml_clear_errors();

		$images   = $document->getElementsByTagName( 'img' );

		if( $images->length ) {
			$image  = $images->item( $images->length - 1 );
			$src    = $image->getAttribute( 'src' );
			$width  = ( $image->hasAttribute( 'width' ) ? $image->getAttribute( 'width' ) : false );
			$height = ( $image->hasAttribute( 'height' ) ? $image->getAttribute( 'height' ) : false );

			return array( $src, $width, $height );
		}
	}

	return false;
}

function enlightenment_get_first_embed() {
	global $more;
	$temp    = $more;
	$more    = true;
	$content = apply_filters( 'the_content', get_the_content( '', true ) );
	$more    = $temp;

	if( '' != $content ) {
		$document = new DOMDocument();
		libxml_use_internal_errors( true );
		$document->loadHTML( $content );
		libxml_clear_errors();

		$iframes  = $document->getElementsByTagName( 'iframe' );
		$objects  = $document->getElementsByTagName( 'object' );
		$embeds   = $document->getElementsByTagName( 'embed' );
		$document = new DOMDocument();

		if( $iframes->length ) {
			$iframe = $iframes->item( $iframes->length - 1 );
			$document->appendChild( $document->importNode( $iframe, true ) );
		} elseif( $objects->length ) {
			$object = $objects->item( $objects->length - 1 );
			$document->appendChild( $document->importNode( $object, true ) );
		} elseif( $embeds->length ) {
			$embed = $embeds->item( $embeds->length - 1 );
			$document->appendChild( $document->importNode( $embed, true ) );
		}

		return $document->saveHTML();
	}

	return false;
}

function enlightenment_get_first_blockquote() {
	global $more;
	$temp    = $more;
	$more    = true;
	$content = apply_filters( 'the_content', get_the_content( '', true ) );
	$more    = $temp;

	if( '' != $content ) {
		$document = new DOMDocument();
		libxml_use_internal_errors( true );
		$document->loadHTML( mb_convert_encoding( $content, 'html-entities', 'utf-8' ) );
		libxml_clear_errors();

		$blockquotes = $document->getElementsByTagName( 'blockquote' );

		if( ! empty( $blockquotes ) ) {
			$blockquote = $blockquotes->item(0);
			$document   = new DOMDocument();
			$document->appendChild( $document->importNode( $blockquote, true ) );

			return $document->saveHTML();
		}
	}

	return false;
}

function enlightenment_entry_link( $src ) {
	if( ! has_post_format( 'link' ) ) {
		return $src;
	}

	global $more;
	$more    = true;
	$content = apply_filters( 'the_content', get_the_content( '', true ) );
	$more    = false;

	if( '' != $content ) {
		$document = new DOMDocument();
		libxml_use_internal_errors( true );
		$document->loadHTML( $content );
		libxml_clear_errors();

		$links = $document->getElementsByTagName( 'a' );

		for( $i = 0; $i < $links->length; $i++ ) {
			$link     = $links->item($i);
			$document = new DOMDocument();
			$document->appendChild( $document->importNode( $link, true ) );

			$src = $link->getAttribute('href');
		}
	}

	return $src;
}
add_filter( 'the_permalink', 'enlightenment_entry_link' );
