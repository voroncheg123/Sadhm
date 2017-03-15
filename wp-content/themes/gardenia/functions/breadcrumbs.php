<?php
/*
 * gardenia Breadcrumbs
*/
function gardenia_custom_breadcrumbs() {

  $gardenia_showonhome = 0; // 1 - show breadcrumbs on the homepage, 0 - don't show
  $gardenia_delimiter = '<span class="glyphicon glyphicon-chevron-right"></span>'; // gardenia_delimiter between crumbs
  $gardenia_home = __('Home','gardenia'); // text for the 'Home' link
  $gardenia_showcurrent = 1; // 1 - show current post/page title in breadcrumbs, 0 - don't show
  $gardenia_before = ' '; // tag before the current crumb
  $gardenia_after = ' '; // tag after the current crumb

  global $post;
  $gardenia_homelink = esc_url(home_url('/'));

  if (is_home() || is_front_page()) {

    if ($gardenia_showonhome == 1) echo '<li id="breadcrumbs_home"><a href="' . $gardenia_homelink . '">' . $gardenia_home . '</a></li>';
    
  }  else {

    echo '<li id="breadcrumbs_home"><a href="' . $gardenia_homelink . '">' . $gardenia_home . '</a> ' . $gardenia_delimiter . '</li><li id="breadcrumbs">';
    
   if ( is_category() ) {
      $gardenia_thisCat = get_category(get_query_var('cat'), false);
      if ($gardenia_thisCat->parent != 0) echo get_category_parents($gardenia_thisCat->parent, TRUE, ' ' . $gardenia_delimiter . ' ');      
		echo $gardenia_before; _e('category','gardenia'); echo ' "'.single_cat_title('', false) . '"' . $gardenia_after;
    } 
    elseif ( is_search() ) {
      echo $gardenia_before; _e('Search Results For','gardenia'); echo ' "'. get_search_query() . '"' . $gardenia_after;

    } elseif ( is_day() ) {
      echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $gardenia_delimiter . ' ';
      echo '<a href="' . get_month_link(get_the_time('Y'),get_the_time('m')) . '">' . get_the_time('F') . '</a> ' . $gardenia_delimiter . ' ';
      echo $gardenia_before . get_the_time('d') . $gardenia_after;

    } elseif ( is_month() ) {
      echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $gardenia_delimiter . ' ';
      echo $gardenia_before . get_the_time('F') . $gardenia_after;

    } elseif ( is_year() ) {
      echo $gardenia_before . get_the_time('Y') . $gardenia_after;

    } elseif ( is_single() && !is_attachment() ) {
      if ( get_post_type() != 'post' ) {
        $gardenia_post_type = get_post_type_object(get_post_type());
        $gardenia_slug = $gardenia_post_type->rewrite;
        echo '<a href="' . $gardenia_homelink . '/' . $gardenia_slug['slug'] . '/">' . $gardenia_post_type->labels->singular_name . '</a>';
        if ($gardenia_showcurrent == 1) echo ' ' . $gardenia_delimiter . ' ' . $gardenia_before . get_the_title() . $gardenia_after;
      } else {
        $gardenia_cat = get_the_category(); $gardenia_cat = $gardenia_cat[0];
        $gardenia_cats = get_category_parents($gardenia_cat, TRUE, ' ' . $gardenia_delimiter . ' ');
        if ($gardenia_showcurrent == 0) $gardenia_cats = preg_replace("#^(.+)\s$gardenia_delimiter\s$#", "$1", $gardenia_cats);
        echo $gardenia_cats;
        if ($gardenia_showcurrent == 1) echo $gardenia_before . get_the_title() . $gardenia_after;
      }

    } elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {
      $gardenia_post_type = get_post_type_object(get_post_type());
      echo $gardenia_before . $gardenia_post_type->labels->singular_name . $gardenia_after;

    } elseif ( is_attachment() ) {
      $gardenia_parent = get_post($post->post_parent);
      $gardenia_cat = get_the_category($gardenia_parent->ID); $gardenia_cat = $gardenia_cat[0];
      echo get_category_parents($gardenia_cat, TRUE, ' ' . $gardenia_delimiter . ' ');
      echo '<a href="' . get_permalink($gardenia_parent) . '">' . $gardenia_parent->post_title . '</a>';
      if ($gardenia_showcurrent == 1) echo ' ' . $gardenia_delimiter . ' ' . $gardenia_before . get_the_title() . $gardenia_after;

    } elseif ( is_page() && !$post->post_parent ) {
      if ($gardenia_showcurrent == 1) echo $gardenia_before . get_the_title() . $gardenia_after;

    } elseif ( is_page() && $post->post_parent ) {
      $gardenia_parent_id  = $post->post_parent;
      $gardenia_breadcrumbs = array();
      while ($gardenia_parent_id) {
        $gardenia_page = get_page($gardenia_parent_id);
        $gardenia_breadcrumbs[] = '<a href="' . get_permalink($gardenia_page->ID) . '">' . get_the_title($gardenia_page->ID) . '</a>';
        $gardenia_parent_id  = $gardenia_page->post_parent;
      }
      $gardenia_breadcrumbs = array_reverse($gardenia_breadcrumbs);
      for ($gardenia_i = 0; $gardenia_i < count($gardenia_breadcrumbs); $gardenia_i++) {
        echo $gardenia_breadcrumbs[$gardenia_i];
        if ($gardenia_i != count($gardenia_breadcrumbs)-1) echo ' ' . $gardenia_delimiter . ' ';
      }
      if ($gardenia_showcurrent == 1) echo ' ' . $gardenia_delimiter . ' ' . $gardenia_before . get_the_title() . $gardenia_after;

    } elseif ( is_tag() ) {
      echo $gardenia_before; _e('Posts tagged','gardenia'); echo ' "'.  single_tag_title('', false) . '"' . $gardenia_after;

    } elseif ( is_author() ) {
       global $author;
      $gardenia_userdata = get_userdata($author);
      echo $gardenia_before; _e('Articles posted by ','gardenia'); echo $gardenia_userdata->display_name . $gardenia_after;

    } elseif ( is_404() ) {
      echo $gardenia_before; _e('Error 404','gardenia'); echo $gardenia_after;
    }
    
    if ( get_query_var('paged') ) {
      if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ' (';
      echo __('Page','gardenia') . ' ' . get_query_var('paged');
      if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ')';
    }
    echo '</li>';

  }
} // end gardenia_custom_breadcrumbs()
