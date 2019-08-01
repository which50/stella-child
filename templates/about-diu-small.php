<?php /*?><?php 
$diu = get_post_meta($post->ID, 'diu', true); 
print_r($diu);
foreach($diu as $item){
	$enable		 	= $item['enable-diu'];
	if($enable == 'Yes'){
	$childThemeDir = get_stylesheet_directory_uri();
?>
<div class="vw-about-author clearfix">
    <p style="text-align:center;"><img width="" height="" class="" src="<?php echo $childThemeDir; ?>/images/which-50-diu-small.jpg" alt="DIU" itemprop="image"></p>
</div>
<?php
	}
}
?><?php */?>

<?php 
//$diu = get_post_meta($post->ID, 'diu', false); 
$status = get_post_meta($post->ID, 'diu', true); 
//print_r($status);
foreach( $status as $key){
	$isEnable	= $key['enable-diu'];
	//echo $isEnable;
}
//$key = array_search('enable-diu', $diu);

if($isEnable == 'Yes'){
$childThemeDir = get_stylesheet_directory_uri();
?>
<div class="vw-about-author clearfix">
    <p style="text-align:center;"><img width="" height="" class="" src="<?php echo $childThemeDir; ?>/images/which-50-diu-small.jpg" alt="DIU" itemprop="image"></p>
</div>
<?php
}
?>