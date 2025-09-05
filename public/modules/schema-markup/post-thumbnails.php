<?php

function enlightenment_post_thumbnail_schema_markup( $html ) {
	if( has_post_thumbnail() )
		$html = str_replace( '<img ', '<img itemprop="image" ', $html );
	return $html;
}
add_filter( 'post_thumbnail_html', 'enlightenment_post_thumbnail_schema_markup' );
