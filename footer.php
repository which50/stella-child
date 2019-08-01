			<?php get_template_part( '/templates/site-footer' ); ?>



		</div>

		<!-- End Site Wrapper -->

		

		<!-- WP Footer -->

		<?php wp_footer(); ?>
		

<script type="text/javascript">
jQuery(document).ready(function ($){
	$('#download-btn, #download-btn2').click(function(){
		var link = document.createElement('a');
		link.href = $(this).attr("src"); // use realtive url 
		link.download = $(this).attr("src");
		document.body.appendChild(link);
		link.click();     
   });
        
   //$("#menu-main-menu a, #menu-mobile-menu a, #menu-top-menu a").removeAttr("itemprop");
})
</script>

		<!-- End WP Footer -->
<!--		<img src="https://amplifypixel.outbrain.com/pixel?mid=00e77d1766108541faf0f2b52402b4a3e6" width="1" height="1" alt=""/> -->
	</body>



</html>
