<?php /* Init the post data */

if ( is_single() && have_posts() ) { the_post(); }

?>



<?php if (

	has_post_format( 'gallery' )

	|| 'large-featured-image' == vw_get_post_layout()

	|| 'large-title' == vw_get_post_layout()

	|| 'page_big_featured_image.php' == get_page_template_slug()



) : ?>



<?php

$classes = 'vw-page-title-section--no-background';

if ( 'large-featured-image' == vw_get_post_layout() ) {

	$classes .= ' vw-page-title-section--no-title';

}

?>

<div class="vw-page-title-section <?php echo esc_attr( $classes ); ?> clearfix">



	<div class="container">

		<div class="vw-page-title-section__inner">

			<?php if ( is_single() && has_post_format( 'gallery' ) ): ?>

			<div class="vw-page-title-section__gallery-direction">

				<a href="#" class="vw-page-title-section__gallery-button vw-page-title-section__gallery-button--prev">

					<i class="vw-icon icon-entypo-left-open-big"></i>

				</a>

				<a href="#" class="vw-page-title-section__gallery-button vw-page-title-section__gallery-button--next">

					<i class="vw-icon icon-entypo-right-open-big"></i>

				</a>

			</div>

			<?php endif; ?>

			

			<?php if ( is_single() && 'large-title' == vw_get_post_layout() ): ?>

			<div class="vw-page-title-section__title-box">



				<?php vw_the_category(); ?>



				<h1 class="vw-page-title-section__title"><?php the_title(); ?></h1>

				<?php vw_the_subtitle(); ?>



				<div class="vw-post-meta">

			

					<i class="vw-icon icon-entypo-users"></i> <?php if ( function_exists( 'coauthors_posts_links' ) ) { coauthors_posts_links(); } else { the_author_posts_link(); } ?>



					<span class="vw-post-meta-separator">/</span>



					<?php vw_the_post_date(); ?>



					<?php /*?><span class="vw-post-meta-separator">/</span><?php */?>



					<?php // vw_the_comment_link(); ?>

					

				</div>

				<div class="breadcrumbs" typeof="BreadcrumbList">
					<?php if(function_exists('bcn_display')) {
                        bcn_display();
                    } ?>
                </div>
				
                <?php $categories = get_the_category();
				$catlink = "";
				$catname = "";
				if ( ! empty( $categories ) ) {
					$catlink = esc_url( get_category_link( $categories[0]->term_id ) );
					$catname = esc_html( $categories[0]->name );
				} 
				
				$keywords = get_post_meta(get_the_ID(), '_yoast_wpseo_focuskw', true);
				$description = wp_strip_all_tags( get_the_excerpt(), true );
				$featured_img_url = get_the_post_thumbnail_url(get_the_ID(),'full'); 
				?>
                
                <script type="application/ld+json">
				{
					"@context": "http://schema.org",
					"@type": "BreadcrumbList",
					"itemListElement":
					 [
							{
							"@type": "ListItem",
							"position": 1,
							"item":
								{
									"@id": "https://which-50.com",
									"name": "Home"
								}
							},        
						{
							"@type": "ListItem",
							"position": 2,
							"item":
								{
									"@id": "<?php echo $catlink; ?>",
									"name": "<?php echo $catname; ?>"
								}
							},        
					
						{
							"@type": "ListItem",
							"position": 3,
							"item":
								{
									"@id": "<?php echo get_the_permalink(); ?>",
									"name": "<?php echo get_the_title(); ?>"
								}
							}                
					]        
				}
				</script>

				<script type="application/ld+json">
				{
					"@context": "http://schema.org",
					"@type": "NewsArticle",
					"mainEntityOfPage":{
						"@type":"WebPage",
						"@id":"<?php echo get_the_permalink(); ?>"
					},
					"headline":"<?php echo get_the_title(); ?>",
					"datePublished":"2018-01-15T18:30:00+00:00",
					"dateModified":"2018-01-16T00:06:19+00:00",
						"author":{
						"@type":"Person",
						"name":"<?php echo get_the_author(); ?>"
					},
						"Publisher": {
						"@type":"Organization",  
						"name":"Which-50", 
						"logo":{
							"@type": "ImageObject",
							"url": "https://which-50.com/wp-content/uploads/2016/11/Which50_hor_new_small.png",
							"width": "**",
							"height": "**"
						}
					},
					"image":{
						"@type": "ImageObject",
						"url": "<?php echo $featured_img_url; ?>",
						"width": "**",
						"height": "**"
					},
						"ArticleSection":"<?php echo $catname; ?>",
						"keywords": "<?php echo $keywords; ?>",
						"description": "<?php echo $description; ?>"
				}
				</script>


			</div>

			<?php endif; ?>

		</div>

	</div>



</div>



<?php endif; ?>



<?php /* Rewind the post data */

if ( is_single() && have_posts() ) { rewind_posts(); }

?>