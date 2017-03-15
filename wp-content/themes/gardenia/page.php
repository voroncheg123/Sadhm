<?php 
/**
 * Main Page template file
**/
get_header(); ?>
<section class="blog-bg">  
<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
  <div class="gg_menu_bg">
    <div class="webpage-container container">
      <div class="gg_menu clearfix">
		  <div class="col-md-6 col-sm-6">  
        <h1><?php the_title(); ?></h1>
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
	<?php while ( have_posts() ) : the_post();  ?>	
        <div class="blog-main blog_1 ">
			<div class="blog-rightsidebar-img">
			<?php if ( has_post_thumbnail() ) : ?>
					<?php the_post_thumbnail( 'gardenia-sidebar-image-size', array( 'alt' => get_the_title(), 'class' => 'img-responsive gardenia-featured-image') ); ?>
				<?php endif; ?>   						
			</div>
          <div class="blog-data">
            <div class="clearfix"></div>
            <div class="blog-content">
              <?php the_content();
			  		wp_link_pages( array(
		            'before'      => '<div class="col-md-6 col-xs-6 no-padding-lr prev-next-btn">' . __( 'Pages', 'gardenia' ) . ':',
		            'after'       => '</div>',
		            'link_before' => '<span>',
		            'link_after'  => '</span>',
		            ) ); ?>         
            </div>
          </div>
        </div> 
    <div class="comments">
		<?php comments_template( '', true ); ?>
	</div>     
    <?php endwhile; ?>                
      </div>
      <?php get_sidebar(); ?>         
    </article>
  </div>
</div>  
</section>
<?php get_footer(); ?>
