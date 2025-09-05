<?php

function enlightenment_bootstrap_widget_archives( $output ) {
    return str_replace( 'name="archive-dropdown"', 'name="archive-dropdown" class="form-control"', $output );
}
add_filter( 'enlightenment_widget_archives', 'enlightenment_bootstrap_widget_archives' );

function enlightenment_bootstrap_widget_categories( $output ) {
    return str_replace( "class='postform'", 'class="postform form-control"', $output );
}
add_filter( 'enlightenment_widget_categories', 'enlightenment_bootstrap_widget_categories' );
