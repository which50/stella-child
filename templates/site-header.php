<!-- Site Header : Left Logo -->
<header class="vw-site-header vw-site-header--left-logo clearfix" <?php vw_itemtype('WPHeader'); ?>>
  <div class="container">
    <div class="vw-site-header__inner">
      <div class="vw-site-header__logo">
        <?php get_template_part( 'templates/logo' ); ?>
        <div class="vw-mobile-nav-button"> <span class="vw-mobile-nav-button__button"> <span class="vw-hamburger-icon"><span></span></span> </span> </div>
      </div>
      <?php get_template_part( 'templates/header-ads' ); ?>
    </div>
  </div>
  <?php get_template_part( 'templates/menu-main' ); ?>
  <?php get_template_part( 'templates/menu-mobile' ); ?>
  <?php if ( vw_is_enable_breaking_news() ) get_template_part( '/templates/breaking-news-bar' ); ?>
</header>
<!-- End Site Header : Left Logo -->
