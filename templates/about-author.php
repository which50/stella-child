<?php // get_template_part( 'templates/about-diu' ); ?>
<?php 
/*$diu = get_post_meta($post->ID, 'diu', true); 
foreach($diu as $item){
	$enable = $item['enable-diu'];
	if($enable != 'Yes'){*/
?>

<?php
$post_tags = get_the_tags();
if ( $post_tags ) { 
?>
   <div class="vw-about-tags clearfix"> 
   	<?php foreach( $post_tags as $tag ) {
    	echo "<span><a href='" . get_tag_link($tag->term_id) . "'>".$tag->name."</a></span>"; 
    }
	?>
   </div>
<?php
}
?>


<?php $author = vw_get_current_author();?>
<div class="vw-about-author clearfix" <?php vw_itemprop('author'); vw_itemtype('Person'); ?>>

	<h3 class="vw-about-author-title"><span><?php _e( 'The Author', 'envirra' ); ?></span></h3>

	<div class="vw-about-author-inner clearfix">
		<?php vw_the_author_avatar( $author, VW_CONST_AVATAR_SIZE_LARGE ); ?>

		<div class="vw-about-author-info">
			<h4 class="vw-author-name" <?php vw_itemprop('name'); ?>><?php echo esc_html( $author->display_name ); ?></h4>
			<p class="vw-author-bio" <?php vw_itemprop('description'); ?>><?php echo nl2br( esc_html( $author->user_description ) ); ?></p>

			<div class="vw-author-socials">
				<?php vw_the_user_social_links(); ?>
			</div>
		</div>
	</div>
</div>
<?php
	/*}
}*/
?>
<?php /*?><?php 
$diu = get_post_meta($post->ID, 'newslettersubscription', true); 
foreach($diu as $item){
	$enable		 	= $item['enable-newsletter-subscription'];
	if($enable == 'Yes'){
?>
<?php
if ( is_active_sidebar( 'custom-sidebar-3' ) ) {
	dynamic_sidebar( 'custom-sidebar-3' );
} 
?>
<?php
	}
}
?><?php */?>

<?php 
//$diu = get_post_meta($post->ID, 'newslettersubscription', false); 
//$key = array_search('enable-newsletter-subscription', $diu);
$status = get_post_meta($post->ID, 'newslettersubscription', true); 
if (is_array($status) || is_object($values)){
	foreach( $status as $key){
		$isEnable	= $key['enable-newsletter-subscription'];
		//echo $isEnable;
	}
	//$enable	= $diu[$key]['enable-newsletter-subscription'];
	if($isEnable == 'Yes'){
		if ( is_active_sidebar( 'custom-sidebar-3' ) ) {
			dynamic_sidebar( 'custom-sidebar-3' );
		} 
	}
}

?>