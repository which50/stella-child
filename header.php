<!DOCTYPE html>
<!--[if IE 9]>         <html class="no-js lt-ie10" <?php vw_html_tag_schema(); ?> <?php language_attributes(); ?>> <![endif]-->
<!--[if gt IE 9]><!--> <html class="no-js" <?php vw_html_tag_schema(); ?> <?php language_attributes(); ?>> <!--<![endif]-->
<head>
<meta charset="<?php echo esc_attr( get_bloginfo('charset') ); ?>">
<!-- WP Header -->
<?php wp_head(); ?>
<!-- End WP Header -->
<script>
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

ga('create', 'UA-43075581-1', 'auto');
ga('send', 'pageview');

</script>
</head>
<body id="site-top" <?php body_class(); ?>>
<!-- Site Wrapper -->
<div class="vw-site-wrapper marto">
<?php vw_the_site_top_bar(); ?>
<?php get_template_part( '/templates/site-header', vw_get_theme_option( 'site_header_layout' ) ); ?>
<?php do_action( 'vw_action_site_header' ); ?>
<?php if ( function_exists( 'breadcrumb_trail' ) ) { ?>
<div class="container">
<?php breadcrumb_trail(); ?>
</div>
<?php } ?>
