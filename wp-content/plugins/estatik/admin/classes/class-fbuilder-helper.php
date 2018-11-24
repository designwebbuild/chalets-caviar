<?php

/**
 * Class Es_FBuilderRepository
 */
class Es_FBuilder_Helper
{
	/**
	 * @return mixed
	 */
	public static function get_field_types()
	{
		return apply_filters( 'es_fbuilder_field_types', array(
			'text' => __( 'Text', 'es-plugin' ),
			'number' => __( 'Number', 'es-plugin' ),
			'price' => __( 'Price', 'es-plugin' ),
			'area' => __( 'Area', 'es-plugin' ),
			'file' => __( 'File', 'es-plugin' ),
			'list' => __( 'Select', 'es-plugin' ),
			'textarea' => __( 'Text area', 'es-plugin' ),
			'date' => __( 'Date', 'es-plugin' ),
			'datetime-local' => __( 'Date time', 'es-plugin' ),
			'email' => __( 'Email', 'es-plugin' ),
			'tel' => __( 'Tel', 'es-plugin' ),
			'url' => __( 'Url', 'es-plugin' ),
		) );
	}

	/**
	 * Return field edit link.
	 *
	 * @param $id
	 *    Field ID.
	 * @return string
	 *    Return field edit link.
	 */
	public static function get_field_edit_link( $id ) {
		return add_query_arg( array(
			'action' => 'es-fbuilder-edit-field',
			'id' => $id,
		) );
	}

	/**
	 * Return field edit link.
	 *
	 * @param $id
	 *    Field ID.
	 * @return string
	 *    Return field edit link.
	 */
	public static function get_section_edit_link( $id ) {
		return add_query_arg( array(
				'action' => 'es-fbuilder-edit-section',
				'id' => $id,
			) ) . '#es-es-section-tab';
	}

	/**
	 * Restore standard field link.
	 *
	 * @param $field_id
	 *
	 * @return string
	 */
	public static function get_field_restore_link( $field_id ) {
		return add_query_arg( array(
			'action' => 'es-fbuilder-restore-field',
			'id' => $field_id,
			'nonce' => wp_create_nonce( 'es-fbuilder-restore-field' )
		) );
	}

	/**
	 * Return remove field link.
	 *
	 * @param $id
	 * @return string
	 */
	public static function get_field_delete_link( $id, $is_base_field = false )
	{
		if ( $is_base_field ) {
			return add_query_arg( array(
				'action' => 'es-fbuilder-remove-field',
				'id' => $id,
				'base_field' => true,
				'nonce' => wp_create_nonce( 'es-fbuilder-remove-field' )
			) );
		}

		return add_query_arg( array(
			'action' => 'es-fbuilder-remove-field',
			'id' => $id,
			'nonce' => wp_create_nonce( 'es-fbuilder-remove-field' )
		) );
	}

	/**
	 * Return remove field link.
	 *
	 * @param $id
	 * @return string
	 */
	public static function get_section_delete_link( $id )
	{
		return add_query_arg( array(
			'action' => 'es-fbuilder-remove-section',
			'id' => $id,
			'nonce' => wp_create_nonce( 'es-fbuilder-remove-section' )
		) );
	}

	/**
	 * Check if edit page is active.
	 *
	 * @return bool
	 */
	public static function is_edit_action() {
		return self::get_edit_field() ? true : false;
	}

	/**
	 * @return array|null|object
	 */
	public static function get_edit_field()
	{
		if ( ! empty( $_GET['id'] ) && ! empty( $_GET['action'] ) && $_GET['action'] == 'es-fbuilder-edit-field' ) {
			$field =  self::get_field( $_GET['id'] );

			$field['type'] = ! empty( $field['formatter'] ) ? $field['formatter'] : $field['type'];

			return $field;
		}

		return null;
	}

	/**
	 * @return array|null|object
	 */
	public static function get_edit_section()
	{
		if ( ! empty( $_GET['id'] ) && ! empty( $_GET['action'] ) && $_GET['action'] == 'es-fbuilder-edit-section' ) {
			$field =  self::get_section( $_GET['id'] );

			return $field;
		}

		return null;
	}

	/**
	 * @param $entity string
	 * @param $section string
	 *    Section machine name.
	 *
	 * @return mixed
	 */
	public static function get_entity_fields( $entity, $section = null, $enable_order = true )
	{
		$result = array();
		$fields = array();

		$callback = apply_filters( 'es_fbuilder_get_entity_section_fields_callback', 'es_get_' . $entity, $entity );

		if ( function_exists( $callback ) ) {
			/** @var Es_Entity $entity */
			$entity = $callback( null );
			$fields = $entity::get_fields();
		}

		if ( $section ) {
			global $wpdb;

			$order = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}fbuilder_fields_order WHERE section_machine_name='{$section}' ORDER BY id ASC", ARRAY_A );

			if ( $order && $enable_order ) {
				foreach ( $order as $data ) {
					$machine_name = $data['field_machine_name'];
					if ( isset( $fields[ $machine_name ] ) && $data['section_machine_name'] == $section ) {
						$result[ $machine_name ] = $fields[ $machine_name ];
						$result[ $machine_name ]['section'] = $data['section_machine_name'];
					}
				}
			}

			$result = ! $result ? $fields : $result;

			foreach ( $result as $key => $field ) {
				if ( empty( $field['section'] ) || ( ! empty( $field['section'] ) && $field['section'] != $section ) ) {
					unset( $result[ $key ] );
				}
			}
		}

		$result = empty( $result ) && empty( $section ) ? $fields : $result;

		return apply_filters( 'es_fbuilder_get_entity_section_fields', $result, $entity );
	}


	/**
	 * @param $entity
	 * @return array|null|object
	 */
	public static function get_fields( $entity ) {
		global $wpdb, $es_settings;

		$fields = $wpdb->get_results( "SELECT * FROM " . $wpdb->prefix . "fbuilder_fields WHERE entity='$entity'" );
		$result = array();

		if ( $fields ) {
			foreach ( $fields as $key => $field ) {
				$field = (array) $field;
				$field['fbuilder'] = true;
				$field['options'] = ! empty( $field['options'] ) ? unserialize( $field['options'] ) : array();
				$field['values'] = ! empty( $field['values'] ) ? unserialize( $field['values'] ) : array();

				if ( $field['formatter'] == 'area' ) {
					$field['options']['step'] = '0.1';
					$field['units'] = $field['machine_name'] .'_unit';
					$result[ $field['machine_name'] .'_unit' ] = array(
						'type' => 'list',
						'values' => $es_settings::get_setting_values( 'unit' ),
						'template' => true,
						'label' => false,
						'skip_search' => true,
						'default_value' => $es_settings->unit,
					);
				}

				$result[ $field['machine_name'] ] = $field;
			}
		}

		return apply_filters( 'es_fbuilder_get_entity_fields', $result, $entity );
	}

	/**
	 * @param $type
	 *    Field type string.
	 * @return string
	 *    Return template name for input type.
	 */
	public static function get_field_options_template( $type )
	{
		$templates = apply_filters( 'es_fbuilder_field_types_templates', array(
			'text' => 'default',
			'number' => 'number',
			'price' => 'number',
			'area' => 'range',
			'file' => 'file',
			'select' => 'multiple',
			'list' => 'multiple',
			'textarea' => null,
			'date' => 'range',
//            'datetime' => 'range',
			'email' => 'default',
			'tel' => 'default',
			'url' => 'default',
		) );

		return ! empty( $templates[ $type ] ) ? $templates[ $type ] : null;
	}

	/**
	 * Return settings field value.
	 *
	 * @param $instance
	 * @param $key
	 * @param null $default
	 * @return null
	 */
	public static function get_settings_value( $instance, $key, $default = null )
	{
		return apply_filters( 'es_fbuilder_get_field_value', ! empty( $instance[ $key ] ) ? $instance[ $key ] : $default, $key, $instance );
	}

	/**
	 * @param $instance
	 * @param $key
	 * @param null $default
	 * @return mixed
	 */
	public static function get_options_value( $instance, $key, $default = null )
	{
		return apply_filters( 'es_fbuilder_get_field_option', ! empty( $instance['options'][ $key ] ) ? $instance['options'][ $key ] : $default, $key, $instance );
	}

	/**
	 * Return sections by entity param.
	 *
	 * @param $entity
	 * @return array
	 */
	public static function get_sections( $entity, $enable_order = true )
	{
		global $wpdb;

		$result = array();

		$fbuilder_sections = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}fbuilder_sections", ARRAY_A );

		if ( $fbuilder_sections ) {
			foreach ( $fbuilder_sections as $key => $value ) {
				$value['fbuilder'] = true;
				$fbuilder_sections[ $value['machine_name'] ] = $value;
				unset( $fbuilder_sections[ $key ] );
			}
		}

		$base_sections = array(
			'es-info' => array(
				'machine_name' => 'es-info',
				'label' => __( 'Basic facts', 'es-plugin' ),
				'render_action' => 'es_single_info_tab',
				'sortable' => false,
				'show_tab' => true,
			),
			'es-description' => array(
				'machine_name' => 'es-description',
				'label' => __( 'Description', 'es-plugin' ),
				'render_action' => 'es_single_description_tab',
				'show_tab' => true,
			),
			'es-map' => array(
				'machine_name' => 'es-map',
				'label' => __( 'Neighborhood', 'es-plugin' ),
				'render_action' => 'es_single_map_tab',
				'show_tab' => true,
			),
			'es-features' => array(
				'machine_name' => 'es-features',
				'label' => __( 'Features', 'es-plugin' ),
				'render_action' => 'es_single_features',
				'show_tab' => true,
				'icon' => 'es-featured',
			),

			'es-video' => array(
				'machine_name' => 'es-video',
				'label' => __( 'Video', 'es-plugin' ),
				'render_action' => 'es_single_video_tab',
				'show_tab' => true,
			) );

		global $es_settings;

		$sections = array_merge( $base_sections, $fbuilder_sections );

		$order = $wpdb->get_col( "SELECT section_machine_name FROM {$wpdb->prefix}fbuilder_sections_order ORDER BY id ASC" );

		if ( $order && $enable_order ) {
			foreach ( $order as $section ) {
				if ( isset( $sections[ $section ] ) ) {
					$result[ $section ] = $sections[ $section ];
				}
			}
		} else {
			$result = $sections;
		}

		return apply_filters( 'es_fbuilder_entity_sections', $result, $entity );
	}

	/**
	 * Return sections for select box field.
	 *
	 * @param $entity
	 * @return array
	 */
	public static function get_sections_options( $entity )
	{
		$result = array();

		if ( $data = self::get_sections( $entity ) ) {
			foreach ( $data as $item ) {
				$result[ $item['machine_name'] ] = $item['label'];
			}
		}

		return $result;
	}

	/**
	 * @param $data
	 * @return false|int
	 */
	public static function save_section( $data )
	{
		global $wpdb;

		$data = apply_filters( 'es_fbuilder_before_save_section_data', $data );

		if ( ! empty( $data['id'] ) ) {
			return $wpdb->update( $wpdb->prefix . 'fbuilder_sections', $data, array( 'id' => $data['id'] ) );
		} else {
			$machine_name = sanitize_title( self::get_settings_value( $data, 'label' ) . time() . uniqid( 'f' ) );

			$wpdb->insert( $wpdb->prefix . 'fbuilder_sections_order', array(
				'section_machine_name' => $machine_name,
			) );

			return $wpdb->insert( $wpdb->prefix . 'fbuilder_sections', array_merge( array(
					'machine_name' => $machine_name ), $data )
			);
		}
	}

	/**
	 * Insert or update new field.
	 *
	 * @param $data
	 * @return false|int
	 */
	public static function save_field( $data ) {
		global $wpdb;

		$entity = self::get_settings_value( $data, 'entity', 'property' );

		$values = ! empty( $data['values'] ) ? array_filter ( $data['values'] ) : null;

		$type = self::get_settings_value( $data, 'type' );
		$formatter = $type == 'price' ? 'price' : null;
		$formatter = $type == 'area' ? 'area' : $formatter;
		$formatter = $type == 'url' ? 'url' : $formatter;
		$formatter = $type == 'file' ? 'file' : $formatter;

		$data['tab'] = $data['section'];

		$instance = apply_filters( 'es_fbuilder_field_presave_instance', array(
			'label' => self::get_settings_value( $data, 'label' ),
			'type' => $type == 'price' || $type == 'area' ? 'number' : $type,
			'formatter' => $formatter,
			'tab' => self::get_settings_value( $data, 'tab' ),
			'section' => self::get_settings_value( $data, 'section' ),
			'options' => ! empty( $data['options'] ) ? serialize( $data['options'] ) : null,
			'entity' => $entity,
			'values' => $values ? serialize( array_combine( $values, $values  ) ) : null,
			'rets_support' => self::get_settings_value( $data, 'rets_support' ),
			'search_range_mode' => self::get_settings_value( $data, 'search_range_mode' ),
			'show_thumbnail' => self::get_settings_value( $data, 'show_thumbnail' ),
			'import_support' => self::get_settings_value( $data, 'import_support', 0 ),
		) );

		if ( ! empty( $data['id'] ) ) {
			$field = self::get_field( $data['id'] );
			if ( $field ) {
				$wpdb->update( $wpdb->prefix . 'fbuilder_fields_order', array( 'section_machine_name' => $instance['section'] ), array( 'field_machine_name' => $field['machine_name'] ) );
				return $wpdb->update( $wpdb->prefix . 'fbuilder_fields', $instance, array( 'id' => $data['id'] ) );
			} else {
				return false;
			}

		} else {
			$machine_name = sanitize_title( self::get_settings_value( $data, 'label' ) . time() . uniqid( 'f' ) );

			$wpdb->insert( $wpdb->prefix . 'fbuilder_fields_order', array(
				'section_machine_name' => $instance['section'],
				'field_machine_name' => $machine_name,
			) );

			return $wpdb->insert( $wpdb->prefix . 'fbuilder_fields', array_merge( array(
					'machine_name' => $machine_name ), $instance )
			);
		}
	}

	/**
	 * @param $id
	 * @return array|null|object
	 */
	public static function get_field( $id ) {
		global $wpdb;
		$instance = $wpdb->get_row( "SELECT * FROM " . $wpdb->prefix . "fbuilder_fields WHERE id = '{$id}'", ARRAY_A );

		if ( $instance ) {
			$instance['options'] = ! empty( $instance['options'] ) ? unserialize( $instance['options'] ) : array();
			$instance['values'] = ! empty( $instance['values'] ) ? unserialize( $instance['values'] ) : array();
		}

		return $instance;
	}

	/**
	 * @param $id
	 * @return array|null|object|void
	 */
	public static function get_section( $id ) {
		global $wpdb;

		return $wpdb->get_row( "SELECT * FROM " . $wpdb->prefix . "fbuilder_sections WHERE id = '{$id}'", ARRAY_A );
	}

	/**
	 * Remove field by ID.
	 *
	 * @param $id
	 * @return false|int
	 */
	public static function remove_field( $id )
	{
		global $wpdb;

		$field = self::get_field( $id );
		if ( $field ) {
			$wpdb->delete( $wpdb->prefix . 'fbuilder_fields_order', array( 'field_machine_name' => $field['machine_name'] ) );
			return $wpdb->delete( $wpdb->prefix . 'fbuilder_fields', array( 'id' => $id ) );
		}

		return false;
	}

	/**
	 * Remove field by ID.
	 *
	 * @param $id
	 * @return false|int
	 */
	public static function remove_section( $id )
	{
		global $wpdb;

		$field = self::get_section( $id );
		if ( $field ) {
			$wpdb->delete( $wpdb->prefix . 'fbuilder_sections_order', array( 'section_machine_name' => $field['machine_name'] ) );
			return $wpdb->delete( $wpdb->prefix . 'fbuilder_sections', array( 'id' => $id ) );
		}

		return false;
	}

	/**
	 * Set sections order.
	 *
	 * @param $sections array
	 *    Sections machine names ordered array.
	 *
	 * @return bool
	 */
	public static function set_sections_order( $sections ) {
		global $wpdb;

		$inserted = true;

		foreach ( $sections as $index => $machine_name ) {
			$wpdb->delete( $wpdb->prefix . 'fbuilder_sections_order', array( 'section_machine_name' => $machine_name ) );

			$inserted = $wpdb->insert( $wpdb->prefix . 'fbuilder_sections_order', array(
				'section_machine_name' => $machine_name,
				'order' => $index,
			) );

			if ( ! $inserted ) {
				break;
			}
		}

		return $inserted;
	}

	/**
	 * Set fields order by section.
	 *
	 * @param $section string
	 *    Section machine name.
	 * @param $fields array
	 *    Fields machine names ordered array.
	 *
	 * @return bool
	 */
	public static function set_section_fields_order( $section, $fields ) {
		global $wpdb;
		$inserted = true;

		foreach ( $fields as $index => $field_machine_name ) {
			$wpdb->delete( $wpdb->prefix . 'fbuilder_fields_order', array( 'field_machine_name' => $field_machine_name ) );

			$inserted = $wpdb->insert( $wpdb->prefix . 'fbuilder_fields_order', array(
				'field_machine_name' => $field_machine_name,
				'section_machine_name' => $section,
				'order' => $index,
			) );

			if ( ! $inserted ) {
				break;
			} else {
				$wpdb->update( $wpdb->prefix . 'fbuilder_fields', array( 'section' => $section, 'tab' => $section ), array( 'machine_name' => $field_machine_name ) );
			}
		}

		return $inserted;
	}
}
