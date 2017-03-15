<?php 
/*
Template Name: Main
*/
$gardenia_options = get_option('gardenia_theme_options');
?>

<!-- start main.php -->


<!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if lt IE 9]>
<script src="<?php echo esc_url(get_template_directory_uri()); ?>/js/html5.js"></script>
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
    <!--<![endif]-->
<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width">
	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">

	<?php if (!empty($gardenia_options['favicon'])) { ?>
		<link rel="shortcut icon" href="<?php echo esc_url($gardenia_options['favicon']); ?>"> 
	<?php } ?>	
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<header class="themepage">
		<div class="container gardenia-container">
		<div class="col-md-12"> 
			<div class="header-main row">
<div class="col-md-3 col-sm-3 theme-menu">
					<div class="adress_logo_line"><h4><b><p>г. Ханты-Мансийск </p><p>ул. Строителей 104</p><b></h4></div>
				</div>
				<div class="col-md-6 col-sm-6 theme-menu">
										<?php if (!empty($gardenia_options['logo'])) { 
						$gardenia_options_image = getimagesize($gardenia_options['logo']) ;						
					?>
						<a href="<?php echo esc_url(home_url('/')); ?>"><img  src="/wp-content/uploads/2016/05/cropped-logo-t.png" ></a> 
					<?php } else { ?>		  
						<a class="home-link" style="color:#<?php echo get_header_textcolor(); ?>!important;" href="<?php echo esc_url(home_url('/')); ?>" title="<?php echo esc_attr(get_bloginfo('name', 'display')); ?>" rel="home">
							<h2 class="site-title"><?php bloginfo('name'); ?></h2>	
						</a>
						<?php	$gardenia_description = get_bloginfo( 'description', 'display' );
					if ( $gardenia_description || is_customize_preview() ) : ?>
						<p class="site-description"><?php echo $gardenia_description; ?></p>
					<?php endif;?>			
						
					<?php } ?>
				</div>				<div class="col-md-3 col-sm-3" test>		 

        <div class="logo_phone_line"><h4><b><p>8 (3467) 32-33-31</p><b><p><i class="fa fa-vk" aria-hidden="true"></i>
<i class="fa fa-odnoklassniki-square" aria-hidden="true"></i>
<i class="fa fa-rss" aria-hidden="true"></i>
</p></h4></div>
				</div>
				
				
			</div>
		</div>
	</div>  
<?php if (get_header_image()) { ?>
                <div class="custom-header-img">
                    <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                        <img src="<?php header_image(); ?>" width="<?php echo get_custom_header()->width; ?>" height="<?php echo get_custom_header()->height; ?>" alt="<?php echo esc_attr(get_the_title()); ?>">
                    </a>
                </div>
<?php } ?>  

</header>	
		
<!--вывод меню под слайдером -->
<div class="col-md-12 menuWrap">
				
				<div class="row">
					<div class="container">
						<div class="underMenu col-md-9">
							<nav class="gg-nav">          
								<div class="navbar-header">
									<?php if (has_nav_menu('primary')) { ?>
										<button type="button" class="navbar-toggle navbar-toggle-top sort-menu-icon collapsed" data-toggle="collapse" data-target=".navbar-collapse"> <span class="sr-only"><?php _e('Toggle navigation', 'gardenia'); ?></span> <span class="icon_menu-square_alt"></span></button>
									<?php } ?>
								</div>			  
								<?php
								$gardenia_defaults = array(
									'theme_location' => 'primary',
									'container' => 'div',
									'container_class' => 'navbar-collapse collapse gg-navbar main-menu unMenu',
									'container_id' => '',
									'menu_class' => 'navbar-collapse collapse gg-navbar',
									'menu_id' => '',
									'submenu_class' => '',
									'echo' => true,							
									'before' => '',
									'after' => '',
									'link_before' => '',
									'link_after' => '',
									'items_wrap' => '<ul id="menu" class="gg-menu">%3$s</ul>',
									'depth' => 0,
									'walker' => ''
								);
								if (has_nav_menu('primary')) {
									wp_nav_menu($gardenia_defaults);
								}
								?>        
							</nav>
						</div> 
						<div class="col-md-3">
							<?php get_search_form(); ?>
						</div>
					</div>
				</div>
			</div>
<div class="headSlWrap">			
</div>	
<!-- end header.php -->

