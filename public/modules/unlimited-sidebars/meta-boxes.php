<?php

function enlightenment_unlimited_sidebars_meta_boxes() {
	if ( WP_Screen::get()->is_block_editor() ) {
        return;
    }

	$post_types = array_merge(
        array(
            'page' => 'page',
        ),
        get_post_types( array(
            'publicly_queryable' => true,
        ) )
    );

	unset( $post_types['attachment'] );
	unset( $post_types['e-landing-page'] );
	unset( $post_types['e-floating-buttons'] );

	foreach ( $post_types as $post_type ) {
		add_meta_box(
			'enlightenment_unlimited_sidebars',
			__( 'Sidebars', 'enlightenment' ),
			'enlightenment_unlimited_sidebars_form',
			$post_type,
			'side',
			'low'
		);
	}
}
add_action( 'add_meta_boxes', 'enlightenment_unlimited_sidebars_meta_boxes' );

function enlightenment_unlimited_sidebars_form( $post ) {
	if ( ! isset( $_GET['post'] ) ) {
		_e( 'Please save this post as Draft first to use Unlimited Sidebars.', 'enlightenment' );

		return;
	}

	global $wp_registered_sidebars;

	$post_name   = strtolower( get_post_type_object( $post->post_type )->labels->singular_name );
	$post_meta   = get_post_meta( $post->ID, '_enlightenment_sidebar_locations', true );
	$post_type   = get_post_type( $post );
	$settings    = enlightenment_sidebar_locations();
	$locations   = $settings[ $post_type ];
	$select_args = array(
		'multiple' => false,
		'options'  => array( '' => '&mdash;' ),
	);

	foreach ( $wp_registered_sidebars as $sidebar => $atts ) {
		$select_args['options'][ $sidebar ] = $atts['name'];
	}

	wp_nonce_field( 'enlightenment_unlimited_sidebars_form', 'enlightenment_unlimited_sidebars_form_nonce' );
	?>

	<div class="default-sidebars">
		<?php
		enlightenment_checkbox( array(
			'name'    => 'enlightenment_default_sidebar_locations',
			'label'   => sprintf( __( 'Use global sidebar locations for %1$s', 'enlightenment' ), $post_name ),
			'checked' => ( '' == $post_meta ),
		) );
		?>
	</div>

	<div class="sidebar-locations<?php echo empty( $post_meta ) ? ' hidden' : ''; ?>">

		<?php
		foreach ( $locations as $location => $sidebar ) :
			if ( isset( $post_meta[ $location ] ) ) {
				$sidebar['sidebar'] = $post_meta[ $location ];
			}

			$select_args['name']  = sprintf( 'enlightenment_sidebar_locations[%s]', $location );
			$select_args['id']    = str_replace( array( '[', ']' ), array( '-', '' ), $select_args['name'] );
			$select_args['value'] = $sidebar['sidebar'];
			?>

			<div class="sidebar-location">
				<p class="post-attributes-label-wrapper">
					<label class="post-attributes-label" for="<?php echo esc_attr( $select_args['id'] ); ?>">
						<?php echo esc_html( $sidebar['name'] ); ?>
					</label>
				</p>

				<?php enlightenment_select_box( $select_args ); ?>
			</div>

			<?php
		endforeach;
		?>

	</div>

	<?php
}

function enlightenment_unlimited_sidebars_form_save_postdata( $post_id ) {
	if ( ! isset( $_POST['enlightenment_sidebar_locations'] ) ) {
		return;
	}

	$nonce = $_POST['enlightenment_unlimited_sidebars_form_nonce'];

	if ( ! wp_verify_nonce( $nonce, 'enlightenment_unlimited_sidebars_form' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	$post = get_post( $post_id );

	if ( ! current_user_can( get_post_type_object( $post->post_type )->cap->edit_post, $post_id ) ) {
		return;
	}

	if ( isset( $_POST['enlightenment_default_sidebar_locations'] ) && '1' == $_POST['enlightenment_default_sidebar_locations'] ) {
		delete_post_meta( $post_id, '_enlightenment_sidebar_locations' );
		return;
	}

	global $wp_registered_sidebars;

	$input     = $_POST['enlightenment_sidebar_locations'];
	$locations = enlightenment_sidebar_locations();
	$post_type = get_post_type( $post_id );

	foreach ( $input as $location => $sidebar ) {
		if ( '' == $location ) {
			continue;
		}

		if ( ! isset( $locations[ $post_type ] ) ) {
			$locations[ $post_type ] = array();
		}

		if ( 'revision' != $post->post_type && ! array_key_exists( $location, $locations[ $post_type ] ) ) {
			unset( $input[ $location ] );
		}

		if ( ! array_key_exists( $sidebar, $wp_registered_sidebars ) ) {
			unset( $input[ $location ] );
		}
	}

	update_post_meta( $post_id, '_enlightenment_sidebar_locations', $input );
}
add_action( 'save_post', 'enlightenment_unlimited_sidebars_form_save_postdata' );
