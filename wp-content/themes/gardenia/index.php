<?php 
/**
 *The index template file
**/
get_header(); ?>

<section class="blog-bg">
<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
  <div class="gg_menu_bg">
    <div class="webpage-container container">
      <div class="gg_menu clearfix">
	
	<div class="col-md-6 col-sm-6">  
        <h1><?php echo get_the_title( $page_id ); ?> </h1>                        
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
<?php 
    $gardenia_args = array( 
						'orderby'      => 'post_date', 
						'order'        => 'DESC',
						'post_type'    => 'post',
						'paged' => $paged,
						'post_status'    => 'publish'	
					  );
	$gardenia_query = new WP_Query($gardenia_args);
	if ($gardenia_query->have_posts() ) : while ($gardenia_query->have_posts()) : $gardenia_query->the_post(); 
?>
	  
        <div class="blog-main blog_1">			
		  			<div class="blog-rightsidebar-img">
			<?php if ( has_post_thumbnail() ) : ?>
					<?php the_post_thumbnail( 'gardenia-sidebar-image-size', array( 'alt' => get_the_title(), 'class' => 'img-responsive gardenia-featured-image') ); ?>
				<?php endif; ?>   						
			</div>
          <div class="blog-data">
            <div class="clearfix"></div>
            <div class="blog-content">
              <h3><a href="<?php echo esc_url( get_permalink() ) ; ?>"><?php the_title();?></a></h3>              
              <?php gardenia_entry_meta(); ?>              				
              <?php the_excerpt(); ?>  
            </div>
          </div>
        </div>            
<?php endwhile; endif; ?> 		   
		<div class="col-md-12 gardenia_pagination">      
			<?php gardenia_pagination(); ?>
		</div>       
      </div>  
      <?php get_sidebar(); ?>   
    </article>
  </div>
</div>
</section>
<?php get_footer(); ?>
