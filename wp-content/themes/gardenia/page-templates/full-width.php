<?php 
/**
 * Template Name: Full Width
**/
get_header(); ?>
<section class="blog-bg"> 	
  <div class="gg_menu_bg">
    <div class="webpage-container container">
      <div class="gg_menu clearfix">
		<div class="col-md-6 col-sm-6">  
			<h1><?php the_title(); ?></h1>
        </div>
      </div>
    </div>
  </div>  
  
  <div class="container">
    <article class="blog-article clearfix">			            
      <div class="col-md-12 full-width">		
	<?php while ( have_posts() ) : the_post();  ?>	
        <div class="blog-main blog_1 ">
			<div class="blog-rightsidebar-img">
			<?php if ( has_post_thumbnail() ) : ?>
					<?php the_post_thumbnail( 'gardenia-full-width', array( 'alt' => get_the_title(), 'class' => 'img-responsive gardenia-featured-image') ); ?>
				<?php endif; ?>   						
			</div>
		    
          <div class="blog-data">
		    <div class="col-md-4 col-sm-4">  
				<ol class="breadcrumb site-breadcumb">
					<?php if (function_exists('gardenia_custom_breadcrumbs')) gardenia_custom_breadcrumbs(); ?>
				</ol>
			</div>
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
    <?php endwhile; ?>
    <div class="comments">
		<?php comments_template( '', true ); ?>
	</div>                 
      </div>         
    </article>
  </div>  
</section>
<?php get_footer(); ?>
