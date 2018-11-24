<?php

/**
 * Class Es_Session_Storage
 */
class Es_Session_Storage
{
    /**
     * @var string
     */
    protected $_key = 'storage';

    /**
     * Es_Session_Storage constructor.
     * @param $key
     */
    public function __construct( $key ) {

        if ( ! session_id() ) {
            session_start();
        }

        $this->_key = $key;
    }

    /**
     * Set data to the session container.
     *
     * @param $key
     * @param $value
     *
     * @return void
     */
    public function set( $key, $value ) {
        $_SESSION[ $this->_key ][ $key ] = $value;
    }

    /**
     * Set array to the storage
     *
     * @param array $data
     *
     * @return void
     */
    public function set_all( array $data ) {
        if ( $data ) {
            foreach ( $data as $key => $value ) {
                $this->set( $key, $value );
            }
        }
    }

    /**
     * Get value from the storage.
     *
     * @param $key
     * @param $default
     *
     * @return mixed
     */
    public function get( $key, $default = null ) {
        return ! empty( $_SESSION[ $this->_key ][ $key ] ) ? $_SESSION[ $this->_key ][ $key ] : $default;
    }

    /**
     * Check for key.
     *
     * @param $key
     *
     * @return bool
     */
    public function exists( $key ) {
        return isset( $_SESSION[ $this->_key ][ $key ] );
    }

    /**
     * Remove data using key.
     *
     * @param $key
     *
     * @return void
     */
    public function remove( $key ) {
        unset( $_SESSION[ $this->_key ][ $key ] );
    }

    /**
     * Clear all data using main key.
     *
     * @return void
     */
    public function clear_all()
    {
        unset( $_SESSION[ $this->_key ] );
    }

    /**
     * Return all storage data using main key.
     *
     * @param null $default
     * @return null
     */
    public function get_all($default = null)
    {
        return ! empty( $_SESSION[ $this->_key ] ) ? $_SESSION[ $this->_key ] : $default;
    }
}
