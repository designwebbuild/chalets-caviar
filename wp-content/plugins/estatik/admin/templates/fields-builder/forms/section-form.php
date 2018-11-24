<?php

/**
 * @file
 * Create / Update entity field form.
 *
 * @var array $tab
 */

$instance = null;

if ( $instance = Es_FBuilder_Helper::get_edit_section() ) {
	$page_title = __( 'Update section', 'es-plugin' );
	$btn_title = __( 'Update', 'es-plugin' );
} else {
	$page_title = __( 'Create section', 'es-plugin' );
	$btn_title = __( 'Create', 'es-plugin' );
} ?>

<h1>
	<?php echo $page_title; ?>

	<?php if ( ! empty( $instance ) ) : ?>
		<a class="es-button es-button-green es-button-add-field" href="<?php echo 'admin.php?page=es_fbuilder#es-es-section-tab'; ?>"><?php _e( 'Add new', 'es-plugin' ); ?></a>
	<?php endif; ?>
</h1>

<form action="" method="POST">

	<?php

	// Field name input.
	echo Es_Html_Helper::render_settings_field( __( 'Section Name', 'es-plugin' ), 'fbuilder[label]', 'text', array(
		'required' => 'required',
		'value' => Es_FBuilder_Helper::get_settings_value( $instance, 'label' ),
	) );

	// Field name input.
	echo Es_Html_Helper::render_settings_field( __( 'Show tab', 'es-plugin' ), 'fbuilder[show_tab]', 'checkbox', array(
		'class' => 'es-switch-input',
		'value' => 1,
		'checked' => true == (bool) Es_FBuilder_Helper::get_settings_value( $instance, 'show_tab' ),
	) );

	if ( ! empty( $instance['id'] ) ) : ?>
		<input type="hidden" name="fbuilder[id]" value="<?php echo $instance['id']; ?>"/>
	<?php endif; ?>

	<input type="submit" style="margin-top: 10px;" class="es-button <?php echo $instance ? 'es-button-blue' : 'es-button-green'; ?>" value="<?php echo $btn_title; ?>"/>

	<?php wp_nonce_field( 'es_fbuilder_save_section', 'es_fbuilder_save_section' ); ?>

</form>
