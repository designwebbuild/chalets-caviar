<?php

/**
 * @var Es_Settings_Container $es_settings
 */

$pages = get_pages();
$list_pages[] = __( '-- Select page --', 'es-plugin' );

if ( ! empty( $pages ) ) {
    foreach ( $pages as $page ) {
        $list_pages[ $page->ID ] = $page->post_title;
    }
}

echo Es_Html_Helper::render_settings_field( __( 'Powered by link', 'es-plugin' ), 'es_settings[powered_by_link]', 'checkbox', array(
    'checked' => (bool) $es_settings->powered_by_link ? 'checked' : false,
    'value' => 1,
    'class' => 'es-switch-input',
) );

echo Es_Html_Helper::render_settings_field( __( 'Number of listings per page', 'es-plugin' ), 'es_settings[properties_per_page]', 'number', array(
    'checked' => (bool) $es_settings->properties_per_page ? 'checked' : false,
    'value' => $es_settings->properties_per_page,
    'min' => '1',
) );

echo Es_Html_Helper::render_settings_field( __( 'Custom Property Slug URI', 'es-plugin' ), 'es_settings[property_slug]', 'text', array(
    'value' => $es_settings->property_slug,
	'required' => 'required',
) );

echo Es_Html_Helper::render_settings_field( __( 'Show price', 'es-plugin' ), 'es_settings[show_price]', 'checkbox', array(
    'checked' => (bool) $es_settings->show_price ? 'checked' : false,
    'value' => 1,
    'class' => 'es-switch-input',
) );

echo Es_Html_Helper::render_settings_field( __( 'Disable Property Featured Image', 'es-plugin' ), 'es_settings[disable_featured_image]', 'checkbox', array(
	'checked' => (bool) $es_settings->disable_featured_image ? 'checked' : false,
	'value' => 1,
	'class' => 'es-switch-input',
) ); ?>

<?php if ( $data = $es_settings::get_setting_values( 'title_address' ) ) : $name = 'title_address'; $label = __( 'Title / Address', 'es-plugin' ) ?>
    <?php include( 'fields/radio-list.php' ); ?>
<?php endif; ?>

<?php echo Es_Html_Helper::render_settings_field( __( 'Show Address', 'es-plugin' ), 'es_settings[show_address]', 'checkbox', array(
    'checked' => (bool) $es_settings->show_address ? 'checked' : false,
    'value' => 1,
    'class' => 'es-switch-input',
) );

echo Es_Html_Helper::render_settings_field( __( 'Hide Property Top Bar', 'es-plugin' ), 'es_settings[hide_property_top_bar]', 'checkbox', array(
    'checked' => (bool) $es_settings->hide_property_top_bar ? 'checked' : false,
    'value' => 1,
    'class' => 'es-switch-input',
) );

echo Es_Html_Helper::render_settings_field( __( 'Enable Wishlist', 'es-plugin' ), 'es_settings[is_wishlist_enabled]', 'checkbox', array(
    'checked' => (bool) $es_settings->is_wishlist_enabled ? 'checked' : false,
    'value' => 1,
    'class' => 'es-switch-input',
) );

echo Es_Html_Helper::render_settings_field( __( 'Disable meta:og tags', 'es-plugin' ), 'es_settings[disable_og_meta_tags]', 'checkbox', array(
    'checked' => (bool) $es_settings->disable_og_meta_tags ? 'checked' : false,
    'value' => 1,
    'class' => 'es-switch-input',
) );

echo Es_Html_Helper::render_settings_field( __( 'Labels', 'es-plugin' ), 'es_settings[show_labels]', 'checkbox', array(
    'checked' => (bool) $es_settings->show_labels ? 'checked' : false,
    'value' => 1,
    'class' => 'es-switch-input',
) );

echo Es_Html_Helper::render_settings_field( __( 'Date added', 'es-plugin' ), 'es_settings[date_added]', 'checkbox', array(
    'checked' => (bool) $es_settings->date_added ? 'checked' : false,
    'value' => 1,
    'class' => 'es-switch-input',
) );

echo Es_Html_Helper::render_settings_field( __( 'Date format', 'es-plugin' ), 'es_settings[date_format]', 'list', array(
    'value' => $es_settings->date_format,
    'values' => $es_settings::get_setting_values( 'date_format' ),
) );

echo Es_Html_Helper::render_settings_field( __( 'Theme style', 'es-plugin' ), 'es_settings[theme_style]', 'list', array(
	'value' => $es_settings->theme_style,
	'values' => $es_settings::get_setting_values( 'theme_style' ),
) );

if ( $data = $es_settings::get_setting_values( 'privacy_policy_checkbox' ) ) : $name = 'privacy_policy_checkbox'; $label = __( 'Privacy Policy Checkbox', 'es-plugin' ) ?>
    <?php include( 'fields/radio-list.php' ); ?>
<?php endif;

echo Es_Html_Helper::render_settings_field( __( 'Terms of Use page', 'es-plugin' ), 'es_settings[term_of_use_page_id]', 'list', array(
    'value' => $es_settings->term_of_use_page_id,
    'values' => $list_pages,
) );

echo Es_Html_Helper::render_settings_field( __( 'Privacy Policy page', 'es-plugin' ), 'es_settings[privacy_policy_page_id]', 'list', array(
    'value' => $es_settings->privacy_policy_page_id,
    'values' => $list_pages,
) );

echo Es_Html_Helper::render_settings_field( __( 'Search page', 'es-plugin' ), 'es_settings[search_page_id]', 'list', array(
	'value' => $es_settings->search_page_id,
	'values' => $list_pages,
) );

echo Es_Html_Helper::render_settings_field( __( 'Registration page', 'es-plugin' ), 'es_settings[registration_page_id]', 'list', array(
	'value' => $es_settings->registration_page_id,
	'values' => $list_pages,
) );

echo Es_Html_Helper::render_settings_field( __( 'Login page', 'es-plugin' ), 'es_settings[login_page_id]', 'list', array(
	'value' => $es_settings->login_page_id,
	'values' => $list_pages,
) );

echo Es_Html_Helper::render_settings_field( __( 'Reset password page', 'es-plugin' ), 'es_settings[reset_password_page_id]', 'list', array(
	'value' => $es_settings->reset_password_page_id,
	'values' => $list_pages,
) );

echo Es_Html_Helper::render_settings_field( __( 'Google map API key', 'es-plugin' ), 'es_settings[google_api_key]', 'text', array(
    'value' => $es_settings->google_api_key,
) );

echo Es_Html_Helper::render_settings_field( __( 'Google Recaptcha SiteKey', 'es-plugin' ), 'es_settings[recaptcha_site_key]', 'text', array(
	'value' => $es_settings->recaptcha_site_key,
) );

echo Es_Html_Helper::render_settings_field( __( 'Google Recaptcha SecretKey', 'es-plugin' ), 'es_settings[recaptcha_secret_key]', 'text', array(
	'value' => $es_settings->recaptcha_secret_key,
) );
