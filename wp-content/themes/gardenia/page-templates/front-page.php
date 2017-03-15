<?php
/*
* Template Name: Home Page
*/
?>
<?php get_header(); 
$gardenia_options = get_option( 'gardenia_theme_options' );
?>

<section class="section-titel">
<?php $gardenia_loop_active = 0; ?>
<div class="banner">    		
	<div id="slider-carousel" class="carousel slide garden-slider" data-ride="carousel">
		<div class="carousel-inner slider">
			<?php for($gardenia_loop=1 ; $gardenia_loop <=5 ; $gardenia_loop++):?>
			<?php if(!empty($gardenia_options['slider-img-'.$gardenia_loop])){
				$gardenia_loop_active++;	
				$gardenia_image = getimagesize($gardenia_options['slider-img-'.$gardenia_loop]);			
			?>
			<div class="item <?php if($gardenia_loop_active == 1) { echo "active"; } ?>">
				<?php if(!empty($gardenia_options['slidelink-'.$gardenia_loop])) { ?>
				<a href="<?php echo esc_url($gardenia_options['slidelink-'.$gardenia_loop]);?>" target="_blank">
					<img src="<?php echo esc_url($gardenia_options['slider-img-'.$gardenia_loop]); ?>" width="<?php echo $gardenia_image[0]; ?>" height="<?php echo $gardenia_image[1]; ?>" alt="<?php echo $gardenia_loop;?>" />
				</a>
				<?php } else { ?>                          
					<img src="<?php echo esc_url($gardenia_options['slider-img-'.$gardenia_loop]); ?>" width="<?php echo $gardenia_image[0]; ?>" height="<?php echo $gardenia_image[1]; ?>" alt="<?php echo $gardenia_loop;?>" />
				<?php } ?>	
			</div>  
			<?php }  endfor; ?>                                          			
		</div>
		<?php if($gardenia_loop_active >= 2) { ?>		
		<a class="left carousel-control slider_button" href="#slider-carousel" data-slide="prev">
			<i class="glyphicon glyphicon-circle-arrow-left"></i>
		</a>
		<a class="right carousel-control slider_button" href="#slider-carousel" data-slide="next">
			<i class="glyphicon glyphicon-circle-arrow-right"></i>
		</a>
		<?php } ?>
	</div>
</div>
<div class="portfolio-bg">
<div class="container webpage-container">
<?php  if(!empty($gardenia_options['home-title']) || !empty($gardenia_options['home-content']) ) { ?>
    <div class="section_row_1 text-center title-main">
		<?php if(!empty($gardenia_options['home-title'])) {?>
      <h2><span><?php echo esc_html($gardenia_options['home-title']);?></span></h2>
		<?php } ?>		
			<?php if(!empty($gardenia_options['home-content'])) {?>
				<p class="fet-p">
		  <?php echo esc_html($gardenia_options['home-content']);?>
				</p>
			<?php } ?>		
    </div>
<?php } ?> 
    <div class="row">
      <div class="section_row_2 clearfix">	
		   <?php for($gardenia_loop=1 ; $gardenia_loop <5 ; $gardenia_loop++):?>
		   <?php if(!empty($gardenia_options['home-icon-'.$gardenia_loop]) || !empty($gardenia_options['section-title-'.$gardenia_loop]) && !empty($gardenia_options['section-content-'.$gardenia_loop]) ) { ?>
        <div class="col-xs-12 col-sm-6 col-md-3 circle-box">
          <div class="img_inline text-center center-block our-feat">
			   <div class="row_img">
			   <?php if(!empty($gardenia_options['home-icon-'.$gardenia_loop])){ ?>
			   <?php $gardenia_image = esc_url($gardenia_options['home-icon-'.$gardenia_loop]);
					 $gardenia_id = gardenia_get_image_id($gardenia_image);
					 $gardenia_image = wp_get_attachment_image_src( $gardenia_id, 'gardenia-home-tab-size' ); 
			   ?>           
				<img alt="<?php echo $gardenia_loop;?>" class="img-circle" src="<?php echo esc_url($gardenia_image[0]); ?>" width="<?php  echo $gardenia_image[1]; ?>" height="<?php  echo $gardenia_image[2]; ?>">			
				<?php } ?>
				</div>
            <div class="row_content">				
			<?php if(!empty($gardenia_options['section-title-'.$gardenia_loop])){
						if(!empty($gardenia_options['section-link-'.$gardenia_loop])){	  ?>
							<a href="<?php echo esc_url($gardenia_options['section-link-' . $gardenia_loop]) ?>">  
								<?php echo '<h4>'. esc_html($gardenia_options['section-title-'.$gardenia_loop]) . '</h4>';?>
							</a>  
						<?php } else {
								 echo '<h4>'. esc_html($gardenia_options['section-title-'.$gardenia_loop]) . '</h4>';
						} 	
				   } ?>              
				  <?php if(!empty($gardenia_options['section-content-'.$gardenia_loop])){ ?>
				   <p><?php echo esc_html($gardenia_options['section-content-'.$gardenia_loop]); ?></p>
				  <?php } ?>             
            </div>
          </div>
        </div> 
        <?php  } ?>
        <?php endfor; ?>           
      </div>
    </div> 
  </div>  
</div>
  <div class="section_row_3 clearfix portfolio-bg  blog">
    <div class="container">
      <div class="section_row_1 text-center title-main">
			<?php if(!empty($gardenia_options['post-title'])) {?>
				<h2><span><?php echo esc_html($gardenia_options['post-title']); ?></span></h2>
			<?php } ?>
        <p class="fet-p2">
			<?php if(!empty($gardenia_options['post-content'])) { ?>
				<?php echo esc_html($gardenia_options['post-content']); ?>
			<?php } ?>
		</p>
      </div>    
      <div class="fancy_list_wrapper">
		  <?php $category= $gardenia_options['post-category-latest']; ?>
<?php	$gardenia_args = array(
	    'cat'  => $category,
	    'posts_per_page' => '3',
	    'ignore_sticky_posts' => '1',	   
		'meta_query' => array(
			array(
			 'key' => '_thumbnail_id',
			 'compare' => 'EXISTS'
			),
		)
	  );	
    	$gardenia_query=new WP_Query($gardenia_args); ?>
		<?php if ( $gardenia_query->have_posts() ) { ?> 
			 <?php while($gardenia_query->have_posts()) {  $gardenia_query->the_post(); ?>  
        <div class="col-md-4 col-sm-4 fancy_list_item">
          <?php $gardenia_image = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'gardenia-frontpage-size' ); ?>          
			  <?php if($gardenia_image[0] != "") { ?> 
          <div class="fancy_image">
			 <a href="<?php echo esc_url( get_permalink() );?>">
				<img alt="<?php the_title(); ?>" src="<?php echo esc_url($gardenia_image[0]); ?>" width="<?php  echo $gardenia_image[1]; ?>" height="<?php  echo $gardenia_image[2]; ?>">
			 </a> 
		  </div>
			  <?php } ?>
          <h3><a class="animate1" href="<?php echo esc_url( get_permalink() );?>"><?php echo get_the_title(); ?></a></h3>
          <div class="fancy_categories">
          	<?php gardenia_entry_meta(); ?>   
          </div>
          <p><?php echo get_the_excerpt(); ?></p>
          <p><span class="glyphicon glyphicon-arrow-right"><a href="<?php echo esc_url( get_permalink() );?>" title="<?php _e('Continue reading', 'gardenia');?>"><?php _e('Continue reading', 'gardenia');?></a></span></p>
        </div>       
           <?php } }?> 
        <div class="clear"></div>
      </div>
    </div>
  </div>  
</section>
<?php get_footer(); ?>
