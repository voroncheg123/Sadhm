<?php
/*
 * gardenia Main Sidebar
*/
function gardenia_widgets_init() {

	register_sidebar( array(
		'name'          => __( 'Main Sidebar', 'gardenia' ),
		'id'            => 'sidebar-1',
		'description'   => __( 'Main sidebar that appears on the left.', 'gardenia' ),
		'before_widget' => '<div class="left-sidebar blog-right %2$s" id="%1$s" >',
		'after_widget'  => '</div>',
		'before_title'  => '<h4>',
		'after_title'   => '</h4>',
	) );
	
	register_sidebar( array(
		'name'          => __( 'Footer Area One', 'gardenia' ),
		'id'            => 'footer-1',
		'description'   => __( 'Footer area one that appears on the footer.', 'gardenia' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="footer-menu-title">',
		'after_title'   => '</h2>',
	) );
	
	register_sidebar( array(
		'name'          => __( 'Footer Area Two', 'gardenia' ),
		'id'            => 'footer-2',
		'description'   => __( 'Footer area two that appears on the footer.', 'gardenia' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="footer-menu-title">',
		'after_title'   => '</h2>',
	) );
	
	register_sidebar( array(
		'name'          => __( 'Footer Area Three', 'gardenia' ),
		'id'            => 'footer-3',
		'description'   => __( 'Footer area three that appears on the footer.', 'gardenia' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="footer-menu-title">',
		'after_title'   => '</h2>',
	) );
	
	register_sidebar( array(
		'name'          => __( 'Footer Area Four', 'gardenia' ),
		'id'            => 'footer-4',
		'description'   => __( 'Footer area four that appears on the footer.', 'gardenia' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="footer-menu-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'gardenia_widgets_init' );


/*
 * gardenia Set up post entry meta.
 *
 * Meta information for current post: categories, tags, permalink, author, and date.
 */
function gardenia_entry_meta() {

	$gardenia_categories_list = get_the_category_list(',','');
	
	$gardenia_tag_list = get_the_tag_list('', ',' );
	
	$gardenia_author= get_the_author();
	
	$gardenia_author_url= esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) );
	
	$gardenia_comments = wp_count_comments(get_the_ID()); 	
	
	$gardenia_date = sprintf( '<time datetime="%1$s">%2$s</time>',	
		sanitize_text_field( get_the_date( 'c' ) ),
		esc_html( get_the_date() )
	);
?>	
		<div class="fancy_categories">
                <div class="glyphicon glyphicon-user color"><a href="<?php echo $gardenia_author_url; ?>" rel="tag"><?php echo $gardenia_author; ?></a></div>
                <div class="glyphicon glyphicon-calendar color"><a href="<?php echo esc_url(get_day_link(get_post_time('Y'), get_post_time('m'), get_post_time('j'))); ?>" rel="tag"><?php echo $gardenia_date; ?></a></div>
               <?php if(!empty($gardenia_tag_list)) { ?><div class="glyphicon glyphicon-tag color"><?php echo $gardenia_tag_list; ?></div><?php } ?>
               <?php if(!empty($gardenia_categories_list)) { ?><div class="glyphicon glyphicon-folder-open color"><?php echo $gardenia_categories_list; ?></div><?php } ?>
                <div class="glyphicon glyphicon-comment color"><span class="comment"><?php comments_number( __( 'No Comments', 'gardenia' ), __( '1 Comment', 'gardenia' ), __( '% Comments', 'gardenia' ) ); ?></span></div>
		</div>
<?php 	
}


/*
 * Comments placeholder function
 * 
**/
add_filter( 'comment_form_default_fields', 'gardenia_comment_placeholders' );

function gardenia_comment_placeholders( $fields )
{
	$fields['author'] = str_replace(
		'<input',
		'<input placeholder="'
		/* Replace 'theme_text_domain' with your theme’s text domain.
		* I use _x() here to make your translators life easier. :)
		* See http://codex.wordpress.org/Function_Reference/_x
		*/
		. _x(
		'Name *',
		'comment form placeholder',
		'gardenia'
		)
		. '" required',
		
	$fields['author']
	);
	$fields['email'] = str_replace(
		'<input',
		'<input  placeholder="'
		. _x(
		'Email Id *',
		'comment form placeholder',
		'gardenia'
		)
		. '" ',
	$fields['email']
	);
	$fields['url'] = str_replace(
		'<input',
		'<input placeholder="'
		. _x(
		'Website URl',
		'comment form placeholder',
		'gardenia'
		)
		. '" required',
	$fields['url']
	);
	
	return $fields;
}

add_filter( 'comment_form_defaults', 'gardenia_textarea_insert' );
	function gardenia_textarea_insert( $fields )
	{
		$fields['comment_field'] = str_replace(
			'<textarea',
			'<textarea placeholder="'
			. _x(
			'Comment',
			'comment form placeholder',
			'gardenia'
			)
		. '" ',
		$fields['comment_field']
		);
	return $fields;
	}

function gardenia_pagination()
{
	the_posts_pagination( array(
				'prev_text'          => __( 'Previous', 'gardenia' ),
				'next_text'          => __( 'Next', 'gardenia' ),
				'before_page_number' => '<span class="meta-nav screen-reader-text"></span>',
			) );  
}

?>
