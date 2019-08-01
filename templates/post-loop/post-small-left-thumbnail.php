<div class="vw-post-box vw-post-box--small-left-thumbnail clearfix <?php vw_the_post_format_class(); ?>" <?php vw_itemtype('Article'); ?>>

	<?php if ( has_post_thumbnail() ) : ?>

	<a class="vw-post-box__thumbnail" href="<?php echo esc_url( get_permalink() ); ?>" rel="bookmark">

		<?php the_post_thumbnail( VW_CONST_THUMBNAIL_SIZE_POST_SMALL_LEFT_THUMBNAIL ); ?>

	</a>

	<?php endif; ?>

	

	<div class="vw-post-box__inner">

		

		<h5 class="vw-post-box__title">

			<a href="<?php echo esc_url( get_permalink() ); ?>" title="<?php printf( esc_attr__('Permalink to %s', 'envirra'), the_title_attribute('echo=0') ); ?>" rel="bookmark" <?php //vw_itemprop('url'); ?>><?php the_title(); ?></a>

		</h5>



		<div class="vw-post-box__meta">

			<?php vw_the_post_date(); ?>



			<?php /*?><span class="vw-post-meta-separator">/</span><?php */?>



			<?php if ( comments_open() ) // vw_the_comment_link(); ?>

		</div>



	</div>

</div>