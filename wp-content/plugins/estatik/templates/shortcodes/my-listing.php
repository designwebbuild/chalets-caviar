<?php

/**
 * @var $properties WP_Query
 */

if ( $properties->have_posts() ) : ?>

    <?php do_action( "es_shortcode_before_" . $this->get_shortcode_name() . "_loop" ); ?>

    <div class="es-wrap <?php echo get_option( 'template' ); ?>">
        <?php if ( ! empty( $atts['show_filter'] ) ) : ?>
            <?php do_action( 'es_archive_sorting_dropdown' ); ?>
        <?php endif; ?>
        <ul class="es-listing es-layout-<?php echo $atts['layout']; ?>">
            <?php while ( $properties->have_posts() ) : $properties->the_post(); ?>
                <?php es_load_template( 'content-archive.php' ); ?>
            <?php endwhile; ?>
        </ul>

        <?php echo es_the_pagination( $properties, array(
            'type' => 'list',
        ) ); ?>
    </div>
    <?php do_action( 'es_shortcode_list_after' ); ?>
    <?php do_action( "es_shortcode_after_" . $this->get_shortcode_name() . "_loop" ); ?>
    <?php wp_reset_postdata(); ?>
<?php else: ?>
    <?php _e( 'Nothing to display', 'es-plugin' ); ?>
<?php endif;
