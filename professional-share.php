<?php
/*
  Plugin Name: Professional Share
  Plugin URI: http://kenmorico.com/blog/professional-share
  Description: A sharing plugin for professional sites.
  Version: 1.5.1
  Author: Ken Morico
  Author URI: http://kenmorico.com/blog
  License: GPL2
 */

/*  Copyright 2013  Ken Morico  email : blog@kenmorico.com Twitter : @KenMorico

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

// ACTIVATION-----------------------------------

define("PS_CURRENT_VERSION",    1.51);

function professional_share_setup_defaults(){
    if(!isset($options['post_top'])){
        $options['post_top'] = '1';
        update_option('professional_share_options', $options);
    }
    if(!isset($options['page_bottom'])){
        $options['page_bottom'] = '1';
        update_option('professional_share_options', $options);
    }
} 


function professional_share_activate() {
     // check if this is first activation
    $options = get_option('professional_share_options');
    
    if(isset($options['prior_activation'])) return;
        else {
            $options['prior_activation'] = 'true';
            update_option('professional_share_options', $options);
        }
    professional_share_setup_defaults();

}
register_activation_hook( __FILE__,  'professional_share_activate' );


function professional_share_deactivate() {

}
register_deactivation_hook( __FILE__, 'professional_share_deactivate' );

function professional_share_uninstall() {
    //delete_option('professional_share_options');
}

register_uninstall_hook(__FILE__, 'professional_share_uninstall' );


// action links
/*
// Add settings link on plugin page
function professional_share_settings_link($links) { 
  $settings_link = '<a href="options-general.php?page=professional_share.php">Settings</a>'; 
  array_unshift($links, $settings_link); 
  return $links; 
}
 
$plugin = plugin_basename(__FILE__); 
add_filter("plugin_action_links_$plugin", 'professional_share_settings_link' );
*/

// Adding WordPress plugin meta links
 
add_filter( 'plugin_row_meta', 'professional_share_plugin_meta_links', 10, 2 );
function professional_share_plugin_meta_links( $links, $file ) {
 
	$plugin = plugin_basename(__FILE__);
 
	// create link
	if ( $file == $plugin ) {
		return array_merge(
			$links,
			array( '<a href="options-general.php?page=professional_share.php">Settings</a>' ,'<a href="http://kenmorico.com/blog/professional-share">Donate</a>' )
		);
	}
	return $links;
 
}



// ADMIN OPTIONS FORM-----------------------------------
// add the admin options page
add_action('admin_menu', 'plugin_admin_add_page');

function plugin_admin_add_page() {
    add_options_page('Professional Share Page', 'Professional Share', 'manage_options', 'professional_share', 'professional_share_options_page');
}

// display the admin options page
function professional_share_options_page() {
    ?>
    <div>
        <h2>Professional Share Plugin</h2>
        <p>Configure options below. For details and tips on these options, visit the <a href="http://kenmorico.com/blog/professional-share" target="_blank">plugin homepage</a>.</p>
        <form action="options.php" method="post">
            <?php settings_fields('professional_share_options'); ?>
    <?php do_settings_sections('professional_share'); ?>

            <input name="Submit" type="submit" value="<?php esc_attr_e('Save Changes'); ?>" />
        </form></div>
    <?php
}

// add the admin settings and such
add_action('admin_init', 'professional_share_admin_init');

function professional_share_admin_init() {
    //set version num
   $options = get_option('professional_share_options'); 
   $options['version'] = PS_CURRENT_VERSION;
    update_option('professional_share_options', $options);
    
    register_setting('professional_share_options', 'professional_share_options', 'professional_share_options_validate');
    
      //position
    add_settings_section('professional_share_position', 'Position Settings', 'professional_share_position_section_text', 'professional_share');
    add_settings_field('professional_share_post_top', 'Show at the top of posts?', 'professional_share_position_post_top_setting', 'professional_share', 'professional_share_position');
    add_settings_field('professional_share_post_bottom', 'Show at the bottom of posts?', 'professional_share_position_post_bottom_setting', 'professional_share', 'professional_share_position');
    add_settings_field('professional_share_page_top', 'Show at the top of pages?', 'professional_share_position_page_top_setting', 'professional_share', 'professional_share_position');
    add_settings_field('professional_share_page_bottom', 'Show at the bottom of pages?', 'professional_share_position_page_bottom_setting', 'professional_share', 'professional_share_position');
    
// twitter
    add_settings_section('professional_share_twitter', 'Twitter Settings', 'professional_share_twitter_section_text', 'professional_share');
    add_settings_field('professional_share_twitter_user', 'Twitter Username', 'professional_share_twitter_user_setting_string', 'professional_share', 'professional_share_twitter');
    //facebook
    add_settings_section('professional_share_facebook', 'Facebook Settings', 'professional_share_facebook_section_text', 'professional_share');
    add_settings_field('professional_share_facebook_appid', 'Facebook App ID', 'professional_share_facebook_appid_setting', 'professional_share', 'professional_share_facebook');
    add_settings_field('professional_share_facebook_uid', 'Facebook Admin User ID', 'professional_share_facebook_uid_setting', 'professional_share', 'professional_share_facebook');    
  
}

// Twitter
function professional_share_twitter_section_text() {
    echo '<p>Enter your Twitter information here. Everyone should have a Twitter account for sharing.</p>';
}

function professional_share_twitter_user_setting_string() {
    $options = get_option('professional_share_options');
    echo "@<input id='professional_share_twitter_user' name='professional_share_options[twitter_user_string]' size='40' type='text' value='{$options['twitter_user_string']}' /> (e.g. KenMorico)";
}

// Facebook
function professional_share_facebook_section_text() {
    echo '<p>Enter your Facebook information here. This optional and for advanced users.</p>';
}
function professional_share_facebook_appid_setting() {
    $options = get_option('professional_share_options');
    echo "<input id='professional_share_facebook_appid' name='professional_share_options[facebook_appid]' size='40' type='text' value='{$options['facebook_appid']}' /> (e.g. 282320758521600) [Optional]";
}
function professional_share_facebook_uid_setting() {
    $options = get_option('professional_share_options');
    echo "<input id='professional_share_facebook_uid' name='professional_share_options[facebook_uid]' size='40' type='text' value='{$options['facebook_uid']}' /> (e.g. 15022342) [Optional]";
}

//Position options
function professional_share_position_section_text() {
    echo '<p>Set your position preferences here. If no boxes are checked the buttons will not display..</p>';
}

function professional_share_position_post_top_setting() {
    $options = get_option('professional_share_options');
    ?>
    <input type="checkbox" id="professional_share_post_top"  name="professional_share_options[post_top]" value="1"<?php checked( 1 == $options['post_top'] ); ?> />
    <?php
}

function professional_share_position_post_bottom_setting() {
    $options = get_option('professional_share_options');
    ?>
    <input type="checkbox" id="professional_share_post_bottom" name="professional_share_options[post_bottom]" value="1"<?php checked( 1 == $options['post_bottom'] ); ?> />
    <?php
}

function professional_share_position_page_top_setting() {
    $options = get_option('professional_share_options');
    ?>
    <input type="checkbox" id="professional_share_page_top" name="professional_share_options[page_top]" value="1"<?php checked( 1 == $options['page_top'] ); ?> />
    <?php
}

function professional_share_position_page_bottom_setting() {
    $options = get_option('professional_share_options');
    ?>
    <input type="checkbox" id="professional_share_page_bottom" name="professional_share_options[page_bottom]" value="1"<?php checked( 1 == $options['page_bottom'] ); ?> />
    <?php
}


// validate our options
function professional_share_options_validate($input) {
    /* $newinput['text_string'] = trim($input['text_string']);
      if(!preg_match('/^[a-z0-9]{32}$/i', $newinput['text_string'])) {
      $newinput['text_string'] = '';
      }
      return $newinput; */
    return $input;
}

// -----------------------------------
// PLUGIN FUNCTIONALITY

function init_professional_share(){
    add_professional_share_meta_tags();
wp_enqueue_script("professional-share-js", plugins_url('/js/professional-share.js', __FILE__)); 
wp_enqueue_style("professional-share-style", plugins_url('/css/style.css', __FILE__)); 
}


// start head additions----------------------------------------
function professional_share_filter_html_tag() { //Add fb namespace and schema.org itemscope
	//fb namespace
	echo ' xmlns:fb="http://ogp.me/ns/fb#" ';
                        echo 'lang="'.get_bloginfo( "language").'"';
	//schema.org itemscope
	if (is_single() || is_page()) {
		echo ' itemscope itemtype="http://schema.org/Article" ';
	} else {
		echo ' itemscope itemtype="http://schema.org/Blog" ';
	}
}

/*opengraph and schema stuff*/
//makes the title, url, site name, description, type opengraph meta tags if default image is set
function professional_share_opengraph_tags() {
	if(is_single() || is_page()){ // Post
		if (have_posts()) : while (have_posts()) : the_post(); 
			echo "\n\t<meta property='og:title' content='",get_the_title($post->post_title),"' />",
				"\n\t<meta property='og:url' content='",get_permalink(),"' />",
				"\n\t<meta property='og:site_name' content='",get_option('blogname'),"' />",
				"\n\t<meta property='og:description' content='",professional_share_excerpt_max_charlength(300),"' />",
				"\n\t<meta property='og:type' content='article' />",
				"\n\t<meta itemprop='name' content='",get_the_title($post->post_title),"' />",
				"\n\t<meta itemprop='description' content='",professional_share_excerpt_max_charlength(300),"' />";
			
                                                                        // check if the post has a Featured Image (Post Thumbnail) assigned to it.
                                                                        if ( has_post_thumbnail() ) {
                                                                             $large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large');
                                                                               echo "\n\t<meta property='og:image' content='".$large_image_url[0]."' />";	
                                                                               echo "\n\t<meta itemprop='image' content='".$large_image_url[0]."' />";
                                                                        }else{ 
                                                                            // let social services pull default image if none is set in WordPress
                                                                            $images_array = professional_share_get_images();
                                                                            foreach ($images_array as $image) {
                                                                                    if ($image != '') {
                                                                                            echo "\n\t<meta property='og:image' content='$image' />";
                                                                                    }
                                                                            }
                                                                            if(sizeof($images_array) >0) echo "\n\t<meta itemprop='image' content='",$images_array[0],"' />";
                                                                        }
		endwhile; endif; 
	}
	elseif(is_home() || is_front_page()) {
		echo "\n\t<meta property='og:title' content='",get_option('blogname'),"' />",
			"\n\t<meta property='og:url' content='",get_option('siteurl'),"' />",
			"\n\t<meta property='og:site_name' content='",get_option('blogname'),"' />",
			"\n\t<meta property='og:description' content='",get_option('blogdescription'),"' />",
			"\n\t<meta property='og:type' content='blog' />",
			"\n\t<meta itemprop='name' content='",get_option('siteurl'),"' />",
			"\n\t<meta itemprop='description' content='",get_option('blogdescription'),"' />";
			
	}
                        
	else{
		echo "\n\t<meta property='og:title' content='",get_option('blogname'),"' />",
			"\n\t<meta property='og:url' content='",get_option('siteurl'),"' />",
			"\n\t<meta property='og:site_name' content='",get_option('blogname'),"' />",
			"\n\t<meta property='og:description' content='",get_option('blogdescription'),"' />",
			"\n\t<meta property='og:type' content='article' />",
			"\n\t<meta itemprop='name' content='",get_option('siteurl'),"' />",
			"\n\t<meta itemprop='description' content='",get_option('blogdescription'),"' />";
			
	}
	
}

//returns an array of attachments from the post
function professional_share_get_images() {
	// Including global WP Enviroment.
	global $post, $posts;
	global $current_blog;
	//$options = get_option('professional_share_options');
	
	$the_images = array();
	
	//Getting images attached to the post.
	$args = array(
		'post_type'      => 'attachment',
		'post_mime_type' => 'image',
		'numberposts'    => -1,
		'order'          => 'ASC',
		'post_status'    => null,
		'post_parent'    => $post->ID
	);
	
	$attachments = get_posts($args);
	
	// Check for attachments.
	if ($attachments) {
		// Cycling through attachments.
		for($i = 0, $size = sizeof($attachments); $i < $size; ++$i){
			// Retrieving image url.
			$the_images[$i] = wp_get_attachment_url($attachments[$i]->ID);
			//add hostname if url is relative (starts with /)
			if (substr($the_images[$i], 0, 1) == '/') {
				$the_images[$i]	= get_option('siteurl') . $the_images[$i]; //'http://' . $current_blog->domain
			}			
		}
	} else {
		// there are no attachment for the current post.  Return default image.
		/*if ($options["default_image"] != '') {
			$the_images[0] = $options['default_image']; 
		}*/
	}
	return $the_images;	
}

/* Extracts the content, removes tags, replaces single and double quotes, cuts it, removes the caption shortcode */
function professional_share_excerpt_max_charlength($charlength) {
    // check for existing excerpt
    $my_excerpt = get_the_excerpt();
if ( $my_excerpt != '' ) {
	$content = $my_excerpt;
}else {
    $content = get_the_content(); //get the content
}

	
	$content = strip_tags($content); // strip all html tags
	$quotes = array('/"/',"/'/"); 
	$replacements = array('&quot;','&#39;');
	$content = preg_replace($quotes,$replacements,$content);
	$regex = "#([[]caption)(.*)([[]/caption[]])#e"; // the regex to remove the caption shortcude tag
	$content = preg_replace($regex,'',$content); // remove the caption shortcude tag
	$content = preg_replace( '/\r\n/', ' ', trim($content) ); // remove all new lines
	
	$excerpt = $content;
	$charlength++;
	if(strlen($excerpt)>$charlength) {
		$subex = substr($excerpt,0,$charlength-5);
		$exwords = explode(" ",$subex);
		$excut = -(strlen($exwords[count($exwords)-1]));
		if($excut<0) {
			return substr($subex,0,$excut).'...';
		} else {
			return $subex.'...';
		}
	} else {
		return $excerpt;
	}
}
/*end opengraph and schema stuff*/



function add_professional_share_meta_tags(){
    $options = get_option('professional_share_options');
    $fbAppID = $options['facebook_appid'] ? $options['facebook_appid'] :  "282320758521600";
    echo '<meta property="fb:admins" content="'.$options['facebook_uid'].'" />';
    echo '<meta property="fb:app_id" content="'.$fbAppID.'" />';
}

// end head additions ----------------------------------

// setup buttons----------------------

function fullUrl()
{
    $s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
    $sp = strtolower($_SERVER["SERVER_PROTOCOL"]);
    $protocol = substr($sp, 0, strpos($sp, "/")) . $s;
    $port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]);
    return $protocol . "://" . $_SERVER['SERVER_NAME'] . $port . $_SERVER['REQUEST_URI'];
}

function urlToShare() {
    $url = "";
    if (is_home()) {
        $url = get_site_url();
    }elseif (is_archive()){
        $url = fullUrl();
    }
    else{
        $url = get_permalink();
    }
    return $url;
}

function createShareBtns($printBtns = false) {
    $btns = "";
    $options = get_option('professional_share_options');
    $twitterUser = $options['twitter_user_string'];
    $linkedInBtn = '<div class="PSBtn"><script type="IN/Share" data-counter="right" data-showzero="true" data-onsuccess="LinkedInShare"></script></div>';
    $twitterBtn = '<div class="PSBtn"><a href="https://twitter.com/share" class="twitter-share-button" data-lang="en" data-via="' . $twitterUser . '"></a></div>';
    $gPlusBtn = '<div class="PSBtn"><div class="g-plusone" data-size="medium"></div></div>';
    $fbBtn = '<div class="PSBtn"><div class="fb-like" data-href="'.  urlToShare().'" data-send="false" data-width="90" data-layout="button_count" data-show-faces="false" data-action="recommend"></div></div>';
        
    $btns = '<!-- Professional Share Plugin--><div class="ProfessionalShareBox">'.$linkedInBtn . $twitterBtn . $gPlusBtn  . $fbBtn.'</div><div id="fb-root"></div>';
    if($printBtns) echo $btns;
        else return $btns;
}


function print_share($content) {
    $options = get_option('professional_share_options');
    if (is_single()) {
        $newContent = "";
        $btns = createShareBtns();
        if(isset($options['post_top']))$newContent = $btns;
        $newContent = $newContent . $content;
        if(isset($options['post_bottom']))$newContent = $newContent . $btns;
        
        return $newContent;
    }
    elseif(is_page()){
        $newContent = "";
        $btns = createShareBtns();
        if(isset($options['page_top']))$newContent = $btns;
        $newContent = $newContent . $content;
        if(isset($options['page_bottom']))$newContent = $newContent . $btns;
        return $newContent;
    }else{
        return $content;
    }
}

function createShareBtnsForHook(){
    createShareBtns(true); 
}

function shortCodeSetup($atts){
        if($atts == "") {
            createShareBtns(true); // default, show buttons
            return;
        }
    
    extract( shortcode_atts( array(
		'show' => 'true',
	), $atts ) );
    if($show == 'false') remove_filter('the_content', 'print_share',20);//don't trigger buttons
    if($show == 'true')createShareBtns(true);
}

// end setup buttons----------------------

// widget----------------------
include_once('Widget.php');

// hooks ------------------

add_action('professional_share', 'createShareBtnsForHook'); //echo btns at hook
// end hooks-------------


add_action('wp_head', 'init_professional_share',5);// enqueue css and js, add basic meta tags for FB sharing

add_filter('language_attributes', 'professional_share_filter_html_tag');//Add fb namespace and schema.org itemscope
add_action('wp_head', 'professional_share_opengraph_tags',6);//Add the opengraph meta tags to wp_head

add_filter('the_content', 'print_share',20);
//add_shortcode('professional_share', 'createShareBtns',true); //add [professional_share] shortcode to manually output buttons
add_shortcode('professional_share', 'shortCodeSetup'); //add [professional_share] shortcode to manually output buttons

/*@TODO add class
$myEmailClass = new emailer();
add_action('publish_post', array($myEmailClass, 'send'));
 
 */
?>