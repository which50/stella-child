<?php

add_filter( 'amp_customizer_is_enabled', '__return_false' );
add_filter( 'disable_wpseo_json_ld_search', '__return_true' );


//prevent editor from deleting, editing, or creating an administrator
// only needed if the editor was given right to edit users
 
class ISA_User_Caps {
 
  // Add our filters
  function ISA_User_Caps(){
    add_filter( 'editable_roles', array(&$this, 'editable_roles'));
    add_filter( 'map_meta_cap', array(&$this, 'map_meta_cap'),10,4);
  }
  // Remove 'Administrator' from the list of roles if the current user is not an admin
  function editable_roles( $roles ){
    if( isset( $roles['administrator'] ) && !current_user_can('administrator') ){
      unset( $roles['administrator']);
    }
    return $roles;
  }
  // If someone is trying to edit or delete an
  // admin and that user isn't an admin, don't allow it
  function map_meta_cap( $caps, $cap, $user_id, $args ){
    switch( $cap ){
        case 'edit_user':
        case 'remove_user':
        case 'promote_user':
            if( isset($args[0]) && $args[0] == $user_id )
                break;
            elseif( !isset($args[0]) )
                $caps[] = 'do_not_allow';
            $other = new WP_User( absint($args[0]) );
            if( $other->has_cap( 'administrator' ) ){
                if(!current_user_can('administrator')){
                    $caps[] = 'do_not_allow';
                }
            }
            break;
        case 'delete_user':
        case 'delete_users':
            if( !isset($args[0]) )
                break;
            $other = new WP_User( absint($args[0]) );
            if( $other->has_cap( 'administrator' ) ){
                if(!current_user_can('administrator')){
                    $caps[] = 'do_not_allow';
                }
            }
            break;
        default:
            break;
    }
    return $caps;
  }
 
}
 
$isa_user_caps = new ISA_User_Caps();

// hide admin from user list
add_action('pre_user_query','isa_pre_user_query');
function isa_pre_user_query($user_search) {
  $user = wp_get_current_user();
  if ($user->ID!=1) { // Is not administrator, remove administrator
    global $wpdb;
    $user_search->query_where = str_replace('WHERE 1=1',
      "WHERE 1=1 AND {$wpdb->users}.ID<>1",$user_search->query_where);
  }
}

add_filter('xmlrpc_enabled', '__return_false');
/**
 * Secure WordPress by removing version
 */
remove_action('wp_head', 'wp_generator');


/**
 * Secure WordPress by hiding login errors
 */
function hide_login_errors($errors) { return 'login error'; }
add_filter('login_errors', 'hide_login_errors', 10, 1);






///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////





/**
 * Add new fields above 'Update' button.
 *
 * @param WP_User $user User object.
 */
function tm_additional_profile_fields( $user ) {

    $user_title = get_the_author_meta( 'user_title', $user->ID );  
	$company_name = get_the_author_meta( 'company_name', $user->ID); 
	$usidebar = get_the_author_meta( 'usidebar', $user->ID); 	
	$latest = get_the_author_meta( 'latest', $user->ID ); 
	$include_in_home = get_the_author_meta( 'include_in_home', $user->ID); 
	
	$tab1 = get_the_author_meta( 'tab1', $user->ID );
	$tab2 = get_the_author_meta( 'tab2', $user->ID );
	$tab3 = get_the_author_meta( 'tab3', $user->ID );
	$tab4 = get_the_author_meta( 'tab4', $user->ID );
	$tab5 = get_the_author_meta( 'tab5', $user->ID );
	$tab6 = get_the_author_meta( 'tab6', $user->ID ); 
	
	
	$order = array("tab1","tab2","tab3","tab4","tab5","tab6");
	$tabs = get_the_author_meta( 'row_order', $user->ID );
	$row_order = (isset($tabs) && $tabs != "") ? $tabs : "tab1,tab2,tab3,tab4,tab5,tab6"; 
	if(!empty($row_order)) $order = explode(",",$row_order); 
  
	$class="";
	$compclass = "";
	if(!($user->roles[0]=='diu') && !($user->roles[0]=='which50_insider')){$class = "style='display:none';";}
	
	if(!($user->roles[0]=='diu')){$compclass = "style='display:none';";}
	
    ?>
    <script src="https://code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
    <script>
		jQuery( "#role" ).change(function() {
			var roleval = jQuery(this).val();
			jQuery('#utitle').val('');
			jQuery('#cname').val('');
			jQuery('#compTr').hide();
				
			if(roleval == 'diu' || roleval == 'which50_insider'){
				jQuery('#pextraInfo').show();	
				if(roleval == 'diu') jQuery('#compTr').show();								
			}
			else {
				jQuery('#pextraInfo').hide();	
			}
		});
		
		jQuery(document).ready(function($) {

			$( "#sortable-row tbody" ).sortable({
				placeholder: "ui-state-highlight",
				update: function (event, ui) {
					saveOrder();
				}
			});
			
			$('button#select_bg_image').on('click', function(){
			   $('input#background_image').trigger('click');
			   return false; 
			});
			
			$("#background_image").change(function() {
				var file = this.files[0];
				var imagefile = file.type;
				var match= ["image/jpeg","image/png","image/jpg"];	
				if(!((imagefile==match[0]) || (imagefile==match[1]) || (imagefile==match[2]))){
					return false;
				} else {
					var reader = new FileReader();	
					reader.onload = imageIsLoaded;
					reader.readAsDataURL(this.files[0]);
				}		
			});

		});
		
		function saveOrder() {
			var selectedLanguage = new Array();
			jQuery('#sortable-row .item').each(function() {
				selectedLanguage.push(jQuery(this).attr("data-id"));
			});
			document.getElementById("row_order").value = selectedLanguage;
		}
		
		function imageIsLoaded(e) { 
			jQuery('#image_preview').css("display", "block");
			jQuery('#image_preview').html('<img src="'+e.target.result+'" width="130" height="100">');
		};
	</script>
    <style>
	#sortable-row { list-style: none; }
	#sortable-row tr { margin-bottom:4px; padding:10px; background-color:#BBF4A8;cursor:move;}
	.btnSave{padding: 10px 20px;background-color: #09F;border: 0;color: #FFF;cursor: pointer;margin-left:40px;}  
	#sortable-row tr.ui-state-highlight { height: 1.0em; background-color:#F0F0F0;border:#ccc 2px dotted;}
	input#background_image {
		display: none;
	}
	</style>
    <div id="pextraInfo" <?php echo $class; ?>>
    <h3>Index page information</h3>
	
    
    <table class="form-table input_fields_wrap">
   	 <tr>
   		 <th><label for="utitle">Title</label></th>
   		 <td>
         	<input type="text" id="utitle" name="user_title" class="regular-text" value="<?php echo $user_title; ?>">
   		 </td>
   	 </tr>
     <tr id="compTr" <?php echo $compclass; ?>>
   		 <th><label for="cname">Company Name</label></th>
   		 <td>
         	<input type="text" id="cname" name="company_name" class="regular-text" value="<?php echo $company_name; ?>">
   		 </td>
   	 </tr>
     <tr>
   		 <th><label for="sSidebar">Select Sidebar</label></th>
   		 <td>
         	<select class="regular-text" name="usidebar" id="usidebar">
			<?php foreach ( $GLOBALS['wp_registered_sidebars'] as $sidebar ) { ?>
                 <option value="<?php echo ucwords( $sidebar['id'] ); ?>" <?php if(ucwords($usidebar) == ucwords($sidebar['id'])){echo 'selected="selected"';} ?>>
                          <?php echo ucwords( $sidebar['name'] ); ?>
                 </option>
            <?php } ?>
            </select>
   		 </td>
   	 </tr>
     

     <tr>
   		 <th><label for="latest">How many articles, you want to display in Latest</label></th>
   		 <td>
         	<input type="text" id="latest" name="latest" class="regular-text" value="<?php echo $latest; ?>">
   		 </td>
   	 </tr>
     
     <tr>
   		 <th><label for="cname">Upload Background Image</label></th>
   		 <td><button id="select_bg_image">Select File</button>
         	<input type="file" name="background_image" id="background_image" value="Upload Background Image" size="350" /><br/>
            <?php $image_attributes = wp_get_attachment_image_src( get_user_meta($user->ID,'background_image',true )); ?>
            <div id="image_preview"><?php echo '<img src="'.$image_attributes[0].'">'; ?></div>
   		 </td>
   	 </tr>




     <!---------------------------------TABS----------------------------------->
     <tr>
   		 <td colspan="2"><!--<button class="add_field_button">Add More Fields</button> --><input type = "hidden" name="row_order" id="row_order" value="<?php echo $row_order; ?>"/></td>
   	 </tr>
     
     <tr>
   		 <td colspan="2">
         <table width="100%" border="0" cellspacing="1" cellpadding="4" id="sortable-row">
			
            <?php foreach($order as $n){ ?>
            	<tr data-id="<?php echo $n;?>" class="item">
                     <td><label for="<?php echo $n;?>"><strong><?php echo strtoupper($n);?></strong></label></td>
                     <td>
                        <input type="text" name="<?php echo $n;?>" id="<?php echo $n;?>" class="regular-text tabdetail" value="<?php echo ${$n}; ?>">
                     </td>
                 </tr>
            <?php } ?>
 
           
         </table>
         </td>
   	 </tr>
     
     <tr>
   		 <th></th>
   		 <td>
         	<input type="checkbox" id="include_in_home" name="include_in_home" class="regular-text" <?php if($include_in_home == "yes"){ ?>checked="checked" <?php } ?> value="yes"> Include in homepage widget
   		 </td>
   	 </tr>
     
    </table>
    </div>
    <?php

}

add_action( 'show_user_profile', 'tm_additional_profile_fields' );
add_action( 'edit_user_profile', 'tm_additional_profile_fields' );

/**
 * Save additional profile fields.
 *
 * @param  int $user_id Current user ID.
 */
function tm_save_profile_fields( $user_id ) { 
    
    
    if ( ! current_user_can( 'edit_user', $user_id ) ) {
   	 return false;
    }
	
	update_user_meta( $user_id, 'tab1', $_POST['tab1'] );
	update_user_meta( $user_id, 'tab2', $_POST['tab2'] );
	update_user_meta( $user_id, 'tab3', $_POST['tab3'] );
	update_user_meta( $user_id, 'tab4', $_POST['tab4'] );
	update_user_meta( $user_id, 'tab5', $_POST['tab5'] );
	update_user_meta( $user_id, 'tab6', $_POST['tab6'] );
	update_user_meta( $user_id, 'row_order', $_POST['row_order'] );	
	
	
	if ($_FILES["background_image"]["name"]) {
		foreach ($_FILES as $file => $array) {
			$newupload = insert_complogo($file,$user_id);
		}
	}  
	
    update_user_meta( $user_id, 'user_title', $_POST['user_title'] );
	update_user_meta( $user_id, 'company_name', $_POST['company_name'] );
	update_user_meta( $user_id, 'usidebar', $_POST['usidebar'] );
	update_user_meta( $user_id, 'latest', $_POST['latest'] );	
	update_user_meta( $user_id, 'include_in_home', $_POST['include_in_home'] );
}

add_action( 'personal_options_update', 'tm_save_profile_fields' );
add_action( 'edit_user_profile_update', 'tm_save_profile_fields' );



function insert_complogo($file_handler,$user_id,$setthumb='false') {
	// check to make sure its a successful upload
	if ($_FILES[$file_handler]['error'] !== UPLOAD_ERR_OK) __return_false();
	require_once(ABSPATH . "wp-admin" . '/includes/image.php');
	require_once(ABSPATH . "wp-admin" . '/includes/file.php');
	require_once(ABSPATH . "wp-admin" . '/includes/media.php');
	
	$attach_id = media_handle_upload( $file_handler, $user_id);
	
	if ($setthumb) update_user_meta($user_id,'background_image',$attach_id);
	//return $attach_id;
} 


/*if (!function_exists('get_post_id_by_meta_key_and_value')) {

function get_post_id_by_meta_key_and_value($key, $value, $newvalue) {
	global $wpdb;
	$querystr = "SELECT * FROM ".$wpdb->postmeta." WHERE meta_key='index_user' AND meta_value='".$wpdb->escape($value)."'";
	echo $querystr; die();
	$postids = $wpdb->get_results($querystr, OBJECT);	
	if ($postids) {
		foreach ($postids as $postid){ 
			$key_1_value = get_post_meta( $postid, 'all_tabs', true );
			if ( ! empty( $key_1_value ) ) {
				update_post_meta($postid, $key, $newvalue);
			}

			
		}  
	}
	return 1;
}
}*/


function get_excerpt_by_id($post_id){
    $the_post = get_post($post_id); //Gets post ID
    $the_excerpt = $the_post->post_content; //Gets post_content to be used as a basis for the excerpt
    $excerpt_length = 20; //Sets excerpt length by word count
    $the_excerpt = strip_tags(strip_shortcodes($the_excerpt)); //Strips tags and images
    $words = explode(' ', $the_excerpt, $excerpt_length + 1);

    if(count($words) > $excerpt_length) :
        array_pop($words);
        array_push($words, '');
        $the_excerpt = implode(' ', $words);
    endif;

    $the_excerpt = '<p>' . $the_excerpt . '</p>';

    return $the_excerpt;
}




add_action( 'add_meta_boxes', 'cd_allocate_article' );
function cd_allocate_article()
{
    add_meta_box( 'my-meta-box-id', 'Index Page', 'cd_allocate_article_details', 'post', 'normal', 'high' );
}

function cd_allocate_article_details()
{
    // $post is already set, and contains an object: the WordPress post
    global $post;
    $values = get_post_custom( $post->ID );
    $selected = isset( $values['editor_badge'][0] ) ? $values['editor_badge'][0] : '';
	$selecteduser = isset( $values['index_user'][0] ) ? $values['index_user'][0] : '';
    $check = isset( $values['is_index_post'][0] ) ? $values['is_index_post'][0] : '';
	$tab = isset( $values['all_tabs'][0] ) ? $values['all_tabs'][0] : '';
    
	$current_user = get_currentuserinfo();
	$display_name = $current_user->display_name;
    // We'll use this nonce field later on when saving.
    wp_nonce_field( 'my_meta_box_nonce', 'meta_box_nonce' );
    ?>
    <p>
        <input type="checkbox" id="is_index_post" name="is_index_post" <?php checked( $check, 'on' ); ?> />
        <label style="float: none;" for="is_index_post">Want to add this post to an Index Page?</label>
    </p>
    <p id="indexeditor_role" style="display:none;">
        <label for="editor_badge">Which-50 Insider/DIU</label>
        <select name="editor_badge" id="editor_badge">
        	<option value="">Select</option>
            <option value="DIU" <?php selected( $selected, 'DIU' ); ?>>DIU</option>
            <option value="which50_insider" <?php selected( $selected, 'which50_insider' ); ?>>which50_insider</option>
        </select>
    </p>
    
    <p id="insidersUserLists" style="display:none;">
		<?php $insiders_users = get_users( 'role=which50_insider'); ?>
        <label for="insiders_users">Select an User</label>
        <select name="insiders_users" id="insiders_users" onchange="gettabslist('insiders_users')">
        	<option value="">Select an User</option>
            <?php foreach ( $insiders_users as $user ) { ?>
            <option value="<?php echo $user->ID; ?>" <?php selected( $selecteduser, $user->ID ); ?>><?php echo esc_html( $user->display_name ); ?></option>
            <?php } ?>
        </select>
    </p>
    
    <p id="diuUserLists" style="display:none;">
		<?php $diu_users = get_users( 'role=DIU'); ?>
        <label for="diu_users">Select an User</label>
        <select name="diu_users" id="diu_users"  onchange="gettabslist('diu_users')">
        	<option value="">Select an User</option>
            <?php foreach ( $diu_users as $user ) { ?>
            <option value="<?php echo $user->ID; ?>" <?php selected( $selecteduser, $user->ID ); ?>><?php echo esc_html( $user->display_name ); ?></option>
            <?php } ?>
        </select>
    </p>
    
    <p id="tabLists_msg">Loading ..... </p>
    <p id="tabLists" style="display:none;">
        <label for="all_tabs">Choose a Tab</label>
        <select name="all_tabs" id="all_tabs">
        	<option value="">Select a Tab</option>
        </select>
    </p>
    
    <style>
	#my-meta-box-id label{width: 150px; float: left;}
	#my-meta-box-id select{width: 200px;}
	#my-meta-box-id p{clear:both;}
	</style>
    <script>
	var ajaxUrl = "<?php echo admin_url('admin-ajax.php'); ?>";
	jQuery("#tabLists_msg").hide();
	
	jQuery( window ).on( "load", function() {
				
		jQuery("#is_index_post").click(function() {
			//jQuery("#indexeditor_role").toggle(this.checked);
			if(jQuery(this).is(':checked')){
				jQuery('#indexeditor_role').show();
			} else {
				jQuery('#indexeditor_role').hide();
				jQuery("#editor_badge").val('');
				jQuery("#insiders_users").val('');
				jQuery("#diu_users").val('');  
				jQuery("#all_tabs").val('');
				jQuery("#insidersUserLists").hide();
				jQuery("#diuUserLists").hide();
				jQuery("#tabLists").hide();
			}
						
		}).triggerHandler('click');
		
		jQuery( "#editor_badge" ).change(function() {
			var badge = jQuery("#editor_badge").val();   
			/*jQuery("#insiders_users").val('');
			jQuery("#diu_users").val(''); 
			jQuery("#tabLists").hide();*/
			jQuery("#coauthorsdiv").show();
			
			if(badge == "DIU")	{
				jQuery( "#diu_users" ).trigger( "change" );
				jQuery("#insidersUserLists").hide();
				jQuery("#diuUserLists").show();
			} else if(badge == "which50_insider")	{
			
				jQuery( "#insiders_users" ).trigger( "change" );
				jQuery("#coauthorsdiv").hide();				
				jQuery("#insidersUserLists").show();
				jQuery("#diuUserLists").hide();

			} else {
				jQuery("#insidersUserLists").hide();
				jQuery("#diuUserLists").hide();
				jQuery("#tabLists").hide();
				
			}
		}).triggerHandler( "change" );
				
	});
	
	
	function gettabslist(badgetype){ 
		

		var result;  
		var selTab = "<?php echo (isset($tab) && $tab != "") ? $tab : 0; ?>";
		id = jQuery("#"+badgetype).val();
		var uname;
		
		if(badgetype == "insiders_users"){
			uname = jQuery("#"+badgetype+" option:selected").text();
			if(id != ""){
				if ( jQuery( '#coauthors-list .coauthor-row .coauthor-tag' ).length > 1 ) {
					jQuery( ".coauthors-author-options .delete-coauthor:not(:first)" ).each(function() {
						jQuery( this ).trigger( "click" );
					});
				}
				
				if ( jQuery( '#coauthors-list .coauthor-row .coauthor-tag' ).length <= 1 )
					jQuery( '#coauthors-list .coauthor-row .coauthors-author-options' ).addClass( 'hidden' );
			
				
				jQuery( '.coauthor-suggest' ).val(uname);
				jQuery( 'input[name="coauthorsinput[]"]' ).eq(0).val(uname);
				jQuery( 'input[name="coauthors[]"]' ).eq(0).val(uname);
				jQuery( 'input[name="coauthorsnicenames[]"]' ).eq(0).val(uname);
				jQuery( '.coauthor-tag.ui-sortable-handle' ).text(uname);
			}
			
		}
		else if(badgetype == "diu_users"){
			uname = "<?php echo $display_name; ?>";
			/*jQuery( '.coauthor-suggest' ).val(uname);
			jQuery( 'input[name="coauthorsinput[]"]' ).eq(0).val(uname);
			jQuery( 'input[name="coauthors[]"]' ).eq(0).val(uname);
			jQuery( 'input[name="coauthorsnicenames[]"]' ).eq(0).val(uname);
			jQuery( '.coauthor-tag.ui-sortable-handle' ).text(uname);*/
		}
				
				    
		jQuery.ajax({
			type:"POST",
			url: ajaxUrl+'?action=getAllTabs&id='+id+'&selTab='+selTab, // our PHP handler file  
			beforeSend: function() {
				jQuery("#tabLists").hide();
				jQuery("#tabLists_msg").show();
			},
			success:function(results){
				jQuery("#all_tabs").html(results);
				jQuery("#tabLists_msg").hide();
				jQuery("#tabLists").show();
			}
		});

	}

	</script>
    
    <?php    
}


add_action( 'add_meta_boxes', 'show_hide_posts' );
function show_hide_posts()
{
    add_meta_box( 'show_hide_posts', 'Show/Hide Post', 'show_hide_posts_cb', 'post', 'side', 'high' );
}


function show_hide_posts_cb()
{
    // $post is already set, and contains an object: the WordPress post
    global $post;
    $values = get_post_custom( $post->ID );
    $hide_post_in_home = isset( $values['hide_post_in_home'][0] ) ? esc_attr( $values['hide_post_in_home'][0] ) : '';
     
    // We'll use this nonce field later on when saving.
    wp_nonce_field( 'my_meta_box_nonce', 'meta_box_nonce' );
    ?>
    <p>
        <input type="checkbox" id="hide_post_in_home" name="hide_post_in_home" <?php checked( $hide_post_in_home, 'on' ); ?> />
        <label for="hide_post_in_home">Hide on Frontpage</label>
    </p>
    <?php    
}



add_action( 'save_post', 'cd_meta_box_save' );
function cd_meta_box_save( $post_id )
{
	global $wpdb;
    // Bail if we're doing an auto save
    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
     
    // if our nonce isn't there, or we can't verify it, bail
    if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'my_meta_box_nonce' ) ) return;
     
    // if our current user can't edit this post, bail
    if( !current_user_can( 'edit_post' ) ) return;
     
	
	$hide_post_in_homex = isset( $_POST['hide_post_in_home'] ) && $_POST['hide_post_in_home'] ? 'on' : 'off';
    update_post_meta( $post_id, 'hide_post_in_home', $hide_post_in_homex );
	
	  
    if( isset( $_POST['editor_badge'] ) )
        update_post_meta( $post_id, 'editor_badge', esc_attr( $_POST['editor_badge'] ) );
	else 
		update_post_meta( $post_id, 'editor_badge', '' );
	
	if( isset( $_POST['editor_badge'] ) && $_POST['editor_badge'] != ""){
		if( $_POST['editor_badge'] == "which50_insider" && isset( $_POST['insiders_users'] ) ){
        	update_post_meta( $post_id, 'index_user', esc_attr( $_POST['insiders_users'] ) );
			$user_id = $_POST['insiders_users'];
			$res = $wpdb->update("wp_posts",array('post_author'=>$user_id),array('ID'=>$post_id));
		}
		else if( $_POST['editor_badge'] == "DIU" && isset( $_POST['diu_users'] ) ){
			update_post_meta( $post_id, 'index_user', esc_attr( $_POST['diu_users'] ) );
			
			$name = (isset($_POST['coauthors']) && $_POST['coauthors'] != "") ? $_POST['coauthors'] : ""; 
			
			if(!empty($name)){
				foreach( $name as $v ) { 
					$ux = $wpdb->get_var( "SELECT ID FROM  $wpdb->users WHERE user_nicename='".$v."'" );
					$res = $wpdb->update("wp_posts",array('post_author'=>$ux),array('ID'=>$post_id));
					break;
				}
			}

		}
	}
	else
		update_post_meta( $post_id, 'index_user', '' );
	
	if( isset( $_POST['all_tabs'] ) )
        update_post_meta( $post_id, 'all_tabs', esc_attr( $_POST['all_tabs'] ) );
	else 
		update_post_meta( $post_id, 'all_tabs', '' );
			   
    // This is purely my personal preference for saving check-boxes
    $chk = isset( $_POST['is_index_post'] ) && $_POST['is_index_post'] ? 'on' : 'off';
    update_post_meta( $post_id, 'is_index_post', $chk );
}


function getAllTabs_ajax_handler() {
	global $wpdb; // this is how you get access to the database
	$user_id = intval( $_GET['id'] ); // get the id value which has been posted
	$selTab = $_GET['selTab'];
	
	$all_meta_for_user = get_user_meta( $user_id );
    $tab1 =  $all_meta_for_user['tab1'][0];
	$tab2 =  $all_meta_for_user['tab2'][0];
	$tab3 =  $all_meta_for_user['tab3'][0];
	$tab4 =  $all_meta_for_user['tab4'][0];
	$tab5 =  $all_meta_for_user['tab5'][0];
	$tab6 =  $all_meta_for_user['tab6'][0];
	
	$opt = '<option value="">Select a Tab</option>';
	if(!empty($tab1)) $opt .= '<option value="tab1"' . selected( $selTab, "tab1" ) .'>'. $tab1 .'</option>';
	if(!empty($tab2)) $opt .= '<option value="tab2"' . selected( $selTab, "tab2" ) .'>'. $tab2 .'</option>';
	if(!empty($tab3)) $opt .= '<option value="tab3"' . selected( $selTab, "tab3" ) .'>'. $tab3 .'</option>';
	if(!empty($tab4)) $opt .= '<option value="tab4"' . selected( $selTab, "tab4" ) .'>'. $tab4 .'</option>';
	if(!empty($tab5)) $opt .= '<option value="tab5"' . selected( $selTab, "tab5" ) .'>'. $tab5 .'</option>';
	if(!empty($tab6)) $opt .= '<option value="tab6"' . selected( $selTab, "tab6" ) .'>'. $tab6 .'</option>';
	echo $opt;
	
	wp_die(); // close the connection
}

add_action('wp_ajax_getAllTabs', 'getAllTabs_ajax_handler'); // add action for logged users
add_action( 'wp_ajax_nopriv_getAllTabs', 'getAllTabs_ajax_handler' ); // add action for unlogged users



function exclude_single_posts_home($query) {
	global $wpdb;
	$ids = array();
	
	if ($query->is_home()) {
		$results = $wpdb->get_results( "SELECT post_id FROM wp_postmeta WHERE meta_key = 'hide_post_in_home' AND meta_value = 'on'", ARRAY_A); 
		if (count($results)> 0){
			foreach ( $results as $res ) {
				$ids[] = $res['post_id']; // Here i used the default object method. So i didnt specify the name here and get all the users name. 
			}
    		$query->set('post__not_in', $ids);
		}
	}
}

add_action('pre_get_posts', 'exclude_single_posts_home');






class best_of_author extends WP_Widget {
	/**
	* To create the example widget all four methods will be 
	* nested inside this single instance of the WP_Widget class.
	**/
	public function __construct() {
		$widget_options = array( 
		  'classname' => 'best_of_author_widget',
		  'description' => 'Mostly visited posts of an Author',
		);
		parent::__construct( 'best_of_author_widget', 'Best of an Author Widget', $widget_options );
	}
	
	public function widget( $args, $instance ) {
		$title = "Best of ".apply_filters( 'widget_title', $instance[ 'title' ] );
		$author = apply_filters( 'widget_author', $instance[ 'author' ] );

		echo $args['before_widget'] . $args['before_title'] . $title . $args['after_title']; 
		echo most_viewed_index_page($author);
		
		?>
		
		<?php echo $args['after_widget'];
	}
	
	
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : ''; 
		$author = ! empty( $instance['author'] ) ? $instance['author'] : '';
		
		//$authors = get_users('orderby=display_name&order=ASC&role=diu,which50_insider');
		
		$first_role_users = get_users('role=diu'); //First role list
		$second_role_users = get_users('role=which50_insider'); // Second roles List		
		$authors = array_merge($first_role_users, $second_role_users );

		
		$auth = "";
		foreach ($authors as $authorx) {
			//if (count_user_posts($authorx->ID) > 0) { echo $authorx->ID."<br>";
				$sel = ($author == $authorx->ID) ? 'selected="selected"' : ''; 
			   	$auth .= '<option value="' . $authorx->ID . '" '. $sel . '>' . $authorx->display_name . '</option>';
			//}
		}


		?>
        <style>
			.widget-content p{display:table; width:100%;}
			.widget-content label{display:table-cell; width:40%; text-align:left; vertical-align:middle;}
			.widget-content select, .widget-content input{display:table-cell; text-align:left; vertical-align:middle; width: 90%;}
		</style>
        
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">Title:</label>
		<input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <p>
        <label for="<?php echo $this->get_field_id( 'author' ); ?>">Author Name:</label>
        <select id="<?php echo $this->get_field_id( 'author' ); ?>" name="<?php echo $this->get_field_name( 'author' ); ?>">
        	<option value="">Select</option>
            <?php echo $auth; ?>
        </select>
        </p>
		<?php 
	}
	
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance[ 'title' ] = strip_tags( $new_instance[ 'title' ] );
		$instance[ 'author' ] = strip_tags( $new_instance[ 'author' ] );
		
		return $instance;
	}

}

function register_best_of_author_widget() { 
  register_widget( 'best_of_author' );
}
add_action( 'widgets_init', 'register_best_of_author_widget' );



function most_viewed_index_page($author) { 
	//global $author;
	global $wpdb;
	
	ob_start();
	
	$user_info = get_userdata($author);
	$first_name = $user_info->first_name;
    $last_name = $user_info->last_name;
		
	$ids = $wpdb->get_var( "SELECT GROUP_CONCAT(post_id SEPARATOR ',') as ID FROM  `wp_postmeta` WHERE meta_key='index_user' and meta_value='".$author."'" );
	$selectedPosts = explode(',', $ids); 
	$selectedPosts = array_unique($selectedPosts);
	?>    
    <!--<h3 class="widget-title widgetind"><span>Best of <?php echo $first_name ." ". $last_name ; ?></span></h3>-->    
	<?php
			
	$query_args = array('post_type' => 'post', 'ignore_sticky_posts' => true, 'posts_per_page' => 3, 'orderby' => 'meta_value_num', 'post__in' => $selectedPosts);	
	$popular = new WP_Query($query_args);
	while ($popular->have_posts()) : $popular->the_post(); 
	
	?>
        <div class="vw-post-box vw-post-box--small-left-thumbnail clearfix vw-post-format-standard" itemscope="" itemtype="">
            <a class="vw-post-box__thumbnail" href="<?php echo get_the_permalink();?>" rel="bookmark">
                <?php $large_image_urlx = wp_get_attachment_image_src( get_post_thumbnail_id( $postid ), 'medium' );
                    if ( ! empty( $large_image_urlx[0] ) ) {
                        $imgsrc = esc_url( $large_image_urlx[0] ) ; ?>
                        <img width="85" height="85" src="<?php echo $imgsrc; ?>" itemprop="image">
                    <?php
                    }
                ?>
            </a>
            <div class="vw-post-box__inner">
                <h5 class="vw-post-box__title">
                    <a href="<?php echo get_the_permalink();?>" title="<?php echo get_the_title();?>" rel="bookmark" itemprop="url"><?php echo get_the_title();?></a>
                </h5>
                <div class="vw-post-box__meta">
                    <i class="vw-icon icon-entypo-clock"></i> <a href="<?php echo get_the_permalink();?>" class="vw-post-date updated" rel="bookmark" original-title="<?php echo get_the_title();?>"><time itemprop="datePublished"><?php echo get_the_time('M j, Y', get_the_id()); ?></time></a>
                </div>
            </div>
        </div>
			
	<?php 		
    endwhile; wp_reset_postdata();
    $out = ob_get_contents();
    ob_end_clean();


	echo wpautop($out);
	
}
//add_shortcode('most_viewed_index_page', 'most_viewed_index_page');
//
//

add_shortcode( 'indexed-users', 'indexed_users_shortcode' );
function indexed_users_shortcode() {
	global $wpdb;
    ob_start();
	
	$roles = array("which50_insider", "diu");
	$title = array("which50_insider"=>"Which-50 Insiders", "diu"=>"Digital Intelligence Unit");
	$cls = "cls";
	
	$hidepostsx = array();
	$hideposts_results = $wpdb->get_results( "SELECT post_id FROM wp_postmeta WHERE meta_key = 'hide_post_in_home' AND meta_value = 'on'", ARRAY_A); 
	if (count($hideposts_results)> 0){
		foreach ( $hideposts_results as $res ) {
			$hidepostsx[] = $res['post_id']; // Here i used the default object method. So i didnt specify the name here and get all the users name. 
		}
	}
	
	$hideposts = implode(",",$hidepostsx);	
	
    ?>
    
    <!-- FlexSlider -->
	<script defer src="<?php echo get_stylesheet_directory_uri().'/js/jquery.flexslider.js';?>"></script>
    <link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri().'/css/flexslider.css';?>" type="text/css" media="screen" />

    <div class="vw-loop vw-loop--mix vw-loop--mix-2 vw-loop--col-2">
    <div class="row">
    
	<?php foreach($roles as $role){ 
	$cnt=0;
	$cls = $role."_slider"; 
	$authors = get_users('role='.$role.'&orderby=display_name&order=ASC'); 
	?>
    <div class="col-sm-6">
    <h3 class="vwspc-section-title"> 
    	<span class=""><?php echo $title[$role]; ?></span>
    </h3>
    
    <section class="slider">
       	<div class="flexslider <?php echo $cls; ?>">
            <ul class="slides">
            <?php
			
			foreach ($authors as $author) { 
				//if (count_user_posts($author->ID) > 0) {
				   	$include_in_home = get_the_author_meta( 'include_in_home', $author->ID); 
					
					if ( $include_in_home == "yes" ) {
					
						$cnt++;
						$ids = "";
						$aumg2 = get_avatar( $author->ID, 100 );
						$all_meta_for_user = get_user_meta( $author->ID );					
						$user_title =  $all_meta_for_user['user_title'][0];
						$user_nicename =  strtolower($all_meta_for_user['nickname'][0]);
						
											
						
						if($role == 'which50_insider'){ 					
							$first_name =  $all_meta_for_user['first_name'][0];
							$last_name =  $all_meta_for_user['last_name'][0];
							$nickname =  $first_name . " " . $last_name;
							$auturl = get_author_posts_url( $author->ID );
						}
						else{
							$nickname =  $all_meta_for_user['company_name'][0];
							$auturl = get_author_posts_url( $author->ID );
						}
					
					
						$ids = $wpdb->get_var( "SELECT GROUP_CONCAT(post_id SEPARATOR ',') as ID FROM  `wp_postmeta` WHERE meta_key='index_user' and meta_value='".$author->ID."'" );	
						if($ids){
						?>
						<li>
						<div class="homeauthorIndex">
							
							<div class="vw-loop vw-loop--mix vw-loop--mix-2 vw-loop--col-1">
								<div class="row">
									<div class="col-sm-12">
									
										<input type="hidden" class="auturl" value="<?php echo $auturl; ?>" />
										<div class="vw-post-box vw-post-box--small-left-thumbnail clearfix vw-post-format-standard" itemscope="" itemtype="">
											<div class="vw-post-box__thumbnail homeauthimg"><a href="<?php echo $auturl; ?>" target="_blank"><?php echo $aumg2;?></a></div>
											<div class="vw-post-box__inner">                
												<h2 class="vw-page-title-section__title"><a href="<?php echo $auturl; ?>" target="_blank"><?php echo $nickname; ?></a></h2>
												<span class="aucamp"><?php if($user_title) echo $user_title."<br/>"; ?></span>
												<br/>
											</div>            
										</div>
									
									
									 <?php		
									 
									 
									 
			
			
									$result = $wpdb->get_results( "SELECT wpm.meta_value, wp.* FROM `wp_posts` as wp INNER JOIN wp_postmeta as wpm ON wpm.post_ID = wp.ID where ID IN ($ids) AND meta_key='all_tabs' AND post_status='publish' AND post_id NOT IN (".$hideposts.") ORDER BY post_date DESC LIMIT 3" );
									foreach ( $result as $pageauth ) { 				
										
										$large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $pageauth->ID ), 'thumbnail' );
										if ( ! empty( $large_image_url[0] ) ) {
											$imgsrc = esc_url( $large_image_url[0] ) ; 
										} else {
											$imgsrc = get_stylesheet_directory_uri().'/images/noimg.png';
										}
										
										?>
										<div class="vw-post-box vw-post-box--small-left-thumbnail clearfix vw-post-format-standard" itemscope="" itemtype="">
											<a class="vw-post-box__thumbnail" href="<?php echo esc_url( get_permalink($pageauth->ID) ); ?>" rel="bookmark">		
												<img width="85" height="85" src="<?php echo $imgsrc; ?>" class="attachment-vw_small_thumbnail size-vw_small_thumbnail wp-post-image" alt="<?php echo $pageauth->post_title; ?>" itemprop="image">
											</a>
											<div class="vw-post-box__inner">                
												<h5 class="vw-post-box__title">
													<a href="<?php echo esc_url( get_permalink($pageauth->ID) ); ?>" class="" itemprop="url"><?php echo $pageauth->post_title; ?></a>
												</h5>
											
												<div class="vw-post-box__meta">
													<i class="vw-icon icon-entypo-clock"></i> 
													<time itemprop="datePublished" ><?php echo date('M j, Y', strtotime($pageauth->post_date)); ?></time>
												</div>
											</div>            
										</div>
								
										
							
									<?php } ?>
									
										
										
									</div>
								</div>
							</div>
						</div>
						</li>
						<?php
						}
					}
				   
				//}
			}
			
			?>
            </ul>
            
            <?php if($cnt>0){ ?>
            <hr/>
            <div class="vw-post-box vw-post-box--small-left-thumbnail clearfix vw-post-format-standard" itemscope="" itemtype="">
                <a class="auth-read button" href="#">View More</a>  
                <div class="authpointers"></div>
            </div>
            <hr/>  
            <?php } ?>                  
        </div>
    </section>
    </div>
    
    <script type="text/javascript">
    jQuery(window).load(function(){
        var auturl;
        jQuery('.flexslider.<?php echo $cls; ?>').flexslider({
            animation: "fade",
            slideshowSpeed: 5000,
            animationSpeed: 500,
            //startAt: 0, 
            slideshow: true,
            initDelay: 0,
            pauseOnHover: true,

            controlNav: true,              
            directionNav: false,
            selector: '.slides > li',
            controlsContainer: ".flexslider.<?php echo $cls; ?> .authpointers",
            start: function(slider){
                jQuery('body').removeClass('loading');	
                auturl = jQuery('.flexslider.<?php echo $cls; ?> .flex-active-slide .auturl').val();
                jQuery('.flexslider.<?php echo $cls; ?> a.auth-read').attr('href',auturl);						
            },
            after: function(){
                auturl = jQuery('.flexslider.<?php echo $cls; ?> .flex-active-slide .auturl').val();
                jQuery('.flexslider.<?php echo $cls; ?> a.auth-read').attr('href',auturl);
            }
        });
    });
    </script>
    
    <?php } ?>
    
    </div>
    </div>
    
    <?php
	$myslider = ob_get_clean();
	return $myslider;
}
?>