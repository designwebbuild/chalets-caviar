<?php

/**
 * Class Es_Data_Manager_Option_Item
 */
class Es_Data_Manager_Item
{
    /**
     * @var string Option name with all data.
     */
    protected $_option_storage_name;
    /**
     * @var string current data value option name.
     */
    protected $_current_option_name;
    /**
     * @var array settings array.
     */
    protected $_options;
    /**
     * @var string Data manager item template path.
     */
    protected $_template_path = '/admin/templates/data-manager/item.php';

    /**
     * Es_Data_Manager_Item constructor.
     * @param $option_storage_name
     * @param $current_option_name
     * @param $options
     */
    public function __construct( $option_storage_name, $current_option_name, $options = array() )
    {
        $this->_option_storage_name = $option_storage_name;
        $this->_current_option_name = $current_option_name;
        $this->_options = $options;
    }

    /**
     * Return template path of data manager item.
     *
     * @return mixed
     */
    protected function get_template_path()
    {
        return apply_filters( 'es_data_manager_item_get_template_path', ES_PLUGIN_PATH . $this->_template_path, get_called_class() );
    }

    /**
     * Return list of data manager item data list.
     *
     * @return mixed
     */
    public function getItems()
    {
        $items = Es_Settings_Container::get_setting_values( $this->_current_option_name );
        return apply_filters( 'es_data_manager_get_items', $items, $this );
    }

    /**
     * @return array
     */
    public function get_options() {

        return $this->_options;
    }

    /**
     * Return selected option value.
     *
     * @return mixed
     */
    public function get_current_item()
    {
        return get_option( $this->_current_option_name );
    }

    /**
     * Render data manager item.
     *
     * @return void
     */
    public function render()
    {
        if ( file_exists( $this->get_template_path() ) ) {
            include ( $this->get_template_path() );
        }
    }

    /**
     * Check if ajax is valid.
     *
     * @return bool
     */
    public static function is_valid_ajax()
    {
        return is_admin() && defined( 'DOING_AJAX' ) && DOING_AJAX && current_user_can( 'manage_options' );
    }

    /**
     * Save option data.
     *
     * @return void
     */
    public static function save()
    {
        // Nonce field name.
        $nonce = 'es_add_data_manager_option';

        // If ajax request is valid.
        if ( static::is_valid_ajax() && wp_verify_nonce( $_REQUEST[ $nonce ], $nonce ) ) {
            // Get available values of the item.
            $values = Es_Settings_Container::get_setting_values( $_POST['current_option_name'] );

            // If items exists.
            if ( in_array( $_POST['item_name'], $values ) ) {
                $response = array( 'message' => __( 'Item already exists.', 'es-plugin' ), 'status' => 'warning' );

            // Add new item to the option.
            } else {
                $values = get_option( $_POST['option_storage_name'], array() );
                $values[ $_POST['item_name'] ] = $_POST['item_name'];
                update_option( $_POST['option_storage_name'], $values );

                $response = array(
                    'message' => __( 'Item is successfully created.', 'es-plugin' ),
                    'status' => 'success',
                    'item' => array_search( $_POST['item_name'], $values )
                );
            }
        } else {
            $response = array( 'message' => __( 'Invalid ajax request.', 'es-plugin' ), 'status' => 'error' );
        }

        wp_die( json_encode( $response ) );
    }

    /**
     * Remove item from data list.
     *
     * @return void
     */
    public static function remove()
    {
        // If valid ajax request.
        if ( static::is_valid_ajax() && $_POST['action'] ) {
            // Get available values.
            $values = Es_Settings_Container::get_setting_values( $_POST['container'] );

            // Remove item using ID and storage.
            if ( ! empty( $values ) ) {
                $values = get_option( $_POST['storage'], array() );
                unset( $values[ $_POST['id'] ] );
                update_option( $_POST['storage'], $values );

                $response = array( 'message' => __( 'Item is successfully deleted.', 'es-plugin' ), 'status' => 'success' );
            } else {
                $response = array( 'message' => __( 'Nothing for delete.', 'es-plugin' ), 'status' => 'warning' );
            }
        } else {
            $response = array( 'message' => __( 'Invalid ajax request.', 'es-plugin' ), 'status' => 'error' );
        }

        wp_die( json_encode( $response ) );
    }

    /**
     * Check option as default.
     *
     * @return void.
     */
    public function check()
    {
        // If valid ajax request.
        if ( static::is_valid_ajax() ) {
            /** @var Es_Settings_Container $es_settings */
            global $es_settings;
            $es_settings->saveOne( $_POST['container'], $_POST['id'] );

            $response = array( 'message' => __( 'Item has been selected.', 'es-plugin' ), 'status' => 'success' );
        } else {
            $response = array( 'message' => __( 'Invalid ajax request.', 'es-plugin' ), 'status' => 'error' );
        }

        wp_die( json_encode( $response ) );
    }
}
