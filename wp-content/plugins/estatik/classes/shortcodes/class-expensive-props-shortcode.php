<?php

/**
 * Class Es_Expensive_Props_Shortcode.
 */
class Es_Expensive_Props_Shortcode extends Es_My_Listing_Shortcode
{
    /**
     * @inheritdoc
     */
    public function get_shortcode_name()
    {
        return 'es_expensive_props';
    }

    /**
     * Merge shortcode attributes (default / input).
     *
     * @param $atts
     * @return array
     */
    public function merge_shortcode_atts( $atts )
    {
        $atts = parent::merge_shortcode_atts( $atts );
        $atts['sort'] = 'highest_price';

        return $atts;
    }
}
