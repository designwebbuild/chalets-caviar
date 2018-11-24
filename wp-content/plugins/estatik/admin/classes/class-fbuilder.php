<?php

/**
 * Class Es_FBuilder
 */
class Es_FBuilder extends Es_Object {

	/**
	 * @inheritdoc
	 */
	public function filters() {
		add_filter( 'es_single_property_tabs', array( $this, 'get_single_property_tabs' ), 10, 1 );
		add_filter( 'es_single_tabbed_content_after', array( $this, 'single_tabbed_fields' ), 10, 1 );
		add_filter( 'es_single_fields_data', array( $this, 'single_fields_data' ), 10, 1 );
		add_filter( 'es_property_sections', array( $this, 'property_sections' ), 10, 1 );
		add_filter( 'es_save_property_field_value', array( $this, 'save_field_value_format' ), 10, 3 );
		add_filter( 'es_get_entity_field_value', array( $this, 'get_field_value_formatted' ), 10, 3 );
		add_filter( 'es_property_get_fields', array( $this, 'remove_base_fields' ), 10, 1 );
		add_filter( 'es_property_metabox_tabs', array( $this, 'property_metabox_tabs' ), 10, 1 );
	}

	/**
	 * @param $fields
	 *
	 * @return mixed
	 */
	public function remove_base_fields( $fields ) {
		global $es_settings;

		$is_fbuilder_page = ! empty( $_GET['page'] ) && $_GET['page'] == 'es_fbuilder' && is_admin();

		if ( ! $is_fbuilder_page && $es_settings->property_removed_fields && is_array( $es_settings->property_removed_fields ) ) {
			foreach ( $es_settings->property_removed_fields as $field ) {
				unset( $fields[ $field ] );
			}
		}

		return $fields;
	}

	/**
	 * @return array
	 */
	public function property_sections()
	{
		return Es_FBuilder_Helper::get_sections( 'property' );
	}

	/**
	 * @param $tabs
	 *
	 * @return array
	 */
	public function get_single_property_tabs( $tabs ) {

		global $es_settings;

		$sections = Es_FBuilder_Helper::get_sections( 'property' );
		$tabs = array();

		foreach ( $sections as $id => $section ) {
			if ( empty( $section['show_tab'] ) ) continue;
			$tabs[ $id ] = $section['label'];
		}

		return $es_settings->hide_property_top_bar ? array() : $tabs;
	}

	/**
	 * Render section fields.
	 * @param $section_id
	 */
	public function single_tabbed_fields( $section_id ) {
		$fields = Es_FBuilder_Helper::get_entity_fields( 'property', $section_id );
		if ( $data = Es_Property_Single_Page::get_fields_render_data( $fields ) ) : ?>
			<ul class="es-property-single-fields">
				<?php foreach ( $data as $field ) : ?>
					<?php if ( ! empty( $field[ key( $field ) ]['markup'] ) ) : ?>
						<?php echo $field[ key( $field ) ]['markup']; ?>
					<?php elseif ( is_array( $field ) ): ?>
						<li><strong><?php echo key( $field ); ?>: </strong><?php echo $field[ key( $field ) ]; ?></li>
					<?php endif; ?>
				<?php endforeach; ?>
			</ul>
		<?php endif;
	}

	/**
	 * Filter field value output.
	 *
	 * @param $value
	 * @param $field
	 * @param $entity
	 * @return false|string
	 */
	public function get_field_value_formatted( $value, $field, $entity ) {
		$p = new Es_Property();
		if ( $p->get_entity_prefix() == $entity ) {
			$field = Es_Property::get_field_info( $field );

			global $es_settings;

			if ( ! empty( $field['type'] ) ) {
				if ( ! empty( $value ) && ( 'datetime-local' == $field['type'] || 'date' == $field['type'] ) ) {
					$value = date( 'datetime-local' == $field['type'] ? $es_settings->date_format . ' H:i' : $es_settings->date_format, $value );
				}
			}
		}

		return $value;
	}

	/**
	 * Filter saving property value.
	 *
	 * @param $value
	 * @param $field
	 * @return mixed
	 */
	public function save_field_value_format( $value, $field ) {

		if ( ! empty( $value ) ) {
			$field_info = Es_Property::get_field_info( $field );

			if ( ! empty( $field_info['type'] ) && in_array( $field_info['type'], array( 'date', 'datetime-local' ) ) ) {
				global $es_settings;
				$format = $es_settings->date_format;
				$format .= $field_info['type'] == 'datetime-local' ? ' H:i' : '';

				$value = DateTime::createFromFormat( $format, $value );
				$value = $value instanceof DateTime ? $value->getTimestamp() : null;
			}
		}

		return $value;
	}

	/**
	 * Filter property fields.
	 *
	 * @param $fields
	 * @return array
	 */
	/**
	 * Filter property fields.
	 *
	 * @param $fields
	 * @return array
	 */
	public function single_fields_data( $fields ) {
		global $es_property;
		$custom = $es_property->get_custom_data();
		$data = array();

		$fields = Es_FBuilder_Helper::get_entity_fields( 'property', 'es-info' );

		if ( $fields ) {
			foreach ( $fields as $key => $field ) {
				if ( ! empty( $field['section'] ) && $field['section'] == 'es-info' ) {
					$data[] = array( __( $field['label'], 'es-plugin' ) => ! empty( $field['formatter'] ) ?
						es_get_the_formatted_field( $key, $field['formatter'] ) : es_get_the_property_field( $key ) );
				}
			}
		}

		// Include custom fields.
		if ( ! empty( $custom ) ) {
			foreach ( $custom as $value ) {
				$data[] = array( __( key( $value ), 'es-plugin' ) => __( reset($value), 'es-plugin' ) );
			}
		}

		return apply_filters( 'es_fbuilder_single_fields_data', $data );
	}

	/**
	 * @param $tabs
	 *
	 * @return array
	 */
	public function property_metabox_tabs( $tabs ) {
		$sections = Es_FBuilder_Helper::get_sections( 'property' );

		if ( $sections ) {
			if ( ! empty( $sections ) ) {
				$tabs = array_merge( $sections, $tabs );
			}
		}

		unset( $tabs['es-agent'] );

		return $tabs;
	}
}
