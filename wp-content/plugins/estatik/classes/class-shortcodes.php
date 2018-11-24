<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Class Es_Shortcodes
 */
class Es_Shortcodes
{
    /**
     * Initialize new plugin shortcodes.
     *
     * @return void.
     */
    public static function init()
    {
        $short_codes = apply_filters( 'es_shortcodes_list', array(
            'Es_My_Listing_Shortcode',
            'Es_Featured_Props_Shortcode',
            'Es_Latest_Props_Shortcode',
            'Es_Cheapest_Props_Shortcode',
            'Es_Category_Shortcode',
            'Es_Expensive_Props_Shortcode',
	        'Es_Register_Shortcode',
            'Es_Single_Shortcode',
	        'Es_Login_Shortcode',
	        'Es_Restore_Password_Shortcode',
            'Es_Search_Form_Shortcode',
            'Es_Search_Shortcode',
            'Es_Profile_Shortcode',
            'Es_Property_Slideshow_Shortcode',
        ) );

        foreach ( $short_codes as $shortcode_class ) {
            if ( class_exists( $shortcode_class ) ) {
                /** @var Es_Shortcode $shortcode */
                $shortcode = new $shortcode_class;
                add_shortcode( $shortcode->get_shortcode_name(), array( $shortcode, 'build' ) );
            }
        }
    }
}
