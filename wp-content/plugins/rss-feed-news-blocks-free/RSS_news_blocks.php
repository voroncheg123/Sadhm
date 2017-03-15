<?php
/**
 * Plugin Name: RSS Feed News Blocks Free
 * Plugin URI: http://www.scriptsmashup.com/product/rss-feeds-news-blocks
 * Description: Show RSS Feed on your posts and pages with shortcode. Create RSS Feeds News Blocks like Popurls, Alltop, Netvibes Theweblist.
 * Version: 1.2.7
 * Author: onigetoc
 * Author URI: http://scriptsmashup.com/
 * License: GPL2
 * Text Domain: newsfeedblocks
 * Domain Path: /languages
 */

//define( 'PLUGIN_PATH', plugins_url( __FILE__ ) );
define( 'RFNB_PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ) );

add_action( 'init', 'rfnb_init' );
function rfnb_init() {
  load_plugin_textdomain('newsfeedblocks', false, dirname(plugin_basename(__FILE__)) . '/languages');
  
  /***** cron job ****/
  if ( !wp_next_scheduled( 'rfnb_cron' ) ) {
    wp_schedule_event( time(), 'monthly', 'rfnb_cron' );
  }

  add_action( 'rfnb_cron', 'rfnb_cron_function' );

  function rfnb_cron_function() {

      delete_option( 'rfnb_favicons' );  // RESET DELETE FAVICONS LIST

  }
  /*****************/
  
}


add_action( 'wp_enqueue_scripts', 'rfnb_css_js');

function rfnb_css_js() {
    global $post;

    if( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'newsblocks') ) {
    //if( has_shortcode( $post->post_content, 'newsblocks') ) {
        wp_enqueue_style('css-columns', RFNB_PLUGIN_DIR_URL . 'css/columns.css');
        wp_enqueue_style('css-rfnb', RFNB_PLUGIN_DIR_URL . 'css/rfnb.css');
    }
    
}


add_shortcode( 'newsblocks', 'rfnb_func' );

function rfnb_func( $atts, $content = null ){
	extract( shortcode_atts( array(
		'url' => '#',
		'items' => '10',
        'max_items' => 'false',
        'orderby' => 'default',
        'title' => 'true',
		'excerpt' => '0',
		'read_more' => 'true',
		'new_window' => 'true',
        'columns' => '1',
        'source' => 'true',
        'date' => 'true',
        'cache' => '43200',
        'merge' => 'false',
        'blocktitle' => '',
        'promo' => 'true'
	), $atts ) );
  
    $lastUrl = "";
    $feeds ="";
    $pwb = "";
  
    if ($promo == 'true'  ) {
    //$pwb = __( 'Powered by:', 'newsfeedblocks' );   
    $pwb = '<div class="clear"></div><div class="clear" style="color: #ccc;font-size: 13px;">'.__( 'Powered by:', 'newsfeedblocks' ).' <a href="http://www.scriptsmashup.com/product/rss-feeds-news-blocks" target="_blank">RSS Feed News Blocks</a></div>';
    }
      
    //$rfn_hide = "rfn_hide";
    /**********************/
    
	$rfnb_data = array(
		//'rfnb_items' => $columns,
		//'max_items' => $max_items,
		'rfnb_column' => $items,
        'e_showmore' => __( 'Show More', 'newsfeedblocks' ),
        'e_showless' => __( 'Show Less', 'newsfeedblocks' ),
        'e_Expand' => __( 'Expand', 'newsfeedblocks' ),
        'e_Minimize' => __( 'Minimize', 'newsfeedblocks' ),
	);
    
    ///////// 
    
    $items_ori = $items+1;


    update_option( 'wp_rss_cache', $cache );

    
    //multiple urls
    if ($url)
    $rss_list = explode(',', $url);
    
    else
        $rss_list = '';
    
    // count feed url
    // echo $num_tags = count($rss_list);

    add_filter( 'wp_feed_cache_transient_lifetime', 'rfnb_cache' );

    //remove_filter( 'wp_feed_cache_transient_lifetime', 'rfnb_cache' );

    // Loop all feeds list
    $counter = 0;
    
    $wp_date = date_i18n('Y-m-d'); // WP Date Function
    //$wp_date = get_the_date('Y-m-d'); // WP Date Function
    $date2=DateTime::createFromFormat('Y-m-d', $wp_date)->format('Y-m-d');
    
    $rss_icon = RFNB_PLUGIN_DIR_URL . 'img/feed-icon.png';
    
    foreach ($rss_list as $urls ) {
    //echo $urls. "<br>";
        
        if ($url == "#") break;
        
    $i = 0; // restart at 0 for each items loops
        
    
    // MERGE 
        if($merge == 'true'){
            $rfnb_merge = " rfnb_merge" ; // class
            $columns = "1";
            $rss = fetch_feed( $rss_list );
            $source = 'true';
            
        } else{
            $rfnb_merge = "" ; // class
            
            if ($lastUrl !== $urls) 
                $rss = fetch_feed( $urls );

        }

    if ( ! is_wp_error( $rss ) ) :

        if ($orderby == 'date' || $orderby == 'date_reverse') {
            $rss->enable_order_by_date(true);
        }
        $maxitems = $rss->get_item_quantity( $items ); 
        $rss_items = $rss->get_items( 0, $maxitems );
        if ( $new_window != 'false' ) {
            $newWindowOutput = 'target="_blank" ';
        } else {
            $newWindowOutput = NULL;
        }

        if ($orderby == 'date_reverse') {
            $rss_items = array_reverse($rss_items);
        }

    endif;
        
        switch ($columns) {
            case "0":
                $rfnb_column = "one_column";
                break;
            case "1":
                $rfnb_column = "one_column";
                break;
            case "2":
                $rfnb_column = "one_half";
                break;
            case "3":
                $rfnb_column = "one_third";
                break;
            case "4":
                $rfnb_column = "one_fourth";
                break;
            case "5":
                $rfnb_column = "one_fifth";
                break;
            case "6":
                $rfnb_column = "one_sixth";
                break;
            default:
                $rfnb_column = "one_third";
        }
        
        $counter++;
        
    //$output = '<div class="rfnb">';
        
        //if($counter >= $columns) {
        if($counter % $columns == 0) { // if ($count % 3 == 0)
        // http://stackoverflow.com/questions/4093017/on-every-third-iteration-in-php
        //if($urls % 3 == 2) {
            $output = '<div class="'.$rfnb_column.'_last">';
        } else{
            //$output = print_r($rfnb_favicons).'<div class="'.$rfnb_column.'">'; // Show favicons array
            $output = '<div class="'.$rfnb_column.'">'; //.$lastUrl
            
        }

        //$output .= getBaseUrl($urls);
//        $output .= '<h2>Today: '.$wp_date.'</h2>';
//        $output .= the_date('Y-m-d', '<h2>', '</h2>');
//        $output .= $my_date = get_the_date('', '<h2>', '</h2>');
        $output .= '<ul class="rfnb_list">';
 
        //$output .= '</span><h2 style="margin-left: 6px;" class="rfnb_feed_title">'.sprintf( __( '%s', 'newsfeedblocks' ), $blocktitle ).'</h2>';
      
        if ($merge == "true"){
            
            $maxminStyle = '';
            if (!$blocktitle)
                $maxminStyle = ' style="width: 100%;"';
            
            $output .= '</span '.$maxminStyle.' ><h2 class="rfnb_feed_title">'.sprintf( __( '%s', 'newsfeedblocks' ), $blocktitle ).'</h2>';
        }


          if ( !isset($maxitems) ) : 
              $output .= '<li>' . _e( 'No items', 'newsfeedblocks' ) . '</li>';        
          else : 
              //loop through each feed item and display each item.
              $i++;
              foreach ( $rss_items as $item ) :
                  //variables
                  $i++;
                  $content = $item->get_content();
                  $the_title = $item->get_title();
                  $enclosure = $item->get_enclosure();
                  $permalink = $item->get_permalink();
                  //$enclosure = $enclosure->get_link();
                  //$output .= $enclosure->get_link();

                  // If title empty put content instead
                  if (!$the_title) {
                      //$the_title = $content;
                      $the_title = esc_html(implode(' ', array_slice(explode(' ', strip_tags(trim($content))), 0, 10))) . "...";
                  }

                  // If content empty put Title instead
                  if (!$content) {
                      $content = $the_title;
                  }


                  /******* Today NEW Feeds *******/
                  //$wp_date = get_the_date('Y-m-d'); // WP Date Function
                  //$wp_date = date_i18n('Y-m-d'); // WP Date Function
                  //$date2=DateTime::createFromFormat('Y-m-d', $wp_date)->format('Y-m-d');
                  //$date2=DateTime::createFromFormat('Y-m-d', get_the_date('Y-m-d'))->format('Y-m-d');  // WP Date Function
                  $date1=DateTime::createFromFormat('Y-m-d', $item->get_date('Y-m-d'))->format('Y-m-d');
                  //$date2=DateTime::createFromFormat('Y-m-d', date("Y-m-d"))->format('Y-m-d'); // Server Time

                  // $output .= $date1.' | '.$date2; // show dates

                  if($date1==$date2 || $date1 > $date2 ){
                      $rfnb_new = '<span class="rfnb_new">&#x25cf; </span>';
                  }else{
                      $rfnb_new = "";
                  }
                  //$rfnb_new = $date1.' | '.$date2;

                  //build output

                  /******* Feed Title *******/
                  // MERGE RESET RESET RESETRESET RESET RESETRESET RESET RESETRESET RESET RESETRESET RESET RESETRESET RESET RESET
                  $rfnb_favicon = "";           
                  if ($item === reset($rss_items) || $merge == "true"){                     

                      if($feed_icon) {
                          //$rfnb_favicon = '<img class="rfnb_feed_img" src="' . $feed_icon . '">';
                          $rfnb_favicon = '<img class="rfnb_feed_img" src="' .  $rss_icon . '" >';
                      }

                      //$rfnb_favicon = '<img class="rfnb_feed_img" src="' . $rss_icon . '">';

                      // GC
                      $feed_title = $item->get_feed()->get_title();

                      if(!$feed_title)
                          $feed_title = getDomain($urls);

                      if ($merge == "false"){
                      $output .= '<h2 class="rfnb_feed_title"><a href="'.$item->get_base('href').'" ' . $newWindowOutput . '">'.$rfnb_favicon.$feed_title.'</a></h2>';
                      }
                      //$output .=  '<h2 class="rfnb_feed_title">'.$item->get_feed()->get_title().'</h2>';
                  
                  } // RESET & MERGE TRUE END                   

                      $output .= '<li class="rfnb_item" ><div class="rfnb_item_wrapper">';

                          // CAN DO IT FOR ALT BECAUSE ALT IS ALWAY THERE WHEN FULL TO TOOLTIP
                          //$alt_title = $the_title;
                          $alt_title = "";
                          $tooltip_class = "";


                      //title
                      $titleClass = "";
                      if ( $excerpt == 0 ) $titleClass = "rfnb_bigtitle ";
      
      
                      if ($title == 'true') {
                          $output .= $rfnb_new.'<a class="rfnb_title '.$titleClass.'"  href="'. esc_url($permalink) . '"
                              title="' . $alt_title . '">'  ;
                              $output .= $the_title;

                          $output .= '</a>';   
                      }


                      // CONTENT BEFORE IMAGE INSIDE
                      $output .= '<div class="rfnb_container">';

                      //content  
                      if ( $excerpt != 'none' ) {
                          if ( $excerpt > 0 ) {

                              // GC remove extra spaces
                              //$content = preg_replace('/\s+/', ' ', $content);
                              $content = trim(preg_replace('/\s+/',' ', $content));
                              $content = strip_shortcodes( $content );
                              $output .= esc_html(implode(' ', array_slice(explode(' ', strip_tags(trim($content))), 0, $excerpt))) . "...";

                          } else {
                              $output .= $content;
                          }
                          if( $read_more == 'true' ) {
                              $output .= ' <a class="rfnb_readmore" ' . $newWindowOutput . 'href="' . esc_url( $item->get_permalink() ) . '"
                                      title="' . sprintf( __( 'Posted %s', 'newsfeedblocks' ), $item->get_date('j F Y | g:i a') ) . '">';
                                      $output .= __( 'Read more &raquo;', 'newsfeedblocks' );
                              $output .= '</a>';
                          }
                      }
                      //metadata

                      if ($source == 'true' || $date == 'true') {
                          $output .= '<div class="rfnb_metadata">';
                              $source_title = $item->get_feed()->get_title();
                              $time = $item->get_date('F j, Y - g:i a');
                              //$publishedtime = $item->get_date('F j, Y - g:i a');
                              //$time = date_i18n( $publishedtime );
                              //$time = date_i18n( $item->get_date('F j, Y - g:i a'),  strtotime( $item->get_date('F j, Y - g:i a') ) );
                              //$time = date_i18n("d F Y (H:i)",$publishedtime) ;

                              //$time = date_i18n( 'M j, Y - g:i a',  strtotime( $item->get_date('M j, Y - g:i a') ) );
                              //$time = date_i18n( 'F j, Y - g:i a',  strtotime( $item->get_date('F j, Y - g:i a') ) );
                              //$time = date_i18n($time); // translate date
                              //date_i18n('Y-m-d'); // translate date
                              if ($source == 'true' && $source_title) {
                                  $output .= '<span class="rfnb_source">' . sprintf( __( 'Source: %s', 'newsfeedblocks' ), $source_title ) . '</span>';
                              }
                              if ($source == 'true' && $date == 'true') {
                                  $output .= ' | ';
                              }
                              if ($date == 'true' && $time) {
                                  $output .= '<span class="rfnb_date">' . sprintf( __( 'Published: %s', 'newsfeedblocks' ), $time ) . '</span>';
                              }
                          $output .= '</div>';
                      }

                  $output .= '</div></div></li>';

              endforeach;
          endif;
      $output .= '</ul>';
        
        
    $output .= '</div>';
        
        //$addOne = 1;
        if($counter % $columns == 0) { // if ($count % 3 == 0)
            $output .= '<div class="clear"></div>';
        }
        // clear block
        if ($urls === end($rss_list) || $merge=="true" ) 
            $output .= $pwb ;
        
        $feeds .= $output;
                        
        // MERGE
        if($merge=="true" && $counter==1) break;  
        $lastUrl = $urls;
    
    } // Foreach End

    return '<div class="rfnb'.$rfnb_merge.'">'.$feeds.'</div>';
}


//update_option('rfnb_favicons', "");

add_option( 'wp_rss_cache', 43200 );

function rfnb_cache() {
    //change the default feed cache
    $cache = get_option( 'wp_rss_cache', 43200 );
    return $cache;
}


function addhttp($url) {
    if (!preg_match("~^(?:ht)tps?://~i", $url)) {
        $url = "http://" . $url;
    }
    return $url;
}

function getDomain($url) 
{
  $parse = parse_url($url);
  $domain = str_replace("www.","",$parse['host']);
  return $domain;
}