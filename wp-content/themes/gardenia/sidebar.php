<?php 
/**
 * The left sidebar template file
**/
?>
<div class="col-md-3 no-padding sidebar col-sm-4">
<?php if ( is_active_sidebar( 'sidebar-1' ) ) { 
			 dynamic_sidebar( 'sidebar-1' );
	 } ?>
</div>
