<?php

/**
 * Class Es_Property_Slideshow_Shortcode
 */
class Es_Property_Slideshow_Shortcode extends Es_My_Listing_Shortcode
{
	/**
	 * @inheritdoc
	 */
	public function get_shortcode_default_atts()
	{
		$default = array_merge( parent::get_shortcode_default_atts(), array(
			'limit' => 20,
			'show' => null,
			'slider_effect' => 'horizontal',
			'show_arrows' => 0,
			'slides_to_show' => 1,
			'layout' => 'horizontal',
			'margin' => 10,
		) );

		return $default;
	}

	/**
	 * @inheritdoc
	 */
	public function build_query_args( $atts )
	{
		$args = parent::build_query_args( $atts );

		if ( $atts['show'] == 'all') {
			$args['limit'] = -1;
			$args['posts_per_page'] = -1;
		} else {
			$args['posts_per_page'] = $atts['limit'];
		}

		unset( $args['paged'] );

		return $args;
	}

	/**
	 * @inheritdoc
	 */
	public function property_loop( $query_args, $atts )
	{
		wp_register_script( 'es-property-slideshow-shortcode', ES_PLUGIN_URL . 'assets/js/custom/shortcode-slideshow.js',
			array( 'jquery', 'es-slick-script' ), false, true );

		wp_enqueue_script( 'es-property-slideshow-shortcode' );
		wp_enqueue_style( 'es-slick-style' );
		wp_enqueue_style( 'es-slick-theme-style' );

		$uid = uniqid();
		$content = null;
		$posts = array();

		if ( ! empty( $query_args ) ) {
			$query = new WP_Query( $query_args );
			$posts = $query->get_posts();
		}

		if ( ! empty( $posts ) ) {
			$variables = Estatik::register_js_variables();

			$variables['shortcodes']['slideshow'][ $uid ] = $atts;

			wp_localize_script( 'es-property-slideshow-shortcode', 'Estatik', $variables );

			add_filter( 'es_global_js_variables', function() use ( $variables ) {
				return $variables;
			} );

			ob_start();
			include ( apply_filters( 'es_property_slideshow_shortcode_template_path',
				ES_PLUGIN_PATH . '/templates/widgets/slideshow.php' ) );
			$content = ob_get_clean();
		}

		return $content;
	}

	/**
	 * Return shortcode name.
	 *
	 * @return string
	 */
	public function get_shortcode_name()
	{
		return 'es_property_slideshow';
	}
}