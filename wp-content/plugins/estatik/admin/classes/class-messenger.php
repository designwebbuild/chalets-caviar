<?php

/**
 * Class Es_Messenger
 */
class Es_Messenger implements Es_Messenger_Interface
{
    /**
     * Container messages key.
     *
     * @var string
     */
    protected $_key;

    /**
     * Es_Messenger constructor.
     * @param $key
     *    Message container key.
     */
    public function __construct( $key )
    {
        if ( ! session_id() ) {
            session_start();
        }

        $this->_key = $key;
    }

    /**
     * Set new message
     *
     * @param $message
     * @param $type
     */
    public function set_message( $message, $type )
    {
        $_SESSION [ $this->_key ][ $type ][] = $message;
    }

    /**
     * Render all messages and clear container.
     *
     * @return void
     */
    public function render_messages()
    {
        if ( ! empty( $_SESSION[ $this->_key ] ) ) {
            foreach ( $_SESSION[ $this->_key ] as $type => $messages ) {
                if ( ! empty( $messages ) ) {
                    foreach ( $messages as $message ) {
                        $message = $type == 'error' ?
                            '<i class="fa fa-times-circle-o" aria-hidden="true"></i> ' . $message :
                            '<i class="fa fa-check-circle-o" aria-hidden="true"></i> ' . $message;

                        echo '<p class="es-message es-message-' . $type . '" >' . $message . '</p>';
                    }
                }
            }

            $this->clean_container();
        }
    }

    /**
     * Return message container.
     *
     * @return null|array
     */
    public function get_messages()
    {
        return $_SESSION[ $this->_key ];
    }

    /**
     * Clean message container.
     *
     * @return void.
     */
    public function clean_container()
    {
        unset($_SESSION[ $this->_key ] );
    }
}
