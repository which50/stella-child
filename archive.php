<?php get_header(); ?>

<div class="vw-page-wrapper clearfix <?php vw_the_sidebar_position_class(); ?>">
	<div class="container">
		<div class="row">
			
		<?php global $author;
        $user_info = get_userdata($author);
        //$username = $user_info->user_login;
        //$first_name = $user_info->first_name;
        //$last_name = $user_info->last_name;
        $role = $user_info->roles[0];
        
        if($role == 'which50_insider' || $role == 'diu'){
        ?>
			<link rel="stylesheet" type="text/css" href="<?php echo get_stylesheet_directory_uri();?>/js/easy-responsive-tabs.css " />
			<script src="<?php echo get_stylesheet_directory_uri();?>/js/easyResponsiveTabs.js"></script>
            <script type="text/javascript">
				jQuery(document).ready(function() {
					jQuery('ul#portfolio li').addClass('hidden');
					jQuery('ul#portfolio li.hidden.latest').removeClass('hidden');
					
					
					
					jQuery("#filtersel").change(function(){
						var selval = jQuery(this).val();
												
						jQuery("#filter li a:contains("+selval+")").css('outline','none');
						jQuery('ul#filter .current').removeClass('current');
						jQuery("#filter li a:contains("+selval+")").parent().addClass('current');
						
						var filterVal = jQuery(this).val().toLowerCase().replace(/\s/g, "");		
						
						if(filterVal == 'latest') {
							jQuery('ul#portfolio li').addClass('hidden');
							jQuery('ul#portfolio li.hidden.latest').show().removeClass('hidden');
						} else {
							
							jQuery('ul#portfolio li').each(function() {
								if(!jQuery(this).hasClass(filterVal)) {
									jQuery(this).hide().addClass('hidden');
								} else {
									jQuery(this).show().removeClass('hidden');
								}
							});
						}
						
						return false;
					});
					
					jQuery('ul#filter a').click(function() {
						jQuery(this).css('outline','none');
						jQuery('ul#filter .current').removeClass('current');
						jQuery(this).parent().addClass('current');
						
						jQuery("#filtersel").val(jQuery(this).text().toLowerCase());
						
						var filterVal = jQuery(this).text().toLowerCase().replace(/\s/g, ""); //.replace(' ','_');

						if(filterVal == 'latest') {
							jQuery('ul#portfolio li').addClass('hidden');
							jQuery('ul#portfolio li.hidden.latest').show().removeClass('hidden');
						} else {
							console.log(filterVal);
							jQuery('ul#portfolio li').each(function(){
								if(!jQuery(this).hasClass(filterVal)) {
									jQuery(this).hide().addClass('hidden');
								} else {
									jQuery(this).show().removeClass('hidden');
								}
							});
						}
						
						return false;
					});
				});
			</script>
			<?php			
			$tabs = array();
			$order = array();
			$finaltabs = array();
			
			$all_meta_for_user = get_user_meta( $author );
            $user_title =  $all_meta_for_user['user_title'][0];
			$company_name =  $all_meta_for_user['company_name'][0]; //print_r($all_meta_for_user); 
			$first_name =  $all_meta_for_user['first_name'][0];
			$last_name =  $all_meta_for_user['last_name'][0];
			$nickname =  $first_name . " " . $last_name;
			
			
			$usidebar =  (isset($all_meta_for_user['usidebar'][0]) && $all_meta_for_user['usidebar'][0] != "") ? $all_meta_for_user['usidebar'][0] : "custom-sidebar-6";
			
			
			(isset($all_meta_for_user['tab1'][0]) && $all_meta_for_user['tab1'][0] != "") ? ($tabs["tab1"] =  $all_meta_for_user['tab1'][0]) : "";
			(isset($all_meta_for_user['tab2'][0]) && $all_meta_for_user['tab2'][0] != "") ? ($tabs["tab2"] =  $all_meta_for_user['tab2'][0]) : "";
			(isset($all_meta_for_user['tab3'][0]) && $all_meta_for_user['tab3'][0] != "") ? ($tabs["tab3"] =  $all_meta_for_user['tab3'][0]) : "";
			(isset($all_meta_for_user['tab4'][0]) && $all_meta_for_user['tab4'][0] != "") ? ($tabs["tab4"] =  $all_meta_for_user['tab4'][0]) : "";
			(isset($all_meta_for_user['tab5'][0]) && $all_meta_for_user['tab5'][0] != "") ? ($tabs["tab5"] =  $all_meta_for_user['tab5'][0]) : "";
			(isset($all_meta_for_user['tab6'][0]) && $all_meta_for_user['tab6'][0] != "") ? ($tabs["tab6"] =  $all_meta_for_user['tab6'][0]) : "";
			
			
			$background_imageId =  $all_meta_for_user['background_image'][0];
			$latest =  $all_meta_for_user['latest'][0];
			$row_order =  (isset($all_meta_for_user['row_order'][0]) && $all_meta_for_user['row_order'][0] != "") ? $all_meta_for_user['row_order'][0] : "tab1,tab2,tab3,tab4,tab5,tab6";

			
            $facebook =  (isset($all_meta_for_user['facebook'][0]) && $all_meta_for_user['facebook'][0] != "") ? $all_meta_for_user['facebook'][0] : "#";
            $linkedin =  (isset($all_meta_for_user['linkedin'][0]) && $all_meta_for_user['linkedin'][0] != "") ? $all_meta_for_user['linkedin'][0] : "#";
            $twitter =  (isset($all_meta_for_user['twitter'][0]) && $all_meta_for_user['twitter'][0] != "") ? $all_meta_for_user['twitter'][0] : "#";
            
            $aumg2 = get_avatar( $author, 150 );
			
			$url = wp_get_attachment_url( $background_imageId, 'full' );
			
			$ids = $wpdb->get_var( "SELECT GROUP_CONCAT(post_id SEPARATOR ',') as ID FROM  `wp_postmeta` WHERE meta_key='index_user' and meta_value='".$author."'" );
							
			$result = $wpdb->get_results( "SELECT wpm.meta_value, wp.* FROM `wp_posts` as wp INNER JOIN wp_postmeta as wpm ON wpm.post_ID = wp.ID where ID IN ($ids) AND meta_key='all_tabs' AND post_status='publish' ORDER BY post_date DESC" );
			foreach ( $result as $page ) { 				
				$finaltabs[$page->meta_value] = $tabs[$page->meta_value];
			}

			if(!empty($row_order)) {
				$orderx = explode(",",$row_order); 
				foreach ($orderx as $row) { 
					if(!empty($finaltabs[$row])) { $order[$row] = $finaltabs[$row];}
				}
			}

			?>
            <div id="bg" style="background-image: url(<?php echo $url ?>); background-repeat: no-repeat; background-size: cover; min-height: 245px;">
            	<div class="vw-page-title-section__title-box indexpage">
                    <div class="container" style="max-width: 100%;">
                        <div class="row" style="margin-left: 0; padding: 0; margin-right: 0;">
                        
                            <div class="col3">
                                <div class="authimg"><?php echo $aumg2;?></div>
                            </div>
                            
                            <div class="col4">
                                <div class="authdetail">
                                    
                                    
                                    <?php if($role == 'which50_insider'){ ?>
                                    	<h2 class="vw-page-title-section__title"><?php echo $nickname; ?></h2>
                                        <?php if($user_title) echo $user_title."<br/>"; ?>
                                        <img src="<?php echo get_stylesheet_directory_uri();?>/images/which50-insider.png"> &nbsp; WHICH-5O INSIDER
                                    <?php } else { ?>
                                    	<h2 class="vw-page-title-section__title"><?php echo $company_name; ?></h2>
                                        <?php if($user_title) echo $user_title."<br/>"; ?>
                                        <img src="<?php echo get_stylesheet_directory_uri();?>/images/diu.png" class="fordesk">
                                        <img src="<?php echo get_stylesheet_directory_uri();?>/images/diu-mob.png" class="formobb">
                                    <?php } ?>
                                </div>
                            </div>
                            
                            <div class="col5">
                                <span class="vw-short-code-site-social-icons">
                                    <span class="vw-site-social-profile"> 
                                    <?php if($linkedin != "#"){ ?>                   
                                        <a title="LinkedIn" href="<?php echo esc_url($linkedin);?>" class="vw-site-social-profile-icon vw-site-social-linkedin" target="_blank">
                                            <i class="vw-icon icon-social-linkedin"></i>
                                        </a>
                                    <?php } ?>
                                    <?php if($twitter != "#"){ ?> 
                                        <a title="Twitter" href="<?php echo esc_url($twitter);?>" class="vw-site-social-profile-icon vw-site-social-twitter" target="_blank">
                                            <i class="vw-icon icon-social-twitter"></i>
                                        </a>
                                     <?php } ?>   
                                     <?php if($facebook != "#"){ ?> 
                                        <a title="Facebook" href="<?php echo esc_url($facebook);?>" class="vw-site-social-profile-icon vw-site-social-facebook" target="_blank">
                                            <i class="vw-icon icon-social-facebook"></i>
                                        </a>
                                    <?php } ?>
                                    </span>
                                </span>
                            </div>
                        </div>
                    </div>
            
                    
                </div>
            </div>
            
 
 
            <div class="vw-page-wrapper indexpagebody clearfix <?php vw_the_sidebar_position_class(); ?>">
              <div class="container">
                <div class="row">
                    <div class="vw-page-content" role="main" itemprop="articleBody">
                    
                    <article <?php post_class( 'vw-main-post clearfix' ); ?>>
                        
                        <ul id="filter">
                            <li class="current"><a href="#">latest</a></li>
                            <?php
                            foreach($order as $item2){
                                echo "<li><a href='#'>" . $item2 . "</a></li>";
                            }
                            ?>
                        </ul>
                        
                        
                        <div class="mc4wp-form-basic formob">
                        <select name="filtersel" id="filtersel">
                        	<option value='latest'>Latest</option>
                        	<?php
                            foreach($order as $item2){
                                echo "<option value='".$item2."'>" . $item2 . "</option>";
                            }
                            ?>
                        </select>
                        </div>
                        
                        <ul id="portfolio">
                            <?php
                            $cn = 0;
                    
                            foreach ( $result as $page ) { 
                                $tabname = $tabs[$page->meta_value];
                            
                                $post_info = get_post($page->ID);								

                                $co_authors = get_coauthors( $page->ID );
                                
                                $co_authors_names = "";
                                
                                foreach ( $co_authors as $co_author ) {
                                        $author_filter_url = get_author_posts_url( $co_author->ID, $co_author->user_nicename );
                                        
                                        $co_authors_names .= get_avatar( $co_author->ID, 25 ) . ' <a href="' . esc_url( $author_filter_url ) . '">' . esc_html( $co_author->display_name ) . '</a>' . ', ';
                                }
                                $name = rtrim( $co_authors_names, ', ' );
                    
                    
                                
                                $large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $page->ID ), 'large' );
                                if ( ! empty( $large_image_url[0] ) ) {
                                    $imgsrc = esc_url( $large_image_url[0] ) ; 
                                } else {
                                    $imgsrc = get_stylesheet_directory_uri().'/images/noimg.png';
                                }
                                
                                $text = get_excerpt_by_id($page->ID);
                                $cn++;
                                if($cn<=$latest){$cls = " latest";} else {$cls = "";}
                            ?>
                               
                               <li class="<?php echo strtolower(str_replace(' ', '', $tabname)); echo $cls; ?> vw-isotope vw-block-grid vw-block-grid-xs-1 vw-block-grid-sm-2">
                                   <div class="vw-block-grid-item">
                                        <a class="vw-post-box__thumbnail" href="<?php echo esc_url( get_permalink($page->ID) ); ?>" rel="bookmark" data-mfp-src=""><img width="360" height="240" src="<?php echo $imgsrc; ?>" class="attachment-vw_one_third_thumbnail size-vw_one_third_thumbnail wp-post-image" alt="<?php echo $page->post_title; ?>" itemprop="image"></a>
                                   </div>
                                   
                                   <div class="vw-block-grid-item">
                                        <h3 class="vw-post-box__title"><a href="<?php echo esc_url( get_permalink($page->ID) ); ?>" class="" itemprop="url"><?php echo $page->post_title; ?></a></h3>
                                        <div class="vw-post-box__excerpt"><p><?php echo $text; ?></p></div>
                                        
                                        <div class="vw-post-box__meta">
                                            <span class="vw-post-author" itemprop="author" itemscope="" itemtype="http://schema.org/Person">
                                                <?php echo $name; ?>
                                            </span>
                                            <span class="vw-post-meta-separator">/</span>
                                            <i class="vw-icon icon-entypo-clock"></i> <time itemprop="datePublished" ><?php echo date('M j, Y', strtotime($page->post_date)); //echo esc_attr( get_the_date( 'M j, Y' ) ); ?></time>
                                        </div>   
                                    
                                    </div>
                                </li>
                            
                            <?php } ?>
                        </ul>    
                    </article>
                    
                    </div>
                    
                    <aside class="vw-page-sidebar vw-page-sidebar-right" itemscope="" itemtype="http://schema.org/WPSideBar">
                        <meta itemprop="name" content="Right Sidebar">
                        <div style="margin-top:20px;"></div>
                        <?php if ( is_active_sidebar($usidebar) ) : 
                        dynamic_sidebar($usidebar); 
                        endif; ?>
                    </aside>
                </div>
              </div>
            </div>



          
        <?php }  else { ?>
            
			<div class="vw-page-content" role="main">
					<div class="vw-page-title-box clearfix">
						<?php if ( is_author() ) : ?>

								<div class="vw-page-title-box-inner">
									<?php $author = vw_get_current_author(); ?>
									<h1 class="vw-page-title"><?php echo wp_kses_data( $author->display_name ); ?></h1>
								</div>

						<?php elseif ( is_category() ) : ?>

							<?php $the_category_thumbnail = vw_get_the_category_thumbnail();
							$cat_ID = get_query_var('cat');
							if ( $the_category_thumbnail ) : ?>
							<div class="vw-page-title-thumbnail"><?php echo $the_category_thumbnail; ?></div>
							<?php endif; ?>
							
							<div class="vw-page-title-box-inner">
								<h1 class="vw-page-title"><?php echo single_cat_title( '', false ); ?></h1>

								<?php if ( category_description( $cat_ID ) ) : ?>
								<div class="vw-page-description"><?php echo wp_kses_data( category_description( $cat_ID ) ); ?></div>
								<?php endif; ?>
							</div>

						<?php elseif ( is_day() || is_month() || is_year() ) : ?>

								<div class="vw-page-title-box-inner">
									<h1 class="vw-page-title"><?php echo vw_get_archive_date(); ?></h1>
								</div>

						<?php elseif ( is_tag() ) : ?>

								<div class="vw-page-title-box-inner">

									<?php $term = get_queried_object(); ?>
									<h1 class="vw-page-title"><?php echo single_tag_title( '', false ); ?></h1>

									<?php if ( ! empty( $term->description ) ) : ?>
									<div class="vw-page-description"><?php echo wp_kses_data( $term->description ); ?></div>
									<?php endif; ?>
								</div>

						<?php endif; ?>
					</div>

					<?php if ( is_author() ) : ?>
						<div class="vw-author-archive-info clearfix">
							<?php vw_the_author_avatar( $author, VW_CONST_AVATAR_SIZE_LARGE ); ?>
							<p class="vw-author-bio" <?php vw_itemprop('description'); ?>><?php echo nl2br( esc_html( $author->user_description ) ); ?></p>

							<div class="vw-author-socials">
								<?php vw_the_user_social_links(); ?>
							</div>
						</div>
					<?php endif; ?>

				<?php if ( have_posts() ) : vw_setup_secondary_query(); ?>

					<?php do_action( 'vw_action_before_archive_posts' ); ?>

					<?php get_template_part( 'templates/post-loop/loop', vw_get_archive_blog_layout() ); ?>

					<?php do_action( 'vw_action_after_archive_posts' ); ?>

					<?php vw_the_pagination(); ?>

				<?php endif; ?>

			</div>

			<?php get_sidebar(); ?>
            
		<?php } ?>
		</div>
	</div>

</div>

<?php get_footer(); ?>