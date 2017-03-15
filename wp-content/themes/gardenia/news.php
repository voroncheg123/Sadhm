<?php 
/**
 Template Name: Novosti
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
	
  <div class="container middle_section">
  <div class="row breadWrap">
	<div class="col-md-3 col-sm-3">  
        <ol class="breadcrumb site-breadcumb">
          <?php if (function_exists('gardenia_custom_breadcrumbs')) gardenia_custom_breadcrumbs(); ?>
        </ol>
	</div>
	</div>
	<div class="row ">
			<?php/* get_sidebar();*/ ?> 
			
		<div class="col-md-8 news_body">
			<h4 class="news_title">Новости</h4>
            <?php
            //$id=23; // ID заданной рубрики
            $n=7;   // количество выводимых записей
            $recent = new WP_Query("cat=23&showposts=$n");
            while($recent->have_posts()) : $recent->the_post();
            ?>  <div class="row postWrap">
                <div class="col-md-4">
                    <a href="<?php the_permalink() ?>" rel="bookmark">
                        <? the_post_thumbnail("postImg");?>
                    </a>
                </div>
                <div class="col-md-8">
                    <a class="news_line_title" href="<?php the_permalink() ?>" rel="bookmark">
                        <?php the_title(); ?>
                    </a>
                    <p style="clear: both"></p>
                    <a href="<?=the_permalink(); ?>" class="rMo">Подробнее</a><span class="postDate"><?the_date();?></span>
                </div>
            </div>
            <?php endwhile; ?>
		</div> 
		<div class="col-md-4">
			<?php dynamic_sidebar( 'news_sidebar' ); ?>
		</div>	
    </div>
  </div>
</div>  
</section>
<!-- end main.php -->
<?php get_footer(); ?>
