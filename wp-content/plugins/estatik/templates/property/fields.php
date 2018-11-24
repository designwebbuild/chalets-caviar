<div class="es-property-fields">

    <?php do_action( 'es_single_share' ); ?>

    <ul>
        <?php if ( $fields = Es_Property_Single_Page::get_single_fields_data() ) : ?>
            <?php foreach ( $fields as $field ) : ?>
                <?php if ( ! empty( $field[ key( $field ) ] ) || ( isset( $field[ key( $field ) ] ) && strlen( $field[ key( $field ) ] ) ) ) : ?>
                    <li><strong><?php echo key( $field ); ?>: </strong>
	                    <?php echo is_array( $field[ key( $field ) ] ) ? implode( ', ', $field[ key( $field ) ] ) : $field[ key( $field ) ]; ?>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>
</div>
