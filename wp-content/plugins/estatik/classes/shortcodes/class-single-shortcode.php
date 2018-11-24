<?php

/**
 * Class Es_Single_Shortcode
 */
class Es_Single_Shortcode extends Es_Shortcode
{
    /**
     * Return proeprty single template name.
     *
     * @return mixed
     */
    public function get_template_path()
    {
        return apply_filters( 'es_single_template_path', ES_TEMPLATES . 'content-single.php' );
    }

    /**
     * @inheritdoc
     */
    public function build( $atts = array() )
    {
    	global $post, $es_property; $temp_post = $post;

    	$content = null;

    	$atts = shortcode_atts( array(
    		'id' => $post->ID,
	    ), $atts );

        $post = get_post( $atts['id'] );
        setup_postdata( $post );
        $es_property = es_get_property( $atts['id'] );

        ob_start();
        es_load_template( 'content-single.php', 'front', 'es_single_template_path' );
        $post = $temp_post;
        wp_reset_postdata();

        do_action( 'es_shortcode_after', $this->get_shortcode_name() );

        $content =  ob_get_clean();

        return $content;
    }

    /**
     * @inheritdoc
     */
    public function get_shortcode_name()
    {
        return 'es_single';
    }
}
