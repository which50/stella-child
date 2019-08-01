<div class="vw-post-box vw-post-box--large vw-post-box--large-1 <?php vw_the_post_format_class(); ?>" <?php vw_itemtype('Article'); ?>>

	<?php if ( has_post_thumbnail() ) : ?>

	<a class="vw-post-box__thumbnail" href="<?php echo esc_url( get_permalink() ); ?>" rel="bookmark" data-mfp-src="<?php echo esc_url( vw_get_embed_video_url() ); ?>">

		<?php the_post_thumbnail( VW_CONST_THUMBNAIL_SIZE_POST_LARGE ); ?>

	</a>

	<?php endif; ?>





	<div class="vw-post-box__inner">



		<?php vw_the_post_format_icon(); ?>

		

		<?php vw_the_review_summary(); ?>



		<?php vw_the_category(); ?>



		<h3 class="vw-post-box__title">

			<a href="<?php the_permalink(); ?>" class="" <?php vw_itemprop('url'); ?>>

				<?php the_title(); ?>

			</a>

		</h3>



		<div class="vw-post-box__excerpt"><?php the_excerpt(); ?></div>

		

		<div class="vw-post-box__meta">

			

			<?php vw_the_author(); ?>



			<span class="vw-post-meta-separator">/</span>



			<?php vw_the_post_date(); ?>



			<?php /*?><span class="vw-post-meta-separator">/</span><?php */?>



			<?php // vw_the_comment_link(); ?>

			

		</div>

		

	</div>

	

</div>