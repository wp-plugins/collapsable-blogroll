<?php
/*
Plugin Name: Collapsable blogroll
Plugin URI: 
Description:  Output links seperated by categories onto a static page (Based on Links Page (http://www.asymptomatic.net/wp-hacks) by Owen Winkler and on Categorical Links Page (http://www.alucinari.net) by Jeremy Albrecht)
Version: 0.1
Author: Romain Schmitz
Author URI: http://slopjong.de
*/
?>
<?php
/*

INSTRUCTIONS
------------
1. Upload this file into your wp-content/plugins directory.
2. Activate the Links Page with Categories plugin in your WordPress admin panel.
3. Create a new static page.
4. Add <!--catlinkspage--> to the static page content where you want the links
to appear.

Enjoy!

*/
?>
<?php

define('LINKSPAGE_FOLDER', dirname(plugin_basename(__FILE__)));
define('LINKSPAGE_URLPATH', get_option('siteurl').'/wp-content/plugins/' . LINKSPAGE_FOLDER.'/');

function catlinks_page_callback()
{

	$output = '';
	
	// This variable isn't used yet. There should be the possibility to save settings in the options table
	// of the wordpress database
	$user_defined = '';
	
	// default settings
	$defaults = array(
		'orderby' => 'name', 
		'order' => 'ASC',
		'limit' => -1, 
		'category' => '', 
		'exclude_category' => '',
		'category_name' => '', 
		'hide_invisible' => 1,
		'show_updated' => 0, 
		'echo' => 1,
		'categorize' => 1, 
		'title_li' => __('Bookmarks'),
		'title_before' => '<h4>', 
		'title_after' => '</h4>',
		'category_orderby' => 'name', 
		'category_order' => 'ASC',
		'class' => 'linkcat', 
		'category_before' => '<a style="text-decoration:none;" onclick="switchMenu(\'%id\');"><h4 class="clplinkcategory">',
		'category_after' => '</h4></a>',
		'show_description' => '0'
	);

	$r = wp_parse_args( $user_defined, $defaults );
	extract( $r, EXTR_SKIP );

	$cats = get_terms('link_category', array('name__like' => $category_name, 'include' => $category, 'exclude' => $exclude_category, 'orderby' => $category_orderby, 'order' => $category_order, 'hierarchical' => 0));

	$output .= '<div class="catlinkspage">';
	
	foreach ( (array) $cats as $cat ) 
	{
		$params = array_merge($r, array('category'=>$cat->term_id));
		$bookmarks = get_bookmarks($params);
		
		if ( empty($bookmarks) )
			continue;

		$divid = 'lp_cat'.$cat->term_id;
		
		$output .= '<a style="text-decoration:none;" onclick="switchMenu(\''. $divid .'\');"><h4 class="clplinkcategory">';
		$output .= "$cat->name";
		$output .= '</h4></a>';
		$output .= '<div id="'. $divid .'" style="display:none;"><ul class="clplinklist">';
		$output .= _walk_bookmarks($bookmarks, $r);
		$output .= '</ul></div>';
	}
	
	$output .= '</div>';

	return $output;
}

function catlinks_page($content)
{
  if ( strpos($content, '<!--catlinkspage-->') !== false ) $content = catlinks_page_callback();
  return $content;
}

function catlinks_header()
{
	echo "\n".'<style type="text/css" media="screen">@import "'.LINKSPAGE_URLPATH.'style.css";</style>';
	
	echo '
	<script type="text/javascript">
	<!--
	function switchMenu(obj) 
	{
		var el = document.getElementById(obj);
		if ( el.style.display != "none" ) {
			el.style.display = \'none\';
		}
		else {
			el.style.display = \'\';
		}
	}
	
	//-->
	</script>
	';
}

add_action('wp_head', 'catlinks_header', 1);
add_filter('the_content', 'catlinks_page');

?>