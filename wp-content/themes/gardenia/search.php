<?php 
/**
 * The Search template file
**/
get_header(); ?>

<section class="blog-bg">
  <div class="gg_menu_bg">
    <div class="webpage-container container">
      <div class="gg_menu clearfix">
		  <div class="col-md-6 col-sm-6">          
			<h1><?php _e('Search Results for ','gardenia'); echo ": ".get_search_query(); ?></h1>	               
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
     <?php if ( have_posts() ) : ?>		  
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
	<?php else : ?> 
		 <div class="blog-content">
			 <h3><?php _e('Nothing Found','gardenia'); ?></h3>
			<p><?php _e('Sorry, but nothing matched your search terms. Please try again with some different keywords.','gardenia'); ?></p>
			<div class="col-md-4">
				<?php get_search_form(); ?>  
			</div>
		 </div>
	<?php endif; ?>
		<div class="col-md-12 gardenia_pagination">      
			<?php gardenia_pagination(); ?>
		</div>       
      </div>
       <?php get_sidebar();  ?>	   
    </article>   
  </div>
</section>
<?php get_footer(); ?>
