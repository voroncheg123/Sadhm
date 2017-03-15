<?php
function gardenia_options_init(){
 register_setting( 'gardenia_option', 'gardenia_theme_options','gardenia_option_validate');
} 
add_action( 'admin_init', 'gardenia_options_init' );
function gardenia_option_validate($input)
{
	 $input['logo'] = esc_url_raw( $input['logo'] );
	 $input['favicon'] = esc_url_raw( $input['favicon'] );
	 $input['footertext'] = sanitize_text_field( $input['footertext'] );
	 $input['email'] = sanitize_email( $input['email'] );
	 $input['phone'] = wp_filter_nohtml_kses( $input['phone'] );
	 $input['home-title'] = sanitize_text_field( $input['home-title'] );
	 $input['home-content'] = sanitize_text_field( $input['home-content'] );
	 $input['post-title'] = sanitize_text_field( $input['post-title'] );
	 $input['post-content'] = sanitize_text_field( $input['post-content'] );	 
	 $input['twitter'] = esc_url_raw( $input['twitter'] );
	 $input['fburl'] = esc_url_raw( $input['fburl'] );
	 $input['pinterest'] = esc_url_raw( $input['pinterest'] );
	 $input['linkedin'] = esc_url_raw( $input['linkedin'] );
	 $input['gplus'] = esc_url_raw( $input['gplus'] );
	 $input['rss'] = esc_url_raw( $input['rss'] );	 
	
	 for($gardenia_i=1; $gardenia_i <=5 ;$gardenia_i++ ):
	 $input['slider-img-'.$gardenia_i] = esc_url_raw( $input['slider-img-'.$gardenia_i] );
	 $input['slidelink-'.$gardenia_i] = esc_url_raw( $input['slidelink-'.$gardenia_i]);
	 endfor;
	 
	 for($gardenia_section_i=1; $gardenia_section_i <=4 ;$gardenia_section_i++ ):
	 $input['home-icon-'.$gardenia_section_i] = esc_url_raw( $input['home-icon-'.$gardenia_section_i]);
	 $input['section-title-'.$gardenia_section_i] = sanitize_text_field($input['section-title-'.$gardenia_section_i]);
	 $input['section-content-'.$gardenia_section_i] = sanitize_text_field($input['section-content-'.$gardenia_section_i]);	
	 $input['section-link-' . $gardenia_section_i] = esc_url_raw($input['section-link-' . $gardenia_section_i]); 
	 endfor;
	 
    return $input;
}

function gardenia_framework_load_scripts($hook){
	if($GLOBALS['gardenia_menu'] != $hook){
		return;
	}
	wp_enqueue_media();
	wp_enqueue_style( 'gardenia-theme-option-css', get_template_directory_uri(). '/theme-options/css/theme-option.css' ,false, '1.0.0');	
	wp_enqueue_script( 'gardenia-options-custom-js', get_template_directory_uri(). '/theme-options/js/theme-option.js', array( 'jquery' ) );
	wp_enqueue_script( 'gardenia-media-uploader', get_template_directory_uri(). '/theme-options/js/media-uploader.js', array( 'jquery') );		

}
add_action( 'admin_enqueue_scripts', 'gardenia_framework_load_scripts' );
function gardenia_framework_menu_settings() {
	$gardenia_menu = array(
				'page_title' => __( 'Theme Options', 'gardenia_framework'),
				'menu_title' => __('Theme Options', 'gardenia_framework'),
				'capability' => 'edit_theme_options',
				'menu_slug' => 'gardenia_framework',
				'callback' => 'gardenia_framework_page'
				);
	return apply_filters( 'gardenia_framework_menu', $gardenia_menu );
}
add_action( 'admin_menu', 'gardenia_options_add_page' ); 
function gardenia_options_add_page() {
	$gardenia_menu = gardenia_framework_menu_settings();
   	$GLOBALS['gardenia_menu']=add_theme_page($gardenia_menu['page_title'],$gardenia_menu['menu_title'],$gardenia_menu['capability'],$gardenia_menu['menu_slug'],$gardenia_menu['callback']);
} 
function gardenia_framework_page(){ 
		global $select_options; 
		if ( ! isset( $_REQUEST['settings-updated'] ) ) 
		$_REQUEST['settings-updated'] = false;		

?>
<div class="gardenia-themes">
	<form method="post" action="options.php" id="form-option" class="theme_option_ft">
  <div class="gardenia-header">
    <div class="logo">
      <?php
		$gardenia_image=get_template_directory_uri().'/theme-options/images/logo.png';
		echo "<a href='http://fruitthemes.com' target='_blank'><img src='".esc_url($gardenia_image)."' alt='FruitThemes' /></a>";
		?>
    </div>
    <div class="header-right">		
    	<div class='btn-save'><input type='submit' class='button-primary' value='<?php _e('Save Options','gardenia'); ?>' />
		</div>     
    </div>
  </div>
  <div class="gardenia-details">
    <div class="gardenia-options">
      <div class="right-box">
        <div class="nav-tab-wrapper">
          <div class="option-title">
            <h2><?php _e('Theme Options','gardenia'); ?></h2>
          </div>  
          <ul>
            <li><a id="options-group-1-tab" class="nav-tab basicsettings-tab" title="<?php _e('Basic Settings','gardenia');?>" href="#options-group-1"><?php _e('Basic Settings','gardenia');?></a></li>
            <li><a id="options-group-2-tab" class="nav-tab homepagesettings-tab" title="<?php _e('Home Page Settings','gardenia');?>" href="#options-group-2"><?php _e('Home Page Settings','gardenia');?></a></li>
            <li><a id="options-group-3-tab" class="nav-tab socialsettings-tab" title="<?php _e('Social Settings','gardenia');?>" href="#options-group-3"><?php _e('Social Settings','gardenia');?></a></li>  
             <li><a id="options-group-4-tab" class="nav-tab profeatures-tab" title="<?php _e('PRO Theme Features','gardenia');?>" href="#options-group-4"><?php _e('PRO Theme Features','gardenia');?></a></li>         
  		  </ul>
        </div>
      </div>
      <div class="right-box-bg"></div>
      <div class="postbox left-box"> 
        <!--======================== F I N A L - - T H E M E - - O P T I O N ===================-->
          <?php settings_fields( 'gardenia_option' );  
		$gardenia_options = get_option( 'gardenia_theme_options' );
		
		 ?>
        
            <!-------------- Header group ----------------->
          <div id="options-group-1" class="group theme-option-inner-tabs">   
                 
          	<div class="section theme-tabs theme-logo">
            <a class="heading theme-option-inner-tab active" href="javascript:void(0)"><?php _e('Site Logo','gardenia'); ?></a>
            <div class="theme-option-inner-tab-group active">
				<div class="explain"><?php _e('Size of Logo should be exactly 300x120px for best results.','gardenia'); ?></div>
              	<div class="ft-control">
					
                <input id="logo-img" class="upload" type="text" name="gardenia_theme_options[logo]" 
                            value="<?php if(!empty($gardenia_options['logo'])) { echo esc_attr($gardenia_options['logo']); } ?>" placeholder="<?php _e('No file chosen','gardenia'); ?>" />
                <input id="upload_image_button" class="upload-button button" type="button" value="<?php _e('Upload','gardenia'); ?>" />
                <div class="screenshot" id="logo-image">
                  <?php if(!empty($gardenia_options['logo'])) { ?>
					  <img src="<?php echo esc_url($gardenia_options['logo']); ?>" />
					  <a class='remove-image'> </a>
				  <?php } ?>
                </div>
              </div>
              
            </div>
          </div>
            <div class="section theme-tabs theme-favicon">
              <a class="heading theme-option-inner-tab" href="javascript:void(0)"><?php _e('Favicon','gardenia'); ?></a>
              <div class="theme-option-inner-tab-group">
              	<div class="explain"><?php _e('Size of favicon should be exactly 32x32px for best results.','gardenia'); ?></div>
                <div class="ft-control">
                  <input id="favicon-img" class="upload" type="text" name="gardenia_theme_options[favicon]" 
                            value="<?php if(!empty($gardenia_options['favicon'])) { echo esc_attr($gardenia_options['favicon']); } ?>" placeholder="<?php _e('No file chosen','gardenia'); ?>" />
                  <input id="upload_image_button1" class="upload-button button" type="button" value="<?php _e('Upload','gardenia'); ?>" />
                  <div class="screenshot" id="favicon-image">
                    <?php  if(!empty($gardenia_options['favicon'])) { ?>
						<img src="<?php echo esc_url($gardenia_options['favicon']); ?>" />
						<a class='remove-image'> </a>
					<?php } ?>
                  </div>
                </div>
                
              </div>
            </div>     
            <div id="section-footertext" class="section theme-tabs">
            	<a class="heading theme-option-inner-tab" href="javascript:void(0)"><?php _e('Copyright Text','gardenia'); ?></a>
              <div class="theme-option-inner-tab-group">
              	<div class="ft-control">
              		<div class="explain"><?php _e('Some text regarding copyright of your site, you would like to display in the footer.','gardenia'); ?></div>                
                  	<input type="text" id="footertext" class="of-input" name="gardenia_theme_options[footertext]" size="32"  value="<?php if(!empty($gardenia_options['footertext'])) { echo esc_html($gardenia_options['footertext']); } ?>">
                </div>                
              </div>
            </div>

            <div id="section-email" class="section theme-tabs">
            	<a class="heading theme-option-inner-tab" href="javascript:void(0)"><?php _e('Email','gardenia'); ?></a>
              <div class="theme-option-inner-tab-group">
              	<div class="ft-control">
              		<div class="explain"><?php _e('Enter e-mail id for your site , you would like to display in the Top Header.','gardenia'); ?></div>                
                  	<input type="text" id="email" class="of-input" name="gardenia_theme_options[email]" size="32"  value="<?php if(!empty($gardenia_options['email'])) { echo esc_attr($gardenia_options['email']); } ?>">
                </div>                
              </div>
            </div>

            <div id="section-phone" class="section theme-tabs">
            	<a class="heading theme-option-inner-tab" href="javascript:void(0)"><?php _e('Phone','gardenia'); ?></a>
              <div class="theme-option-inner-tab-group">
              	<div class="ft-control">
              		<div class="explain"><?php _e('Enter phone number for your site , you would like to display in the Top Header.','gardenia');?></div>                
                  	<input type="text" id="phone" class="of-input" name="gardenia_theme_options[phone]" size="32"  value="<?php if(!empty($gardenia_options['phone'])) { echo esc_attr($gardenia_options['phone']); } ?>">
                </div>                
              </div>
            </div>
                        
          </div>          
          <!-------------- Home Page group ----------------->
          <div id="options-group-2" class="group theme-option-inner-tabs">
          <h3><?php _e('Banner Slider','gardenia'); ?></h3>
			<?php for($gardenia_i=1; $gardenia_i <= 5 ;$gardenia_i++ ):?> 
            <div class="section theme-tabs theme-slider-img">
            <a class="heading theme-option-inner-tab" href="javascript:void(0)"><?php _e('Slider','gardenia');  echo $gardenia_i; _e(' (Recommended Size : 1350px * 350px) ','gardenia');?></a>
            <div class="theme-option-inner-tab-group">
                <div class="ft-control">
                <input id="slider-img-<?php echo $gardenia_i;?>" class="upload" type="text" name="gardenia_theme_options[slider-img-<?php echo $gardenia_i;?>]" 
                            value="<?php if(!empty($gardenia_options['slider-img-'.$gardenia_i])) { echo esc_attr($gardenia_options['slider-img-'.$gardenia_i]); } ?>" placeholder="<?php _e('No file chosen','gardenia'); ?>" />
                <input id="1upload_image_button" class="upload-button button" type="button" value="<?php _e('Upload','gardenia');?>" />
                <div class="screenshot" id="slider-img-<?php echo $gardenia_i;?>">
                  <?php if(!empty($gardenia_options['slider-img-'.$gardenia_i])) { ?>
					  <img src="<?php echo esc_url($gardenia_options['slider-img-'.$gardenia_i]); ?>" />
					  <a class='remove-image'> </a>
				   <?php } ?>
                </div>
              </div>            
              <div class="ft-control">
                    <input type="text" placeholder="<?php _e('Slider','gardenia'); echo $gardenia_i; _e('link','gardenia'); ?>" id="slidelink-<?php echo $gardenia_i;?>" class="of-input" name="gardenia_theme_options[slidelink-<?php echo $gardenia_i;?>]" size="32"  value="<?php if(!empty($gardenia_options['slidelink-'.$gardenia_i])) { echo esc_url($gardenia_options['slidelink-'.$gardenia_i]); } ?>">
              </div>                              
            </div>            
            </div>
            <?php endfor; ?>
            <h3><?php _e('Title Bar','gardenia'); ?></h3>
            <div id="section-title" class="section theme-tabs">
            	<a class="heading theme-option-inner-tab" href="javascript:void(0)"><?php _e('Title','gardenia'); ?></a>
              <div class="theme-option-inner-tab-group">
              	<div class="ft-control">
              		<div class="explain"><?php _e('Enter home page title for your site , you would like to display in the Home Page.','gardenia'); ?></div>                
                  	<input id="title" class="of-input" name="gardenia_theme_options[home-title]" type="text" size="50" value="<?php if(!empty($gardenia_options['home-title'])) { echo esc_attr($gardenia_options['home-title']); } ?>" />
                </div>                
              </div>
            </div>
            <div class="section theme-tabs theme-short_description">
            	<a class="heading theme-option-inner-tab" href="javascript:void(0)"><?php _e('Short Description','gardenia'); ?></a>
              <div class="theme-option-inner-tab-group">
              	<div class="ft-control">
                <div class="explain"><?php _e('Enter content for your site , you would like to display in the Home Page.','gardenia'); ?></div>
              <textarea name="gardenia_theme_options[home-content]" rows="6" id="home-content1" class="of-input"><?php if(!empty($gardenia_options['home-content'])) { echo esc_textarea($gardenia_options['home-content']); } ?></textarea>
                </div>                
              </div>
            </div>
            
            <h3><?php _e('First Section','gardenia'); ?></h3>
          <?php for($gardenia_section_i=1; $gardenia_section_i <=4 ;$gardenia_section_i++ ): ?>
            <div class="section theme-tabs theme-slider-img">
            <a class="heading theme-option-inner-tab" href="javascript:void(0)"><?php _e('Tab','gardenia'); ?> <?php echo $gardenia_section_i; ?></a>
            <div class="theme-option-inner-tab-group">
                <div class="ft-control">
				<div class="explain"><?php _e('An image with a height of 150 pixels and a width of 150 pixels:','gardenia'); ?></div>
                <input id="first-image-<?php echo $gardenia_section_i;?>" class="upload" type="text" name="gardenia_theme_options[home-icon-<?php echo $gardenia_section_i;?>]" 
                            value="<?php if(!empty($gardenia_options['home-icon-'.$gardenia_section_i])) { echo esc_attr($gardenia_options['home-icon-'.$gardenia_section_i]); } ?>" placeholder="<?php _e('No file chosen','gardenia'); ?>" />
                <input id="upload_image_button" class="upload-button button" type="button" value="<?php _e('Upload','gardenia'); ?>" />
                <div class="screenshot" id="first-img-<?php echo $gardenia_section_i;?>">
                  <?php if(!empty($gardenia_options['home-icon-'.$gardenia_section_i])) { ?>
				  <img src="<?php echo esc_url($gardenia_options['home-icon-'.$gardenia_section_i]); ?>"/>
				  <a class='remove-image'> </a>
				  <?php } ?>
                </div>
              </div>
            
                <div class="ft-control">
                <div class="explain"><?php _e('Enter section title for your home template , you would like to display in the Home Page.','gardenia'); ?></div>
                    <input type="text" placeholder="<?php _e('Enter title here','gardenia'); ?>" id="title-<?php echo $gardenia_section_i;?>" class="of-input" name="gardenia_theme_options[section-title-<?php echo $gardenia_section_i;?>]" size="32"  value="<?php if(!empty($gardenia_options['section-title-'.$gardenia_section_i])) { echo esc_attr($gardenia_options['section-title-'.$gardenia_section_i]); } ?>">
              </div>
				<div class="ft-control">
                 <div class="explain"><?php _e('Enter section content for home template , you would like to display in the Home Page.','gardenia'); ?></div>
              <textarea name="gardenia_theme_options[section-content-<?php echo $gardenia_section_i; ?>]" rows="6" id="content-<?php echo $gardenia_section_i; ?>" placeholder="<?php _e('Enter Content here','gardenia'); ?>" class="of-input" maxlength="100"><?php if(!empty($gardenia_options['section-content-'.$gardenia_section_i])) { echo esc_textarea($gardenia_options['section-content-'.$gardenia_section_i]) ; } ?></textarea>             
              </div>                              
				
			   <div class="ft-control">
					<div class="explain"><?php _e('Enter section link for your home template , you would like to display in the Home Page.', 'gardenia'); ?></div>
					<input type="text" maxlength="200" placeholder="<?php _e('Enter link here', 'gardenia'); ?>" id="link-<?php echo $gardenia_section_i; ?>" class="of-input" name="gardenia_theme_options[section-link-<?php echo $gardenia_section_i; ?>]" size="32"  value="<?php if (!empty($gardenia_options['section-link-' . $gardenia_section_i])) { echo sanitize_text_field($gardenia_options['section-link-' . $gardenia_section_i]); } ?>">
				</div>
			   
           
            </div>            
            </div>
            <?php endfor; ?>
            
            <h3><?php _e('Second Section','gardenia'); ?></h3>
            <div id="section-title" class="section theme-tabs">
            	<a class="heading theme-option-inner-tab" href="javascript:void(0)"><?php _e('Title','gardenia'); ?></a>
              <div class="theme-option-inner-tab-group">
              	<div class="ft-control">
              		<div class="explain"><?php _e('Enter Latest post title for your site , you would like to display in the Home Page.','gardenia'); ?></div>                
                  	<input id="title" class="of-input" name="gardenia_theme_options[post-title]" type="text" size="50" value="<?php if(!empty($gardenia_options['post-title'])) { echo esc_attr($gardenia_options['post-title']); } ?>" />
                </div>                
              </div>
            </div>
            <div class="section theme-tabs theme-short_description">
            	<a class="heading theme-option-inner-tab" href="javascript:void(0)"><?php _e('Short Description','gardenia'); ?></a>
              <div class="theme-option-inner-tab-group">
              	<div class="ft-control">
                <div class="explain"><?php _e('Enter latest post content for your site , you would like to display in the Home Page.','gardenia'); ?></div>
              <textarea name="gardenia_theme_options[post-content]" rows="6" id="home-content1" class="of-input"><?php if(!empty($gardenia_options['post-content'])) { echo esc_textarea($gardenia_options['post-content']); } ?></textarea>
                </div>                
              </div>
            </div>
            <div class="section theme-tabs theme-short_description">
            	<a class="heading theme-option-inner-tab" href="javascript:void(0)"><?php _e('Select Category','gardenia'); ?></a>
              <div class="theme-option-inner-tab-group">
              	<div class="ft-control">					
				<div class="explain"><?php _e('Select Categories of posts for your site , you would like to display in the Home Page.','gardenia'); ?></div> 
                <select name="gardenia_theme_options[post-category-latest]" id="category">
                  <option value=""><?php _e('Select Category','gardenia');?></option>
                  <?php 
				$gardenia_args = array(
				'meta_query' => array(
									array(
									'key' => '_thumbnail_id',
									'compare' => 'EXISTS'
										),
									)
								);  
				$gardenia_post = new WP_Query( $gardenia_args );				
				$gardenia_cat_id=array();
				while($gardenia_post->have_posts()){
				$gardenia_post->the_post();
				$gardenia_post_categories = wp_get_post_categories( get_the_id());   
				$gardenia_cat_id[]=$gardenia_post_categories[0];
				}
				$gardenia_cat_id=array_unique($gardenia_cat_id);
				$gardenia_args = array(
				'orderby' => 'name',
				'parent' => 0,
				'include'=>$gardenia_cat_id
				);
				$gardenia_categories = get_categories($gardenia_args); 
                  foreach ($gardenia_categories as $gardenia_category) {
					  if($gardenia_category->term_id == $gardenia_options['post-category-latest'])
					  	$gardenia_selected="selected=selected";
					  else
					  	$gardenia_selected='';
                    $gardenia_option = '<option value="'.$gardenia_category->term_id .'" '.$gardenia_selected.'>';
                    $gardenia_option .= $gardenia_category->cat_name;
                    $gardenia_option .= '</option>';
                    echo $gardenia_option;
                  }
                 ?>
                </select>                
                </div>                
              </div>
            </div>            
            		
           </div>
          <!-------------- Social group ----------------->
          <div id="options-group-3" class="group theme-option-inner-tabs">            
            <div id="section-facebook" class="section theme-tabs">
            	<a class="heading theme-option-inner-tab active" href="javascript:void(0)"><?php _e('Facebook','gardenia'); ?></a>
              <div class="theme-option-inner-tab-group active">
              	<div class="ft-control">
              		<div class="explain"><?php _e('Facebook profile or page URL ','gardenia'); ?>i.e. http://facebook.com/username/ </div>                
                  	<input id="facebook" class="of-input" name="gardenia_theme_options[fburl]" size="30" type="text" value="<?php if(!empty($gardenia_options['fburl'])) { echo esc_attr($gardenia_options['fburl']); } ?>" />
                </div>                
              </div>
            </div>
            <div id="section-twitter" class="section theme-tabs">
            	<a class="heading theme-option-inner-tab" href="javascript:void(0)"><?php _e('Twitter','gardenia'); ?></a>
              <div class="theme-option-inner-tab-group">
              	<div class="ft-control">
              		<div class="explain"><?php _e('Twitter profile or page URL ','gardenia'); ?>i.e. http://www.twitter.com/username/</div>                
                  	<input id="twitter" class="of-input" name="gardenia_theme_options[twitter]" type="text" size="30" value="<?php if(!empty($gardenia_options['twitter'])) { echo esc_attr($gardenia_options['twitter']); } ?>" />
                </div>                
              </div>
            </div>
            
            <div id="section-gplus" class="section theme-tabs">
            	<a class="heading theme-option-inner-tab" href="javascript:void(0)"><?php _e('Google Plus','gardenia'); ?></a>
              <div class="theme-option-inner-tab-group">
              	<div class="ft-control">
              		<div class="explain"><?php _e('Google plus profile or page URL ','gardenia'); ?>i.e. https://plus.google.com/username/</div>                
                  	 <input id="gplus" class="of-input" name="gardenia_theme_options[gplus]" type="text" size="30" value="<?php if(!empty($gardenia_options['gplus'])) { echo esc_attr($gardenia_options['gplus']); } ?>" />
                </div>                
              </div>
            </div>
            
            <div id="section-pinterest" class="section theme-tabs">
            	<a class="heading theme-option-inner-tab" href="javascript:void(0)"><?php _e('Pinterest','gardenia'); ?></a>
              <div class="theme-option-inner-tab-group">
              	<div class="ft-control">
              		<div class="explain"><?php _e('pinterest profile or page URL ','gardenia'); ?>i.e. https://pinterest.com/username/</div>                
                  	 <input id="pinterest" class="of-input" name="gardenia_theme_options[pinterest]" type="text" size="30" value="<?php if(!empty($gardenia_options['pinterest'])) { echo esc_attr($gardenia_options['pinterest']); } ?>" />
                </div>                
              </div>
            </div>

			<div id="section-linkedin" class="section theme-tabs">
            	<a class="heading theme-option-inner-tab" href="javascript:void(0)"><?php _e('Linkedin','gardenia'); ?></a>
              <div class="theme-option-inner-tab-group">
              	<div class="ft-control">
              		<div class="explain"><?php _e('Linkedin profile or page URL ','gardenia'); ?>i.e. https://linkedin.com/username/</div>                
                  	 <input id="linkedin" class="of-input" name="gardenia_theme_options[linkedin]" type="text" size="30" value="<?php if(!empty($gardenia_options['linkedin'])) { echo esc_attr($gardenia_options['linkedin']); } ?>" />
                </div>                
              </div>
            </div>
            <div id="section-rss" class="section theme-tabs">
            	<a class="heading theme-option-inner-tab" href="javascript:void(0)"><?php _e('RSS','gardenia'); ?></a>
              <div class="theme-option-inner-tab-group">
              	<div class="ft-control">
              		<div class="explain"><?php _e('RSS profile or page URL ','gardenia'); ?>i.e. https://www.rss.com/username/</div>                
                  	<input id="rss" class="of-input" name="gardenia_theme_options[rss]" type="text" size="30" value="<?php if(!empty($gardenia_options['rss'])) { echo esc_attr($gardenia_options['rss']); } ?>" />
                </div>                
              </div>
            </div>
            
          </div>
          <!-------------- Social group ----------------->          
		   
		   <div id="options-group-4" class="group theme-option-inner-tabs gardenia-pro-image">  
			<div class="gardenia-pro-header">
              <img src="<?php echo get_template_directory_uri(); ?>/theme-options/images/gardenia_logopro_features.png" class="gardenia-pro-logo" />
              <a href="http://fruitthemes.com/wordpress-themes/gardenia" target="_blank">
					<img src="<?php echo get_template_directory_uri(); ?>/theme-options/images/gardenia-buy-now.png" class="gardenia-pro-buynow" /></a>
              </div>
          	<img src="<?php echo get_template_directory_uri(); ?>/theme-options/images/gardenia_pro_features.png" />
		  </div> 
		    	
        <!--======================== F I N A L - - T H E M E - - O P T I O N S ===================--> 
      </div>
     </div>
	</div>
	<div class="gardenia-footer">
      	<ul>        	
            <li class="btn-save"><input type="submit" class="button-primary" value="<?php _e('Save options','gardenia');?>" /></li>
        </ul>
    </div>
    </form>    
</div>


<?php } ?>
