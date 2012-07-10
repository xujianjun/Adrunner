<?php
/**
*AdrunnerWidget Class
*
*@Adrunner Widget
*@author Curran Xu
*@copyright 2012-2014
*@since 1.0.1
*/
include( "adrunner_include.php" );

class AdrunnerWidget extends AdrunnerWidgetFunctions {
	function AdrunnerWidget( ){
		parent::AdrunnerWidgetFunctions( );

		$widget_ops = array( 'classname' => 'adrunner-widget', 'description' => __( "Display adrunner advertisement", "adrunner" ));
		$this->WP_Widget( 'adrunner--widget', __( 'Adrunner Widget', "adrunner"), $widget_ops );
	}

	function field_id( $field ){
		echo  $this->get_field_id( $field );
	}

	function field_name( $field ){
		echo  $this->get_field_name( $field );
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['template'] = $new_instance['template'];

		return $instance;
	}

	function form( $instance ) {
		$defaults = array( 'title' => __('Adrunner', 'adrunner'), 'template' => 53);
		$instance = wp_parse_args( (array) $instance, $defaults );

		extract( $instance );
		?>

		<p>
	 		<label for="<?php $this->field_id( 'title') ?>"><?php _e( 'Title', "adrunner" ) ?>
				<input class="widefat" id="<?php $this->field_id( 'title') ?>" name="<?php $this->field_name( 'title' ) ?>" type="text" value="<?php echo esc_attr( $title ) ?>" />
			</label>
		</p>

		<?php
	}

	function widget( $args, $instance ) {
		extract( $args );
		extract( $instance );

		//start widget
		$output  = $before_widget ."\n";
		if( $title ) $output  .= $before_title. $title . $after_title . "\n";

		$slider = isset($slider) ? $slider : true;
		$output .= '<div class="adrunner-layout-v">';
		$output .= $this->get_adrunner_content($slider);
		$output .= '</div><!--.pop-layout-v-->';
		echo $output .=  $after_widget . "\n";

	}
}



