<?php
/*
Plugin Name: Custom CSS for pages
Description: Create custom css for pages.
Tags: post custom css, pages, custom, custom css, css
Author URI: http://habitmanager.com/
Author: Kjeld Hansen
Text Domain: ri_custom_css
Requires at least: 4.0
Tested up to: 4.4.2
Version: 1.0
*/


 if ( ! defined( 'ABSPATH' ) ) exit; 


add_action( 'admin_enqueue_scripts', 'ri_custom_css_admin_css' );
function ri_custom_css_admin_css(){
	wp_register_style( 'ri_custom_css_admin_wp_admin_css', plugins_url( '/css/admin.css', __FILE__), false, '1.0.0' );
    wp_enqueue_style( 'ri_custom_css_admin_wp_admin_css' );	
}
#################################################################################################################################

function realmeta_register_cc_meta_boxes() {
    add_meta_box( 'ri-cc', __( 'The Custom CSS', 'textdomain' ), 'realmeta_cc_display_callback', '' );
}
add_action( 'add_meta_boxes', 'realmeta_register_cc_meta_boxes' );
 

function realmeta_cc_display_callback( $post ) {
    $ptvisib = array('page');
        wp_nonce_field( 'realmeta_cc_inner_custom_box', 'realmeta_cc_inner_custom_box_nonce' );
		$ptype = 'post';
		if(isset($_REQUEST['post_type'])){ $ptype = $_REQUEST['post_type']; }
		else if(isset($_REQUEST['post'])){
			 $ptype = get_post_type($_REQUEST['post']);
		}
		
		if(in_array($ptype, $ptvisib, true)){
		
		$value = unserialize(get_post_meta( $post->ID, '_ricc_css', true ));
		?>
		<div class="ricc_col">
		<label for="ri_ccss">
			<?php _e( 'Custom CSS', 'textdomain' ); ?> : 
		</label> 
			<textarea  id="ri_ccss" name="ri_ccss"  placeholder="Write CSS"> <?php echo esc_attr( $value ); ?> </textarea>
		</div><?php
		}  

}
add_action( 'save_post', 'realmeta_save_cc_meta_box' );

function realmeta_save_cc_meta_box( $post_id ) {
 	$ptvisib = array('page');
        if ( ! isset( $_POST['realmeta_cc_inner_custom_box_nonce'] ) ) {
            return $post_id;
        }
 
        $nonce = $_POST['realmeta_cc_inner_custom_box_nonce'];
 
        if ( ! wp_verify_nonce( $nonce, 'realmeta_cc_inner_custom_box' ) ) {
            return $post_id;
        }
 	$ptype = 'post';
	if(isset($_REQUEST['post_type'])){ $ptype = $_REQUEST['post_type']; }
	else if(isset($_REQUEST['post'])){
		 $ptype = get_post_type($_REQUEST['post']);
	}
	if(in_array($ptype, $ptvisib, true)){
		$ridata = sanitize_text_field( $_POST['ri_ccss'] );  
		update_post_meta( $post_id, '_ricc_css', serialize($ridata) );
	}
		
}

add_action( 'wp_enqueue_scripts', 'ri_ccss_scripts_method', 1000 );
function ri_ccss_scripts_method(){
	
	$ricss = unserialize(get_post_meta( get_the_id(), '_ricc_css', true ));
	echo '<style type="text/css">';
	echo $ricss;
	echo '</style>';
	//header("Content-type: text/css");
	//wp_enqueue_style('ricc-dynamic-css', plugins_url( 'dynamic-css.php?css='.$ricss, __FILE__));
	
}


//###################################################

