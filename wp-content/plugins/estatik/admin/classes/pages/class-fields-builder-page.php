<?php

if ( ! defined( 'WPINC' ) ) die;


/**
 * Class Es_Fields_Builder_Page
 */
class Es_Fields_Builder_Page extends Es_Object
{
	/**
	 * Add actions for dashboard page.
	 */
	public function actions()
	{
		add_action( 'admin_enqueue_scripts', array( $this , 'enqueue_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this , 'enqueue_scripts' ) );
		add_action( 'init', array( $this , 'action_save_field' ) );
		add_action( 'init', array( $this , 'action_save_section' ) );
		add_action( 'es_before_property_metabox_tab_content', array( $this , 'add_property_tab_info' ), 10, 1 );
		add_action( 'init', array( $this , 'action_remove_field' ) );
		add_action( 'init', array( $this , 'action_restore_field' ) );
		add_action( 'init', array( $this , 'action_remove_section' ) );
		add_action( 'wp_ajax_es_fbuilder_load_field_options', array( $this , 'action_load_field_options' ) );
		add_action( 'wp_ajax_es_fbuilder_change_fields_order', array( $this , 'action_change_fields_order' ) );
		add_action( 'wp_ajax_es_fbuilder_change_sections_order', array( $this , 'action_change_sections_order' ) );
	}

	/**
	 * Add info text for basic facts property tab.
	 *
	 * @param $id
	 */
	public function add_property_tab_info( $id ) {
		if ( 'es-info' == $id ) {
			echo '<div class="es-fb-info">' . sprintf( wp_kses( __( 'If you lack some fields, please go to <a href="%s">Fields Builder</a> and add your own custom fields.', 'es-plugin' ),
					array(  'a' => array( 'href' => array() ) ) ), esc_url( es_admin_fields_builder_uri() ) ) . '</div>';
		}
	}

	/**
	 * Enqueue styles for dashboard page.
	 *
	 * @return void
	 */
	public function enqueue_styles()
	{
		$custom = 'admin/assets/css/custom/';

		wp_register_style( 'es-fields-builder-style', ES_PLUGIN_URL . $custom . 'fbuilder.css' );
		wp_enqueue_style( 'es-fields-builder-style' );
	}

	/**
	 * Enqueue scripts for the page.
	 *
	 * @return void
	 */
	public function enqueue_scripts()
	{
		$custom = 'admin/assets/js/custom/';

		wp_register_script( 'es-fields-builder-script', ES_PLUGIN_URL . $custom . 'fbuilder.js', array(
			'jquery', 'es-cloneya-script', 'es-admin-script',
		) );
		wp_enqueue_script( 'es-fields-builder-script' );
	}

	/**
	 * @inheritdoc
	 */
	public static function render()
	{
		$path = self::get_template_path( 'main' );

		if ( file_exists( $path ) ) {
			load_template( $path );
		}
	}

	/**
	 * @return mixed
	 */
	public static function get_tabs()
	{
		return apply_filters( 'es_fbuilder_get_tabs', array(
			'es-property' => array(
				'label' => __( 'Listing fields', 'es-plugin' ),
				'template' => self::get_template_path( 'tabs/entity-fields-tab' ),
				'entity' => 'property',
			),
			'es-section' => array(
				'label' => __( 'Listing sections' ),
				'template' => self::get_template_path( 'tabs/entity-sections-tab' ),
				'entity' => 'property',
			),
		) );
	}

	/**
	 * Return template path by template name.
	 *
	 * @param $template
	 * @return string
	 */
	public static function get_template_path( $template ) {
		$path = ES_ADMIN_TEMPLATES . 'fields-builder' . ES_DS . $template . '.php';

		return apply_filters( 'es_fields_buider_get_template_path', $path, $template );
	}

	/**
	 * Save fbuilder field.
	 *
	 * @return void
	 */
	public function action_save_field() {
		$nonce = 'es_fbuilder_save_field';

		if ( ! empty( $_REQUEST[ $nonce ] ) && wp_verify_nonce( $_REQUEST[ $nonce ], $nonce ) ) {
			$messenger = new Es_Messenger( 'fbuilder' );

			$label = $_POST['fbuilder']['label'];

			if ( Es_FBuilder_Helper::save_field( $_POST['fbuilder'] ) ) {
				Es_FBuilder_Helper::set_section_fields_order( $_POST['fbuilder']['section'], array_keys( Es_FBuilder_Helper::get_entity_fields( 'property', $_POST['fbuilder']['section'], true ) ) );
				$messenger->set_message( sprintf( __( 'Field %s successfully created.', 'es-plugin' ), $label ), 'success' );
			} else {
				$messenger->set_message( sprintf( __( 'Field %s doesn\'t created.', 'es-plugin' ), $label ), 'error' );
			}
		}
	}

	/**
	 * Save fbuilder field.
	 *
	 * @return void
	 */
	public function action_save_section() {
		$nonce = 'es_fbuilder_save_section';

		if ( ! empty( $_REQUEST[ $nonce ] ) && wp_verify_nonce( $_REQUEST[ $nonce ], $nonce ) ) {
			$messenger = new Es_Messenger( 'fbuilder' );

			$label = $_POST['fbuilder']['label'];

			if ( Es_FBuilder_Helper::save_section( $_POST['fbuilder'] ) ) {
				Es_FBuilder_Helper::set_sections_order( array_keys( Es_FBuilder_Helper::get_sections( 'property', false ) ) );
				$messenger->set_message( sprintf( __( 'Section %s successfully created.', 'es-plugin' ), $label ), 'success' );
			} else {
				$messenger->set_message( sprintf( __( 'Section %s doesn\'t created.', 'es-plugin' ), $label ), 'error' );
			}
		}
	}

	/**
	 * Remove field action.
	 *
	 * @return void
	 */
	public function action_restore_field()
	{
		$nonce = 'es-fbuilder-restore-field';

		if ( ! empty( $_GET[ 'nonce' ] ) && wp_verify_nonce( $_GET[ 'nonce' ], $nonce ) && ! empty( $_GET['id'] ) ) {
			$messenger = new Es_Messenger( 'fbuilder' );

			/** @var Es_Settings_Container $es_settings */
			global $es_settings;

			if ( in_array( $_GET['id'], $es_settings->property_removed_fields ) ) {
				$removed_fields = $es_settings->property_removed_fields;
				$key = array_search( $_GET['id'], $removed_fields );
				unset( $removed_fields[ $key ] );

				$es_settings->saveOne( 'property_removed_fields', $removed_fields );

				$messenger->set_message( __( 'Field successfully restored.', 'es-plugin' ), 'success' );
				wp_redirect( 'admin.php?page=es_fbuilder' ); die;
			} else {
				$messenger->set_message( sprintf( __( 'Field #%s isn\'t exist.', 'es-plugin' ), $_GET['id'] ), 'error' );

			}
		}
	}

	/**
	 * Remove field action.
	 *
	 * @return void
	 */
	public function action_remove_field()
	{
		$nonce = 'es-fbuilder-remove-field';

		if ( ! empty( $_GET[ 'nonce' ] ) && wp_verify_nonce( $_GET[ 'nonce' ], $nonce ) && ! empty( $_GET['id'] ) ) {
			$field = Es_FBuilder_Helper::get_field( $_GET['id'] );
			$messenger = new Es_Messenger( 'fbuilder' );

			if ( $field || ! empty( $_GET['base_field'] ) ) {
				global $es_settings;

				if ( ! empty( $_GET['base_field'] ) ) {
					$es_settings->property_removed_fields = array_merge( $es_settings->property_removed_fields, array( $_GET['id'] ) );
					$es_settings->saveOne( 'property_removed_fields', $es_settings->property_removed_fields );
				} else {
					Es_FBuilder_Helper::remove_field( $_GET['id'] );
				}
				$messenger->set_message( sprintf( __( 'Field %s successfully removed.', 'es-plugin' ), $field['label'] ), 'success' );
				wp_redirect( 'admin.php?page=es_fbuilder' ); die;
			} else {
				$messenger->set_message( sprintf( __( 'Field #%s isn\'t exist.', 'es-plugin' ), $_GET['id'] ), 'error' );
			}
		}
	}

	/**
	 * Remove section action.
	 *
	 * @return void
	 */
	public function action_remove_section()
	{
		$nonce = 'es-fbuilder-remove-section';

		if ( ! empty( $_GET[ 'nonce' ] ) && wp_verify_nonce( $_GET[ 'nonce' ], $nonce ) && ! empty( $_GET['id'] ) ) {
			$field = Es_FBuilder_Helper::get_section( $_GET['id'] );
			$messenger = new Es_Messenger( 'fbuilder' );

			if ( $field ) {
				Es_FBuilder_Helper::remove_section( $_GET['id'] );
				$messenger->set_message( sprintf( __( 'Section %s successfully removed.', 'es-plugin' ), $field['label'] ), 'success' );
				wp_redirect( 'admin.php?page=es_fbuilder#es-es-section-tab' ); die;
			} else {
				$messenger->set_message( sprintf( __( 'Section #%s isn\'t exist.', 'es-plugin' ), $_GET['id'] ), 'error' );
			}
		}
	}

	/**
	 * Ajax action. Get field additional options fields.
	 *
	 * @return void
	 */
	public function action_load_field_options()
	{
		if ( ! empty( $_POST['type'] ) && wp_doing_ajax() && current_user_can( 'manage_options' ) ) {
			$template = Es_FBuilder_Helper::get_field_options_template( $_POST['type'] );
			$path = apply_filters( 'es_fbuilder_field_options_path', self::get_template_path( 'partials/options/' . $template ) );
			if ( $template && file_exists( $path ) ) {
				include $path;
			}
		}

		wp_die();
	}

	/**
	 * Ajax action. Set fields order.
	 *
	 * @return void
	 */
	function action_change_fields_order()
	{
		if ( wp_doing_ajax() && current_user_can( 'manage_options' ) ) {
			$section = filter_input(INPUT_POST, 'section');
			$order = filter_input(INPUT_POST, 'order', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );

			if ( $section && is_array( $order ) && ! empty( $order ) ) {
				Es_FBuilder_Helper::set_section_fields_order( $section, $order );

				$response = ! empty( $response ) ? $response : array( 'status' => true );

			} else {
				$response = array( 'status' => false );
			}

			wp_die( apply_filters( 'es_fbuilder_action_change_fields_order', $response ) );
		}
	}

	/**
	 * Ajax action. Set sections order.
	 *
	 * @return void
	 */
	public function action_change_sections_order()
	{
		if ( wp_doing_ajax() && current_user_can( 'manage_options' ) ) {
			$order = filter_input(INPUT_POST, 'order', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );

			if ( is_array( $order ) && ! empty( $order ) ) {
				Es_FBuilder_Helper::set_sections_order( $order );
				$response = ! empty( $response ) ? $response : array( 'status' => true );

			} else {
				$response = array( 'status' => false );
			}

			wp_die( apply_filters( 'es_fbuilder_action_change_sections_order', $response ) );
		}
	}
}
