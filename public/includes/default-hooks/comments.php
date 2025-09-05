<?php

add_action( 'enlightenment_comments', 'enlightenment_list_comments' );

add_action( 'enlightenment_before_comments_list', 'enlightenment_comments_number' );
add_action( 'enlightenment_before_comments_list', 'enlightenment_comments_nav' );

add_action( 'enlightenment_after_comments_list', 'enlightenment_comments_nav' );

add_action( 'enlightenment_after_comments', 'enlightenment_comment_form' );

add_action( 'enlightenment_comment_header', 'enlightenment_comment_author_avatar', 10, 2 );
add_action( 'enlightenment_comment_header', 'enlightenment_comment_author' );
add_action( 'enlightenment_comment_header', 'enlightenment_comment_meta' );
add_action( 'enlightenment_comment_header', 'enlightenment_comment_awaiting_moderation' );

add_action( 'enlightenment_comment_meta', 'enlightenment_comment_time' );

add_action( 'enlightenment_comment_content', 'comment_text' );

add_action( 'enlightenment_comment_footer', 'enlightenment_comment_reply_link', 10, 2 );

add_action( 'enlightenment_comments_require_password', 'enlightenment_comments_password_notice' );

add_action( 'comment_form_comments_closed', 'enlightenment_comments_closed_notice' );
