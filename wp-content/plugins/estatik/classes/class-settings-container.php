<?php

/**
 * Class Es_Settings_Container.
 *
 * @property bool $powered_by_link
 * @property int $properties_per_page
 * @property bool $show_price
 * @property bool $show_labels
 * @property string $title_address
 * @property bool $show_address
 * @property string $date_format
 * @property string $theme_style
 * @property string $listing_layout
 * @property string $single_layout
 * @property string $currency
 * @property string $price_format
 * @property string $currency_position
 * @property string $recaptcha_secret_key
 * @property string $recaptcha_site_key
 * @property string $google_api_key
 * @property string $main_color
 * @property string $property_slug
 * @property string $frame_color
 * @property string $secondary_color
 * @property string $reset_button_color
 * @property integer $thumbnail_attachment_id
 * @property bool $disable_og_meta_tags
 * @property bool $date_added
 * @property bool $hide_property_top_bar
 * @property string $all_listings_page_id
 * @property array $property_removed_fields
 * @property bool $privacy_policy_checkbox
 * @property integer $term_of_use_page_id
 * @property integer $privacy_policy_page_id
 * @property integer $disable_featured_image
 * @property integer $search_page_id
 * @property integer $email_logo_attachment_id
 * @property integer $is_wishlist_enabled
 */
class Es_Settings_Container
{
    /**
     * Prefix for settings. Example {SETTING_PREFIX}powered_by_link.
     */
    const SETTING_PREFIX = 'es_';

    /**
     * Return list of available settings.
     *
     * @return array|mixed
     */
    public static function get_available_settings()
    {
        return apply_filters( 'es_get_available_settings', array(

            'powered_by_link' => array(
                'default_value' => 1,
            ),

            'unit' => array(
                'default_value' => 'sq_ft',
                'values' => array(
                    'sq_ft'    => __( 'sq ft', 'es-plugin' ),
                    'sq_m'     => __( 'm²', 'es-plugin' ),
                    'acres'    => __( 'acres', 'es-plugin' ),
                    'hectares' => __( 'hectares', 'es-plugin' ),
                    'm3'       => __( 'm³', 'es-plugin' ),
                ),
            ),

            'properties_per_page' => array(
                'default_value' => 10,
            ),

            'show_price' => array(
                'default_value' => 1,
            ),

            'show_address' => array(
                'default_value' => 0,
            ),

            'date_added' => array(
                'default_value' => 1,
            ),

            'listing_layout' => array(
                'default_value' => 'list',
                'values' => array(
                    'list' => __( 'List', 'es-plugin' ),
                    '2_col' => __( '2 Columns', 'es-plugin' ),
                    '3_col' => __( '3 Columns', 'es-plugin' ),
                ) ),

            'single_layout' => array(
                'default_value' => 'left',
                'values' => array(
                    'left' => __( 'Left layout', 'es-plugin' ) ,
                    'right' => __( 'Right layout', 'es-plugin' ),
                    'center' => __( 'Center layout', 'es-plugin' ),
                ) ),

            'show_labels' => array(
                'default_value' => 1
            ),

            'currency' => array(
                'default_value' => 'USD',
                'values' => array(
                    'USD' => '$',
                    'EUR' => '€',
                    'GBP' => '£',
                    'RUB' => 'RUB',
                ),
            ),

            'price_format' => array(
                'default_value' => ',.',
                'values' => array( ',.' => '19,999.00', '.,' => '19.999,00', ' ' => '19 999', ',' => '19,999' ),
            ),

            'currency_position' => array(
                'default_value' => 'before',
                'values' => array(
                    'after' => __( 'After price', 'es-plugin' ),
                    'before' => __( 'Before price', 'es-plugin' ),
                ) ),

            'title_address' => array(
                'default_value' => 'address',
                'values' => array( 'title' => __( 'Title', 'es-plugin' ), 'address' => __( 'Address', 'es-plugin' ) ),
            ),

            'date_format' => array(
                'default_value' => 'm/d/y',
                'values' => array( 'd/m/y' => date('d/m/y'), 'm/d/y' => date('m/d/y'), 'd.m.y' =>  date('d.m.y') ),
            ),

            'theme_style' => array(
                'default_value' => 'light',
                'values' => array( 'light' => __( 'Light', 'es-plugin' ), 'dark' => __( 'Dark', 'es-plugin' ) ),
            ),

            'google_api_key' => array(
                'default_value' => '',
            ),

            'thumbnail_attachment_id' => array(
                'default_value' => '',
            ),

            'share_twitter' => array(
                'default_value' => 1,
            ),

            'share_linkedin' => array(
                'default_value' => 1,
            ),

            'share_facebook' => array(
                'default_value' => 1,
            ),

            'share_google_plus' => array(
                'default_value' => 1,
            ),

	        'recaptcha_site_key' => array(
		        'default_value' => '',
	        ),

            'recaptcha_secret_key' => array(
	            'default_value' => '',
            ),

            'registration_page_id' => array( 'default_value' => '' ),
            'login_page_id' => array( 'default_value' => '' ),
            'reset_password_page_id' => array( 'default_value' => '' ),

            'all_listings_page_id' => array(
	            'default_value' => '',
            ),

            'property_removed_fields' => array(
	            'default_value' => array(),
            ),

	        'demo_executed' => array(
	        	'default_value' => '',
	        ),

            'single_featured_listing' => array(
	        	'default_value' => '',
	        ),

            'main_color' => array(
	        	'default_value' => '#ff9600',
	        ),

            'secondary_color' => array(
	        	'default_value' => '#f0f0f0',
	        ),

            'reset_button_color' => array(
	        	'default_value' => '#9e9e9e',
	        ),

            'frame_color' => array(
	        	'default_value' => '#1d1d1d',
	        ),
            'property_slug' => array(
                'default_value' => 'property',
            ),

            'hide_property_top_bar' => array(
                'default_value' => false,
            ),

            'disable_og_meta_tags' => array(
                'default_value' => false,
            ),

            'privacy_policy_checkbox' => array(
                'default_value' => 'required',
                'values' => array(
                    'required' => __( 'Required', 'es-plugin' ),
                    'optional' => __( 'Optional', 'es-plugin' ),
                ),
            ),

            'term_of_use_page_id' => array(
                'default_value' => '',
            ),

            'privacy_policy_page_id' => array(
                'default_value' => '',
            ),

            'disable_featured_image' => array(
                'default_value' => 1,
            ),

            'search_page_id' => array(
                'default_value' => '',
            ),

            'email_logo_attachment_id' => array(
		        'default_value' => '',
	        ),

            'is_wishlist_enabled' => array(
	            'default_value' => 1,
            ),
        ) );
    }

    /**
     * Return list if available values using setting name.
     *
     * @param $name
     * @return null
     */
    public static function get_setting_values( $name ) {
        $settings = static::get_available_settings();

        $stored_values = get_option( self::SETTING_PREFIX . $name . '_values', array() );
        $defined_values = ! empty( $settings[ $name ]['values'] ) ? $settings[ $name ]['values'] : array();

        $values = array_merge( $defined_values, $stored_values );

        return $values ? $values : null;
    }

    /**
     * Return option value using setting name.
     *
     * @param $name
     * @return string|null
     */
    public function __get( $name )
    {
        $settings = static::get_available_settings();

        return isset( $settings[ $name ]['default_value'] ) ?
            get_option( static::SETTING_PREFIX . $name, $settings[ $name ]['default_value'] ) : null;

    }

    /**
     * Return field default value.
     *
     * @param $name
     * @return null
     */
    public function get_default_value( $name ) {

        $settings = static::get_available_settings();
        return ! emptY( $settings[ $name ]['default_value'] ) ? $settings[ $name ]['default_value'] : null;
    }

    /**
     * Magic method for empty and isset methods.
     *
     * @param $name
     * @return bool
     */
    public function __isset($name)
    {
        $value = $this->__get( $name );
        return ! empty( $value );
    }

    /**
     * Save one settings.
     *
     * @param $name
     * @param $value
     *
     * @return void
     */
    public function saveOne( $name, $value )
    {
        update_option( static::SETTING_PREFIX . $name, $value );
    }

    /**
     * Save settings list.
     *
     * @param array $data
     * @see update_option
     */
    public function save( array $data )
    {
        if ( ! empty( $data ) ) {
            $settings = static::get_available_settings();
            foreach ( $settings as $name => $setting ) {
                if ( isset( $data[ $name ] ) ) {
                    update_option( static::SETTING_PREFIX . $name, $data[ $name ] );
                }
            }
        }
    }

    /**
     * Return label of the value.
     *
     * @param $name
     * @param $value
     * @return null
     */
    public function get_label( $name, $value )
    {
        $default = static::get_setting_values( $name );
        return ! empty( $default[ $value ] ) ? $default[ $value ] : null;
    }
}
