<?php

/**
 * Class
 */
class Es_Data_Manager_Currency_Item extends Es_Data_Manager_Item
{
    /**
     * @var string Data manager item template path.
     */
    protected $_template_path = '/admin/templates/data-manager/currency-item.php';

    /**
     * @return void
     */
    public static function save()
    {
        $nonce = 'es_add_data_manager_currency';

        // If ajax request is valid.
        if ( static::is_valid_ajax() && wp_verify_nonce( $_REQUEST[ $nonce ], $nonce ) ) {
            // Get available values of the item.
            $values = Es_Settings_Container::get_setting_values( $_POST['current_option_name'] );

            // If items exists.
            if ( in_array( $_POST['currency_label'], $values ) || isset( $values[ $_POST['currency_code'] ] ) ) {
                $response = array( 'message' => __( 'Currency already exists.', 'es-plugin' ), 'status' => 'warning' );

                // Add new item to the option.
            } else if ( ! empty( $_POST['currency_code'] ) && ! empty( $_POST['currency_label'] ) ) {
                $values = get_option( $_POST['option_storage_name'], array() );
                $values[ $_POST['currency_code'] ] = $_POST['currency_label'];

                $values = array_filter( $values );

                update_option( $_POST['option_storage_name'], $values );

                $response = array(
                    'message' => __( 'Item is successfully created.', 'es-plugin' ),
                    'status' => 'success',
                    'item' => array_search( $_POST['currency_label'], $values )
                );
            } else {
                $response = array( 'message' => __( 'Please, fill the currency code and label.', 'es-plugin' ), 'status' => 'error' );
            }
        } else {
            $response = array( 'message' => __( 'Invalid ajax request.', 'es-plugin' ), 'status' => 'error' );
        }

        wp_die( json_encode( $response ) );
    }
}
