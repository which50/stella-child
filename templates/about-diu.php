<?php 
$diu = get_post_meta($post->ID, 'diu', true); 
foreach($diu as $item){
	$enable		 	= $item['enable-diu'];
	$image 			= $item['logo-image'];
	$title 			= $item['title'];
	$authorinfo 	= $item['author-info'];
	$description 	= $item['description'];
	if(!empty($image)){
	  if(is_numeric($image)){
		 $attachment_image = wp_get_attachment_image_src( $image, 'full' );
		 $src = $attachment_image[0];
	  }
	  else{
		 $src = $image;
	  }
	}
	if($enable == 'Yes'){
?>
<div class="vw-about-author clearfix">

	<h3 class="vw-about-author-title"><span><?php echo $title; ?></span></h3>

	<div class="vw-about-author-inner clearfix">
		<!--<span class="vw-author-avatar">
        <img width="110" height="" class="avatar avatar-110 photo" src="<?php echo $src; ?>" alt="DIU" itemprop="image">
        </span>-->

		<div class="vw-about-author-info" style="width:100%; padding-left:0;">
        	<p style="text-align:center;"><img width="" height="" class="" src="<?php echo $src; ?>" alt="DIU" itemprop="image"></p>
			<div class="vw-author-bio"><?php echo $authorinfo; ?></div>
            <hr />
			<div class="vw-author-bio"><?php echo $description; ?></div>

			<?php /*?><div class="vw-author-socials">
				<?php vw_the_user_social_links(); ?>
			</div><?php */?>
		</div>
	</div>
</div>
<?php
	}
}
?>