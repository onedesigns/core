<?php

function enlightenment_comment_form_fields( $fields ) {
	$commenter = wp_get_current_commenter();
	$req = get_option( 'require_name_email' );
	$aria_req = ( $req ? " aria-required='true'" : '' );
	$args = array(
		'author_container' => 'p',
		'author_container_class' => 'comment-form-author',
		'author_label_class' => '',
		'author_label_text' => __( 'Name', 'enlightenment' ),
		'author_class' => '',
		'author_id' => 'author',
		'author_size' => 30,
		'author_extra_atts' => $aria_req,
		'before_author_label' => '',
		'after_author_label' => $req ? ' <span class="required">*</span>' : '',
		'email_container' => 'p',
		'email_container_class' => 'comment-form-email',
		'email_label_class' => '',
		'email_label_text' => __( 'Email', 'enlightenment' ),
		'email_class' => '',
		'email_id' => 'email',
		'email_size' => 30,
		'email_extra_atts' => $aria_req,
		'before_email_label' => '',
		'after_email_label' => $req ? ' <span class="required">*</span>' : '',
		'url_container' => 'p',
		'url_container_class' => 'comment-form-url',
		'url_label_class' => '',
		'url_label_text' => __( 'Website', 'enlightenment' ),
		'url_class' => '',
		'url_id' => 'url',
		'url_size' => 30,
		'url_extra_atts' => '',
		'before_url_label' => '',
		'after_url_label' => '',
	);
	$args = apply_filters( 'enlightenment_comment_form_fields_args', $args );
	$fields = array(
		'author' => enlightenment_open_tag( $args['author_container'], $args['author_container_class'] ) .
						'<label' . ( '' != $args['author_id'] ? ' for="' . $args['author_id'] . '"' : '' ) . enlightenment_class( $args['author_label_class'] ) . '>' . $args['before_author_label'] . esc_html( $args['author_label_text'] ) . $args['after_author_label'] . '</label> ' .
						'<input' . enlightenment_id( $args['author_id'] ) . enlightenment_class( $args['author_class'] ) . ' name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="' . intval( $args['author_size'] ) . '"' . enlightenment_extra_atts( $args['author_extra_atts'] ) . ' />' .
					enlightenment_close_tag( $args['author_container'] ),
		'email' => enlightenment_open_tag( $args['email_container'], $args['email_container_class'] ) .
						'<label' . ( '' != $args['email_id'] ? ' for="' . $args['email_id'] . '"' : '' ) . enlightenment_class( $args['email_label_class'] ) . '>' . $args['before_email_label'] . esc_html( $args['email_label_text'] ) . $args['after_email_label'] . '</label> ' .
						'<input' . enlightenment_id( $args['email_id'] ) . enlightenment_class( $args['email_class'] ) . ' name="email" type="text" value="' . esc_attr( $commenter['comment_author_email'] ) . '" size="' . intval( $args['email_size'] ) . '"' . enlightenment_extra_atts( $args['email_extra_atts'] ) . ' />' .
					enlightenment_close_tag( $args['email_container'] ),
		'url' => enlightenment_open_tag( $args['url_container'], $args['url_container_class'] ) .
						'<label' . ( '' != $args['url_id'] ? ' for="' . $args['url_id'] . '"' : '' ) . enlightenment_class( $args['url_label_class'] ) . '>' . $args['before_url_label'] . esc_html( $args['url_label_text'] ) . $args['after_url_label'] . '</label> ' .
						'<input' . enlightenment_id( $args['url_id'] ) . enlightenment_class( $args['url_class'] ) . ' name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="' . intval( $args['url_size'] ) . '"' . enlightenment_extra_atts( $args['url_extra_atts'] ) . ' />' .
					enlightenment_close_tag( $args['url_container'] ),
	);

	return apply_filters( 'enlightenment_comment_form_fields', $fields, $commenter, $req );
}
add_filter( 'comment_form_default_fields', 'enlightenment_comment_form_fields' );

function enlightenment_comment_form_defaults( $defaults ) {
	$args = array(
		'container' => 'p',
		'container_class' => 'comment-form-comment',
		'label_class' => '',
		'label_text' => _x( 'Comment', 'noun', 'enlightenment' ),
		'textarea_class' => '',
		'textarea_id' => 'comment',
		'textarea_extra_atts' => ' aria-required="true"',
		'cols' => 45,
		'rows' => 8,
		'before_label' => '',
		'after_label' => '',
	);
	$args = apply_filters( 'enlightenment_comment_form_defaults_args', $args );
	$defaults['comment_field'] = enlightenment_open_tag( $args['container'], $args['container_class'] ) .
									'<label' . ( '' != $args['textarea_id'] ? ' for="' . esc_attr( $args['textarea_id'] ) . '"' : '' ) . enlightenment_class( $args['label_class'] ) . '>' . $args['before_label'] . esc_html( $args['label_text'] ) . $args['after_label'] . '</label>' .
									'<textarea' . enlightenment_id( $args['textarea_id'] ) . enlightenment_class( $args['textarea_class'] ) . ' name="comment" cols="' . intval( $args['cols'] ) . '" rows="' . intval( $args['rows'] ) . '" ' . enlightenment_extra_atts( $args['textarea_extra_atts'] ) . '></textarea>' .
								 enlightenment_close_tag( $args['container'] );
	return apply_filters( 'enlightenment_comment_form_defaults', $defaults );
}
add_filter( 'comment_form_defaults', 'enlightenment_comment_form_defaults' );
