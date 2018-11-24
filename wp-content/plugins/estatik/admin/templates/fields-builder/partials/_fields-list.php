<?php

/**
 * @var $entity string
 */

global $es_settings;

$removed_fields = $es_settings->property_removed_fields ? $es_settings->property_removed_fields : array();

if ( $sections = Es_FBuilder_Helper::get_sections( $entity ) ) : ?>
    <ul>
        <?php foreach ( $sections as $key => $section ) : ?>
            <li>
                <h1><?php echo $section[ 'label' ]; ?></h1>
                <?php if ( $fields = Es_FBuilder_Helper::get_entity_fields( $entity ) ) : ?>
                    <ul class="es-list__styled">
                        <?php foreach ( $fields as $field_key => $field ) :
                            $is_removed_field = in_array( $field_key, $removed_fields ); ?>

                            <?php if ( empty( $field['section'] ) || $field['section'] != $key ) continue; ?>
                            <?php require ( Es_Fields_Builder_Page::get_template_path( 'partials/_field' ) ); ?>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif;
