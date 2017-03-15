<?php 
/*
 * Green Garden Enqueue css and js files
*/
function gardenia_enqueue()
{
	wp_enqueue_style('gardenia-bootstrap',get_template_directory_uri().'/css/bootstrap.css',array());
	wp_enqueue_style('gardenia-style',get_stylesheet_uri(),array());		
	wp_enqueue_style('gardenia-font-awesome',get_template_directory_uri().'/css/font-awesome.css',array());
	wp_enqueue_style('gardenia-eleganticons',get_template_directory_uri().'/css/eleganticons.css',array());	
	
	wp_enqueue_script('gardenia-bootstrapjs',get_template_directory_uri().'/js/bootstrap.js',array('jquery'));    
    if(is_page_template('page-templates/front-page.php')){  		
		wp_enqueue_style('gardenia-owl-curosal-css', get_template_directory_uri() . '/css/owl.carousel.css');
		wp_enqueue_script( 'gardenia-owlcarousel-js', get_template_directory_uri() . '/js/owl.carousel.js', array('jquery')); 
		}
	if ( is_singular() ) wp_enqueue_script( "comment-reply" ); 
}
add_action('wp_enqueue_scripts', 'gardenia_enqueue');
