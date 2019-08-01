<div class="vw-post-meta vw-post-meta-large">
  <div class="vw-post-meta-inner"> <span class="vw-post-author" <?php vw_itemprop('author'); vw_itemtype('Person'); ?>>
    <?php if ( ! vw_has_coauthors() ) : ?>
    <?php vw_the_author_avatar( null, VW_CONST_AVATAR_SIZE_SMALL ); ?>
    <a class="author-name" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" title="<?php echo esc_attr( sprintf( __( 'View all posts by %s', 'envirra' ), get_the_author() ) ); ?>" rel="author" <?php vw_itemprop('name'); ?>>
    <?php the_author(); ?>
    </a>
    <?php else : ?>
    <i class="vw-icon icon-entypo-users"></i>
    <?php $coauthors = new CoAuthorsIterator(); ?>
    <?php while( $coauthors->iterate() ) : ?>
    <?php if ( ! $coauthors->is_first() ) :  ?>
    <span class="vw-coauthors-separater">,</span>
    <?php endif; ?>
    <a class="author-name" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" title="<?php echo esc_attr( sprintf( __( 'View all posts by %s', 'envirra' ), get_the_author() ) ); ?>" rel="author" <?php vw_itemprop('name'); ?>>
    <?php the_author(); ?>
    </a>
    <?php endwhile; ?>
    <?php endif; ?>
    </span>
    <?php vw_the_post_meta_separator(); ?>
    <?php vw_the_post_date(); ?>
    <?php

		if ( comments_open() ) {

			// vw_the_post_meta_separator();

			// vw_the_comment_link();

		} ?>
    <?php if ( vw_post_views_enabled() ) : ?>
    <?php vw_the_post_meta_separator(); ?>
    <?php vw_the_post_views(); ?>
    <?php endif; ?>
  </div>
  <?php /*?><div class="vw-post-meta-icons">
    <?php vw_the_post_shares( 'vw-post-meta-icon' ); ?>
  </div><?php */?>
</div>
