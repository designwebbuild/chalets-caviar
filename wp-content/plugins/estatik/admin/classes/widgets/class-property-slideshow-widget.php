<?php

/**
 * Class Es_Property_Slidehow_Widget
 */
class Es_Property_Slideshow_Widget extends Es_Widget
{
	/**
	 * Es_Property_Slideshow_Widget constructor.
	 */
	public function __construct()
	{
		parent::__construct( 'es_property_slideshow', __( 'Estatik Slideshow', 'es-plugin' ) );
	}

	/**
	 * Return layouts of this widget.
	 *
	 * @return array
	 */
	public static function get_layouts()
	{
		return apply_filters( 'es_get_property_slideshow_widget_layouts', array( 'horizontal', 'vertical' ) );
	}

	/**
	 * Return layouts of this widget.
	 *
	 * @return array
	 */
	public static function get_slider_effects()
	{
		return apply_filters( 'es_get_property_slideshow_widget_layouts', array( 'horizontal', 'vertical' ) );
	}

	/**
	 * @return array
	 */
	public static function get_filter_fields_data()
	{
		/** @var Es_Settings_Container $es_settings */
		global $es_settings;

		$data = array();

		$taxonomies = $es_settings::get_setting_values( 'taxonomies' );

		if ( ! empty( $taxonomies ) ) {
			foreach ( $taxonomies as $name => $taxonomy ) {
				if ( taxonomy_exists( $name ) ) {
					$taxonomy = get_taxonomy( $name );

					$data[ $taxonomy->label ] = get_terms( array( 'taxonomy' => $taxonomy->name, 'hide_empty' => false ) );
				}
			}
		}

		return apply_filters( 'es_get_property_slideshow_filter_fields_data', $data );
	}

	/**
	 * Function for register widget.
	 *
	 * @return void
	 */
	public static function register()
	{
		register_widget( 'Es_Property_Slideshow_Widget' );
	}

	/**
	 * @inheritdoc
	 */
	protected function get_widget_template_path()
	{
		return ES_PLUGIN_PATH . '/admin/templates/widgets/es-property-slideshow-widget.php';
	}

	/**
	 * @return string
	 */
	protected function get_widget_form_template_path()
	{
		return ES_PLUGIN_PATH . '/admin/templates/widgets/es-property-slideshow-widget-form.php';
	}

	/**
	 * @return mixed
	 */
	public function generate_map_shortcode( $instance = array() )
	{
		global $es_settings;

		$content = '';
		$data_filter = array();

		$layout = ! empty( $instance['layout'] ) ? $instance['layout'] : 'horizontal';
		$show_arrows = isset( $instance['show_arrows'] ) ? $instance['show_arrows'] : 0;
		$slider_effect = ! empty( $instance['slider_effect'] ) ? $instance['slider_effect'] : 'horizontal';
		$slides_num = ! empty( $instance['slides_num'] ) ? $instance['slides_num'] : 1;
		$prop_ids = ! empty( $instance['prop_ids'] ) ? $instance['prop_ids'] : null;
		$limit = ! empty( $instance['limit'] ) ? $instance['limit'] : 20;
		$margin = ! empty( $instance['margin'] ) ? $instance['margin'] : 10;

		if ( ! empty( $instance['filter_data'] ) ) {
			foreach ( $instance['filter_data'] as $tid ) {
				$term = get_term( $tid );
				if ( $term && strlen( $term->taxonomy ) > 3 ) {
					$data_filter[ substr( $term->taxonomy, 3 ) ][] = $term->name;
				}
			}

			if ( is_array( $data_filter ) && ! empty( $data_filter ) ) {
				foreach ( $data_filter as $taxonomy => $slugs ) {
					$content .= ' ' . $taxonomy . '="' . implode( ',', $slugs ) . '"';
				}
			}
		}

		if ( ! empty( $prop_ids ) ) {
			$content .= ' prop_id="' . $prop_ids . '"';
		}
		$content .= ' layout="' . $layout . '"';
		$content .= ' slider_effect="' . $slider_effect . '"';
		$content .= ' slides_to_show="' . $slides_num . '"';
		$content .= ' show_arrows="' . $show_arrows . '"';
		$content .= ' margin="' . $margin . '"';
		$content .= ' limit="' . $limit . '"';

		return apply_filters( 'es_generate_slideshow_shortcode', '[es_property_slideshow' . $content . ']', $this->id_base, $this->id );
	}
}

add_action( 'widgets_init', array( 'Es_Property_Slideshow_Widget', 'register' ) );
