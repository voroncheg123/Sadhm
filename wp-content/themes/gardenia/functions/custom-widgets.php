<?php 
// Creating the widget 
class gardenia_widget extends WP_Widget {

function __construct() {
parent::__construct(
// Base ID of your widget
'gardenia_widget', 

// Widget name will appear in UI
__('Recent Work only images', 'gardenia'), 

// Widget description
array( 'description' => __( 'Only image for Recent work ', 'gardenia' ), ) 
);
}
// Creating widget front-end
// This is where the action happens
public function widget( $args, $instance ) {
	 extract( $args );
	
	$gardenia_title = apply_filters( 'widget_title', $instance['title'] );
	$gardenia_no_post = apply_filters( 'widget_title', $instance['no_post'] );
	// before and after widget arguments are defined by themes
	echo $before_widget;
	echo $args['before_title'];
		if ( ! empty( $gardenia_title ) ) {
			echo  $gardenia_title ; 
			}
	echo $args['after_title'];
			 $args = array(
	'posts_per_page'   => $gardenia_no_post,
	'offset'           => 0,
	'category'         => '',
	'category_name'    => '',
	'orderby'          => 'post_date',
	'order'            => 'DESC',
	'include'          => '',
	'exclude'          => '',
	'meta_key'         => '',
	'meta_value'       => '',
	'post_type'        => 'post',
	'post_mime_type'   => '',
	'post_parent'      => '',
	'post_status'      => 'publish',
	'suppress_filters' => true ); 
	$gardenia_post = get_posts( $args );
?>			
           <ul class="flickr">
				<?php foreach($gardenia_post as $gardenia_posts) : setup_postdata( $gardenia_posts ); 
				  $gardenia_image =  wp_get_attachment_image_src( get_post_thumbnail_id($gardenia_posts->ID),'gardenia-custom-widget-size'); 
				  if(!empty($gardenia_image[0])) {				
				?>
              <li>
				  <a class="flickr-link" href="<?php echo esc_url( get_permalink( $gardenia_posts->ID ) ); ?>" rel="" >
					  <img src="<?php echo esc_url($gardenia_image[0]);?>" alt="<?php echo $gardenia_posts->post_title; ?>" width="<?php echo $gardenia_image[1];?>" height="<?php echo $gardenia_image[2];?>" >
				 </a>
			  </li>
				<?php } endforeach; wp_reset_postdata();?>
            </ul>    
			<?php
		echo $after_widget;
}
		
// Widget Backend 
public function form( $instance ) {
	if ( isset( $instance[ 'title' ] ) &&  isset( $instance[ 'no_post' ] )  ) {
		$gardenia_title = $instance[ 'title' ];
		$gardenia_no_post=$instance[ 'no_post' ];
	}
	else {
		$gardenia_title = __( 'New title', 'gardenia' );
		$gardenia_no_post = 5;	
	}
	// Widget admin form
?>
<p>
<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:','gardenia'); ?></label> 
<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $gardenia_title ); ?>" />
<p></p>
<label for="<?php echo $this->get_field_id( 'no_post' ); ?>"><?php _e( 'Number of Post to show: ','gardenia' ); ?></label> 
<input class="widefat" size="2" id="<?php echo $this->get_field_id( 'no_post' ); ?>" name="<?php echo $this->get_field_name( 'no_post' ); ?>" type="number" value="<?php echo  $gardenia_no_post ; ?>" min="0">
</p>
<?php 
}
	
// Updating widget replacing old instances with new
public function update( $new_instance, $old_instance ) {
	$instance = array();
	$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
	$instance['no_post'] = ( ! empty( $new_instance['no_post'] ) ) ? strip_tags( $new_instance['no_post'] ) : '';
	return $instance;
	}
} // Class gardenia_widget ends here

// Register and load the widget
function gardenia_load_widget() {
	register_widget( 'gardenia_widget' );
}
add_action( 'widgets_init', 'gardenia_load_widget' );
?>
