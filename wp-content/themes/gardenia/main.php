<?php 
/**
 Template Name: Glavnaya
**/
get_header(); 

//echo do_shortcode( '[pjc_slideshow slide_type="frontslider"]' );

?>


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

<section class="blog-bg">
<div class="container">

        <?$id=30; // ID рубрики слайдера
        $n=5;   // количество выводимых слайдов
        $recent = new WP_Query("cat=$id&showposts=$n&order=ASC");?>


       <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">

        <div class="carousel-inner" role="listbox">
            <?foreach($recent->posts as $k => $v){?>

            <div class="item <?if($k == 0){
                echo "active";
            } ?>">
                <?=get_the_post_thumbnail($recent->posts[$k]->ID, "sliderImg");?>
                <div class="carousel-caption">
                    <h3><?=$recent->posts[$k]->post_title?></h3>
                    <p><?=$recent->posts[$k]->post_content;?></p>
                </div>
            </div>

                <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
                    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
                    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            <?}?>
        </div>

        </div>




		<!--test111 
			<?	
				var_dump($recent);
			?>
		-->
</div>  
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
	<div class="row ">
			<?php/* get_sidebar();*/ ?> 
		<div class="col-md-3">
			<?php dynamic_sidebar( 'right_sidebar' ); ?>
		</div>	
		<div class="col-md-6 news_body">

			<?php
				//$id=23; // ID заданной рубрики
				$n=7;   // количество выводимых записей
				$recent = new WP_Query("cat=23,28&showposts=$n");
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
            11111
		</div>
		<div class="col-md-3">
			<?php dynamic_sidebar( 'left_sidebar' ); ?>	
		</div>    
    </div>
  </div>
</div>  
</section>
<div class="map">
<div id="ymaps-map-id_50799940" style="width: auto; height: 445px;"></div><script type="text/javascript">function fid_50799940(ymaps) {var objects = [];var events = {};try{}catch(e){alert(e);};var map; objects["map1"] = map = new ymaps.Map("ymaps-map-id_50799940", {center: [61.01259025204375,69.04694749999993], zoom: 15,type: "yandex#map",behaviors:['drag','dblClickZoom']});map.controls.add("typeSelector",{"top":null,"right":10}).add("zoomControl",{"top":null,"left":10});};</script><script type="text/javascript" src="http://api-maps.yandex.ru/2.0-stable/?load=package.full&lang=ru-RU&onload=fid_50799940"></script><!-- Этот блок кода нужно вставить в ту часть страницы, где вы хотите разместить карту (конец) -->
</div>
<!-- end main.php -->
<?php get_footer(); ?>
