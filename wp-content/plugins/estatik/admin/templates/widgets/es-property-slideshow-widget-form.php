<?php

/**
 * @file Estatik search widget form file.
 *
 * @var array $instance
 * @var string $title
 * @var string $layout
 * @var string $display_type
 * @var array $pages_active
 * @var $this Es_Property_Slideshow_Widget
 */

$title       = ! empty( $instance['title'] )        ? $instance['title']          : null;
$layout      = ! empty( $instance['layout'] )       ? $instance['layout']         : null;
$slider_effect      = ! empty( $instance['slider_effect'] )       ? $instance['slider_effect']         : null;
$slides_num  = ! empty( $instance['slides_num'] )   ? $instance['slides_num']     : 1;
$filter_data = ! empty( $instance['filter_data'] )  ? $instance['filter_data']    : array();
$listing_ids = isset( $instance['prop_ids'] )    ? $instance['prop_ids']    : '';
$show_arrows = isset( $instance['show_arrows'] )    ? $instance['show_arrows']    : false;
$limit       = ! empty( $instance['limit'] )        ? $instance['limit']          : 20;
$margin       = ! empty( $instance['margin'] )        ? $instance['margin']          : 10;

$filter_data = ! empty( $filter_data[0] ) && is_array( $filter_data[0] ) ? $filter_data[0] : $filter_data;

?>

<div class="es-widget-wrap">

	<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title', 'es-plugin' ); ?>: </label>
		<input id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" type="text" class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo $title; ?>"/>
	</p>

	<p>
		<label><?php _e( 'Show arrows', 'es-plugin' ); ?>: </label>
		<label><?php _e( 'Yes', 'es-plugin' ); ?>
			<input type="radio" <?php checked( $show_arrows, 1 ); ?> class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'show_arrows' ) ); ?>" value="1"/>
		</label>
		<label><?php _e( 'No', 'es-plugin' ); ?>
			<input type="radio" <?php checked( $show_arrows, 0 ); ?> class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'show_arrows' ) ); ?>" value="0"/>
		</label>
	</p>

	<?php if ( $layouts = $this::get_slider_effects() ) : ?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'slider_effect' ) ); ?>"><?php _e( 'Slide Effect', 'es-plugin' ); ?>: </label>
			<select id="<?php echo esc_attr( $this->get_field_id( 'slider_effect' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'slider_effect' ) ); ?>" class="widefat">
				<?php foreach ( $layouts as $field ) : ?>
					<option <?php selected( $field, $slider_effect ); ?> value="<?php echo $field; ?>"><?php echo Es_Html_Helper::generate_label( $field ); ?></option>
				<?php endforeach; ?>
			</select>
		</p>
	<?php endif; ?>

	<?php if ( $data = $this::get_filter_fields_data() ) : ?>
		<select data-placeholder="<?php _e( '-- Select parameters for filtering --', 'es-plugin' ); ?>"
		        style="width: 100%"
		        multiple class="js-select2-tags"
		        name="<?php echo esc_attr( $this->get_field_name( 'filter_data[]' ) ); ?>"
		        id="<?php echo esc_attr( $this->get_field_id( 'filter_data' ) ); ?>">

			<?php foreach ( $data as $group => $items ) : ?>
				<?php if ( empty( $items ) ) continue; ?>

				<optgroup label="<?php echo $group; ?>">
					<?php foreach ( $items as $value => $item ) : ?>
						<?php if ( $item instanceof WP_Term) : ?>
							<option <?php selected( in_array( $item->term_id , $filter_data ) ); ?> value="<?php echo $item->term_id; ?>"><?php echo $item->name; ?></option>
						<?php else : ?>
							<option <?php selected( in_array( $item->term_id , $filter_data ) ); ?> value="<?php echo $value; ?>"><?php echo $item; ?></option>
						<?php endif; ?>
					<?php endforeach; ?>
				</optgroup>

			<?php endforeach; ?>
		</select>
	<?php endif; ?>

	<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'prop_ids' ) ); ?>"><?php _e( 'Listings IDs', 'es-plugin' ); ?>: </label>
		<input value="<?php echo $listing_ids; ?>" type="text" name="<?php echo esc_attr( $this->get_field_name( 'prop_ids' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'prop_ids' ) ); ?>">
	</p>

	<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'margin' ) ); ?>"><?php _e( 'Space between slides', 'es-plugin' ); ?>: </label>
		<input value="<?php echo $margin; ?>" type="number" min="0" name="<?php echo esc_attr( $this->get_field_name( 'margin' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'margin' ) ); ?>">
	</p>

	<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'limit' ) ); ?>"><?php _e( 'Limit', 'es-plugin' ); ?>: </label>
		<input value="<?php echo $limit; ?>" type="number" min="1" name="<?php echo esc_attr( $this->get_field_name( 'limit' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'limit' ) ); ?>">
	</p>

	<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'slides_num' ) ); ?>"><?php _e( 'Slides to show', 'es-plugin' ); ?>: </label>
		<input value="<?php echo $slides_num; ?>" type="number" min="1" name="<?php echo esc_attr( $this->get_field_name( 'slides_num' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'slides_num' ) ); ?>">
	</p>

	<?php if ( $layouts = $this::get_layouts() ) : ?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'layout' ) ); ?>"><?php _e( 'Layout', 'es-plugin' ); ?>: </label>
			<select id="<?php echo esc_attr( $this->get_field_id( 'layout' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'layout' ) ); ?>" class="widefat">
				<?php foreach ( $layouts as $field ) : ?>
					<option <?php selected( $field, $layout ); ?> value="<?php echo $field; ?>"><?php echo Es_Html_Helper::generate_label( $field ); ?></option>
				<?php endforeach; ?>
			</select>
		</p>
	<?php endif; ?>

	<?php do_action( 'es_widget_' . $this->id_base . '_page_access_block', $instance ); ?>

</div>
