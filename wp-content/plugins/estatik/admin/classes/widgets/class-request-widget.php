<?php

/**
 * Class Es_Request_Widget
 */
class Es_Request_Widget extends Es_Widget
{
    /**
     * @var int
     */
    const SEND_ADMIN = 1;

    /**
     * @inheritdoc
     */
    public function __construct()
    {
        parent::__construct( 'es_request_widget' , __( 'Estatik Request Form', 'es-plugin' ) );
    }

    /**
     * Return list of "send_to" selectbox field.
     *
     * @return mixed
     */
    public static function get_send_to_list()
    {
        return apply_filters( 'es_request_widget_get_send_to_list', array(
            self::SEND_ADMIN       => __( 'Admin', 'es-plugin' ),
        ) );
    }

    /**
     * Overridden render checker.
     *
     * @param $instance
     * @return bool
     */
    public static function can_render($instance)
    {
        return get_post_type() == Es_Property::get_post_type_name() && is_single();
    }

    /**
     * Function for register widget.
     *
     * @return void
     */
    public static function register()
    {
        register_widget( 'Es_Request_Widget' );
    }

    /**
     * Submit request widget handler.
     *
     * @return void
     */
    public static function submit_form()
    {
        $response = array();
        $nonce = 'es_request_send';

        if ( ! empty( $_POST[ $nonce ] ) && wp_verify_nonce( $_POST[ $nonce ], $nonce ) ) {
            $post = ! empty( $_POST['post_id'] ) ? get_post( $_POST['post_id'] ) : null;
            $check = false;

            if ( isset( $_POST['g-recaptcha-response'] ) ) {
                if ( ! empty( $_POST['g-recaptcha-response'] ) ) {
                    global $es_settings;
                    $secret = $es_settings->recaptcha_secret_key;

                    $verifyResponse = wp_safe_remote_get('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);

                    if ( ! empty( $verifyResponse['body'] ) ) {
                        $responseData = json_decode( $verifyResponse['body'] );

                        if ( ! empty( $responseData->success ) ) {
                            $check = true;
                        }
                    }
                }
            } else {
                $check = true;
            }

            if ( ! $check ) {
                $response = array( 'status' => 'error', 'message' => __( 'Invalid captcha.', 'es-plugin' ) );
            }

            if ( ! empty( $post->post_type ) && $post->post_type == Es_Property::get_post_type_name() && $check ) {
                if ( $emails = static::get_emails( $_POST['send_to'], $post ) ) {
                    $property = es_get_property( $post->ID );
                    $email = $_POST['email'];
                    $name = ! empty( $_POST['name'] ) ? $_POST['name'] : null;
                    $tel = ! empty( $_POST['tel'] ) ? $_POST['tel'] : null;
                    $message = $_POST['message'];

                    $msg = ! empty( $name ) ?  "<p><b>" . __( 'Name', 'es-plugin' ) . "</b>: " . $name . "</p>" : null;
                    $msg .= "<p style='font-weight: 300;'><b>" . __( 'Email', 'es-plugin' ) . "</b>: " . $email . "\n";
                    $msg .= ! empty( $tel ) ?  "<p><b>" . __( 'Phone', 'es-plugin' ) . "</b>: " . $tel . "</p>" : null;
                    $msg .= "<p style='font-weight: 300;'><b>" . __( 'Property ID', 'es-plugin' ) . "</b>: " . $post->ID . "\n";
                    $msg .= "<p style='font-weight: 300;'><b>" . __( 'Property Link', 'es-plugin' ) . "</b>: " . get_permalink( $post->ID ) . "</p>";
                    $msg .= "<p style='font-weight: 300;'><b>" . __( 'Property Address', 'es-plugin' ) . "</b>: " . $property->address . "</p>";
                    $msg .= "<p style='font-weight: 300; line-height: 1.6;'><b>" . __( 'Request', 'es-plugin' ) . "</b>: " . $message . "\n";

                    $subject = apply_filters( 'es_request_form_email_subject', stripslashes( $_POST['subject'] ) );
                    $message = apply_filters( 'es_request_form_email_message', $msg, $_POST );

	                $message = es_email_content( 'emails/property-request.php', array(
	                	'message' => $message,
		                'title' => sprintf( __( 'Request about property #%s', 'es-plugin' ), $post->ID ),
	                ) );

                    if ( wp_mail( $emails, $subject, $message ) ) {
                        $response = array( 'status' => 'success', 'message' => __( 'Thank you for your message! We will contact you as soon as we can.', 'es-plugin' ) );
                    } else {
                        $response = array( 'status' => 'error', 'message' => __( 'Your message wasn\'t sent. Please, contact support.', 'es-plugin' ) );
                    }
                }
            } else {
                $response = ! empty( $response ) ? $response : array( 'status' => 'error', 'message' => __( 'Incorrect post.', 'es-plugin' ) );
            }

            $response = apply_filters( 'es_request_form_response', $response );

            $template = apply_filters( 'es_request_form_response_template_path', ES_PLUGIN_PATH . '/templates/partials/request-form-response.php' );

            ob_start();
            include ( $template );

            wp_die( json_encode( ob_get_clean() ) );
        }
    }

    /**
     * Return emails for sending.
     *
     * @param $type
     * @param $post
     * @return mixed
     */
    protected static function get_emails( $type, WP_Post $post )
    {
        $emails = array();

        $emails[] = get_option( 'admin_email' );

        return apply_filters( 'es_request_form_get_emails', $emails, $type, $post );
    }

    /**
     * @inheritdoc
     */
    protected function get_widget_template_path()
    {
        return ES_PLUGIN_PATH . '/admin/templates/widgets/es-request-widget.php';
    }

    /**
     * @return string
     */
    protected function get_widget_form_template_path()
    {
        return ES_PLUGIN_PATH . '/admin/templates/widgets/es-request-widget-form.php';
    }
}

add_action( 'widgets_init', array( 'Es_Request_Widget', 'register' ) );
add_action( 'wp_ajax_es_request_send', array( 'Es_Request_Widget', 'submit_form' ) );
add_action( 'wp_ajax_nopriv_es_request_send', array( 'Es_Request_Widget', 'submit_form' ) );
