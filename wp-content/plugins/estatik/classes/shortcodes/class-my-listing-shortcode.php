<?php

/**
 * Class Es_My_Listing_Shortcode for [es_my_listing] shortcode.
 */
class Es_My_Listing_Shortcode extends Es_Shortcode
{
    /**
     * @inheritdoc
     */
    public function build( $atts = array() )
    {
        // Merge shortcode attributes,
        $atts = $this->merge_shortcode_atts( $atts );

        // Prepare layout names from prev. plugin version.
        if ( ! empty( $atts['layout'] ) ) {
            switch ( $atts['layout'] ) {
                case 'table':
                    $atts['layout'] = '3_col';
                    break;
                case '2columns':
                    $atts['layout'] = '2_col';
                    break;
            }
        }

        return $this->property_loop(
            $this->build_query_args( $atts ),
            $atts
        );
    }

    /**
     * Merge shortcode attributes (default / input).
     *
     * @param $atts
     * @return array
     */
    public function merge_shortcode_atts( $atts )
    {
        return shortcode_atts( $this->get_shortcode_default_atts(), $atts, $this->get_shortcode_name() );
    }

    /**
     * @inheritdoc
     */
    public function get_shortcode_name()
    {
        return 'es_my_listing';
    }

    /**
     * @inheritdoc
     */
    public function get_shortcode_default_atts()
    {
        global $es_settings;

        return array(
            // list, 2_col, 3_col
            'layout' => $es_settings->listing_layout,
            'posts_per_page' => $es_settings->properties_per_page,
            // recent, highest_price, lowest_price, most_popular
            'sort' => null,
            // Taxonomies.
            'status' => null,
            'type' => null,
            'rent_period' => null,
            'category' => null,
            // 1,2,3,...n
            'prop_id' => null,
            // Show filter dropdown with sort values.
            'show_filter' => 1,
            // Simple address string.
            'address' => null,
        );
    }

    /**
     * Build query_args array for wp_query class.
     *
     * @param $atts
     * @return array
     */
    public function build_query_args( $atts )
    {
        // Get property class.
        $property = es_get_property( null );

        if ( get_query_var( 'paged' ) ) {
            $page_num = get_query_var( 'paged' );
        } elseif ( get_query_var( 'page' ) ) {
            $page_num = get_query_var( 'page' );
        } else {
            $page_num = 1;
        }

        $query_args = array(
            'post_type'           => $property::get_post_type_name(),
            'post_status'         => 'publish',
            'posts_per_page'      => $atts[ 'posts_per_page' ],
            'paged' => $page_num,
        );

        $taxonomies = apply_filters( 'es_registered_' . $this->get_shortcode_name() . '_taxonomies', array(
            'es_category', 'es_status', 'es_type', 'es_rent_period',
        ) );

        switch ( $atts['sort'] ) {
            case 'recent':
                $query_args['orderby'] = 'post_date';
                $query_args['order'] = 'DESC';
                break;

            case 'highest_price':
                $query_args['orderby'] = 'meta_value_num';
                $query_args['meta_key'] = 'es_property_price';
                $query_args['order'] = 'DESC';
                $query_args['meta_query'] = array(
	                'relation' => 'OR',
	                array( 'compare' => '=', 'key' => $property->get_entity_prefix() . 'call_for_price', 'value' => '0' ),
	                array( 'compare' => '=', 'key' => $property->get_entity_prefix() . 'call_for_price', 'value' => null ),
	                array( 'compare' => 'NOT EXISTS', 'key' => $property->get_entity_prefix() . 'call_for_price' ),
                );
                break;

            case 'lowest_price':
                $query_args['orderby'] = 'meta_value_num';
                $query_args['meta_key'] = 'es_property_price';
                $query_args['order'] = 'ASC';
                $query_args['meta_query'] = array(
	                'relation' => 'OR',
	                array( 'compare' => '=', 'key' => $property->get_entity_prefix() . 'call_for_price', 'value' => '0' ),
	                array( 'compare' => '=', 'key' => $property->get_entity_prefix() . 'call_for_price', 'value' => null ),
	                array( 'compare' => 'NOT EXISTS', 'key' => $property->get_entity_prefix() . 'call_for_price' ),
                );
                break;

            case 'featured':
                $query_args['orderby'] = 'meta_value_num';
                $query_args['meta_key'] = 'es_property_featured';
                $query_args['order'] = 'DESC';
                break;

            case 'most_popular':
                $query_args['meta_query'][] = array( 'key' => 'es_property_featured', 'value' => 1 );
                break;

            case 'title':
                $query_args['orderby'] = 'post_title';
                $query_args['order'] = 'ASC';
                break;

            default:
                $query_args['orderby'] = 'post_date';
                $query_args['order'] = 'DESC';
        }

        if ( ! empty( $atts ) ) {
            foreach ( $atts as $key => $value ) {
                $tax_name = apply_filters( 'es_taxonomy_shortcode_name', 'es_' . $key );
                if ( in_array( $tax_name, $taxonomies ) && taxonomy_exists( $tax_name ) ) {
                    if ( ! empty( $value ) ) {
                        $query_args['tax_query'][] = array( 'taxonomy' => $tax_name, 'field' => 'name', 'terms' => explode( ',', $value ) );
                    }
                }
            }
        }

        if ( ! empty( $atts['address'] ) ) {
            if ( $output = preg_split( "/[,\s]/", $atts['address'] ) ) {

                $ids = array();

                foreach ( $output as $key => $address_part ) {
                    if ( empty( $address_part ) ) continue;
                    $ids = array_merge( $ids, Es_Property::find_by_address( $address_part ) );
                }

                if ( ! empty( $ids ) ) {
                    $atts['prop_id'] = $ids;
                } else {
	                $atts['prop_id'] = array( -1 );
                }
            }
        }

        if ( ! empty( $atts['prop_id'] ) ) {
            if ( is_array( $atts['prop_id'] ) ) {
                $query_args['post__in'] = $atts['prop_id'];
            } else {
                $query_args['post__in'] = array_map( 'trim', explode( ',', $atts['prop_id'] ) );
            }

            if ( empty( $atts['sort'] ) ) {
                $query_args['orderby'] = 'post__in';
                $query_args['order'] = null;
            }
        }

        return $query_args;
    }

    /**
     * Display listings using
     *
     * @param $query_args
     * @param $atts
     * @return string
     */
    protected function property_loop( $query_args, $atts )
    {
        $properties = new WP_Query( $query_args );

        ob_start();

        include apply_filters( 'es_get_my_listings_template_path', ES_TEMPLATES . '/shortcodes/my-listing.php', $this );

        return ob_get_clean();
    }
}
