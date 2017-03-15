<?php 
/**
 * The Archive template file
**/
get_header(); ?>

<section class="blog-bg">
  <div class="gg_menu_bg">
    <div class="webpage-container container">
      <div class="gg_menu clearfix">
	<div class="col-md-6 col-sm-6">  	  
<?php if (is_day()) { ?>
	<h1><?php _e('Archives ','gardenia'); echo ": ".get_the_date('F jS, Y'); ?></h1>	
<?php } ?> 
<?php if (is_month()) { ?>
	<h1><?php _e('Archives ','gardenia'); echo ": ".get_the_date('F, Y'); ?></h1>		
<?php } ?>  
<?php if (is_year()) { ?>
	<h1><?php _e('Archives ','gardenia'); echo ": ".get_the_date('Y'); ?></h1>
<?php } ?> 
	</div>
	<div class="col-md-6 col-sm-6">  
        <ol class="breadcrumb site-breadcumb">
           <?php if (function_exists('gardenia_custom_breadcrumbs')) gardenia_custom_breadcrumbs(); ?>
        </ol>        
    </div>
      </div>
    </div>
  </div>

  <div class="container">
    <article class="blog-article clearfix">
     
      <div class="col-md-9 blog-right-page col-sm-8">
	 <?php while ( have_posts() ) : the_post(); ?>		  
        <div class="blog-main blog_1">			
			<div class="blog-rightsidebar-img">
			<?php if ( has_post_thumbnail() ) : ?>
					<?php the_post_thumbnail( 'gardenia-sidebar-image-size', array( 'alt' => get_the_title(), 'class' => 'img-responsive gardenia-featured-image') ); ?>
				<?php endif; ?>   						
			</div>
          <div class="blog-data">
            <div class="clearfix"></div>
            <div class="blog-content">
              <h3><a href="<?php echo esc_url( get_permalink() ); ?>"><?php the_title();?></a></h3>              
              <?php gardenia_entry_meta(); ?>              				
              <?php the_excerpt(); ?>               
            </div>
          </div>
        </div>              
	<?php endwhile; ?>   
		<div class="col-md-12 gardenia_pagination"> 
		<?php gardenia_pagination();  ?>			
		</div>       
      </div>   
 <?php get_sidebar();     ?>  
    </article>
  </div>

</section>
<?php get_footer(); ?>
