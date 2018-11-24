<?php

/**
 * Class Es_Search_Shortcode.
 */
class Es_Search_Shortcode extends Es_My_Listing_Shortcode {

    /**
     * @inheritDoc
     */
    public function build( $atts = array() ) {

        $atts = shortcode_atts( $this->get_shortcode_default_atts(), $atts );

        $query = $this->build_query_args( $atts );

        $properties = new WP_Query( $query );

        ob_start();

        include apply_filters( 'es_get_my_listings_template_path', ES_TEMPLATES . '/shortcodes/my-listing.php', $this );

        return ob_get_clean();
    }

    /**
     * @param $atts
     *
     * @return array
     */
    public function build_query_args( $atts ) {

        $query = parent::build_query_args( $atts );

        if ( ! empty( $_GET['es_search'] ) ) {
            $temp = Es_Search_Widget::build_query( new WP_Query(), $_GET['es_search'] );
            $query = array_merge( $temp->query_vars, $query );
        }

        return $query;
    }

    /**
     * @inheritDoc
     */
    public function get_shortcode_name() {
        return 'es_search';
    }

    /**
     * @return array
     */
    public function get_shortcode_default_atts()
    {
        global $es_settings;

        return array(
            // list, 2_col, 3_col
            'layout' => $es_settings->listing_layout,
            'posts_per_page' => $es_settings->properties_per_page,
            // recent, highest_price, lowest_price, most_popular
            'sort' => 'recent',
            // Show filter dropdown with sort values.
            'show_filter' => 1,
        );
    }
}
