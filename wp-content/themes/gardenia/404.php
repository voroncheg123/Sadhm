<?php 
/**
 * 404 page template file
**/
get_header();?>
<section class="blog-bg">	
  <div class="gg_menu_bg">
    <div class="webpage-container container">
      <div class="gg_menu clearfix">        
        <h1><?php _e('Epic 404 - Article Not Found', 'gardenia'); ?></h1>
        <ol class="breadcrumb site-breadcumb">
          <?php if (function_exists('gardenia_custom_breadcrumbs')) gardenia_custom_breadcrumbs(); ?>
        </ol>
      </div>
    </div>
  </div>  
  <div class="container">
    <article class="blog-article clearfix">			            
      <div class="col-md-12 full-width">         
          <div class="blog-data">
            <div class="clearfix"></div>
            <div class="blog-content">	
			  <h1><?php _e('Epic 404 - Article Not Found','gardenia'); ?></h1>			
			  <p><?php _e("This is embarassing. We can't find what you were looking for.",'gardenia');?></p>
			  <p><?php _e('Whatever you were looking for was not found, but maybe try looking again or search using the form below.','gardenia'); ?></p>
            </div>
            <div class="row">
                <div class="col-md-4">                   
                    <?php get_search_form(); ?>                   								
                 </div>
            </div>
          </div>                      
      </div>         
    </article>
  </div>  
</section>
<?php get_footer(); ?>
