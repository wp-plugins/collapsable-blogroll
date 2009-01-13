<?php
/*
Plugin Name: Collapsable blogroll
Plugin URI: 
Description:  Output the built-in blogroll onto a static page. The categories can be collapsed.
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

/****************************************************************
*** Backend
****************************************************************/



function collroll_menu()
{   
	if ( $_GET['page'] == "collapsable-blogroll" ) 
	{
		wp_register_script('cp_prototype', LINKSPAGE_URLPATH . 'colorpicker/refresh_web/prototype.js', array(), '1.6');
		wp_enqueue_script('cp_prototype');
		
		wp_register_script('cp_colorMethods', LINKSPAGE_URLPATH . 'colorpicker/refresh_web/colorpicker/ColorMethods.js');
		wp_enqueue_script('cp_colorMethods');
		
		wp_register_script('cp_colorValuePicker', LINKSPAGE_URLPATH . 'colorpicker/refresh_web/colorpicker/ColorValuePicker.js');
		wp_enqueue_script('cp_colorValuePicker');
	
		wp_register_script('cp_slider', LINKSPAGE_URLPATH . 'colorpicker/refresh_web/colorpicker/Slider.js');
		wp_enqueue_script('cp_slider');
		
		wp_register_script('cp_colorPicker', LINKSPAGE_URLPATH . 'colorpicker/refresh_web/colorpicker/ColorPicker.js');
		wp_enqueue_script('cp_colorPicker');			
	}
	
	if (function_exists('add_submenu_page'))
        add_submenu_page(/*collroll_getTarget()*/'options-general.php', 'Collapsing Blogroll', 'Collapsing Blogroll', 5, "collapsable-blogroll", 'collroll');
}

//Switch page target depending on version
function collroll_getTarget() 
{
	global $wp_version;
	if (version_compare($wp_version, '2.6.5', '>'))
		return "link-manager.php";
	else
		return "edit.php";
}

function collroll()
{	
	$options = $newoptions = get_option('collroll');
	$color = $options['color'];
	$msg = '';
	
	if ( $_POST['menu-submit'] ) 
	{
	    $newoptions['color'] = $_POST['color'];
	}
	
	if ( $options != $newoptions ) 
	{
	    $options = $newoptions;
	    $color = $options['color'];
	    update_option('collroll', $options);
	} 
	else
	{
		// I want to output a message box that disappears 
		//$msg = '<div id="message">Color not changed.</div>';
	}
	
	if ( empty($color) )
		$color = 'ff0000';

?>
	<div class="wrap">
		
		<?php echo $msg; ?>

		<h2>Collapsible blogroll</h2>
		
		<p>
		Select the category background color:
		
		<form id="colorform" action="options-general.php?page=collapsable-blogroll" method="post">
		
			<table>
				<tr>
					<td valign="top">
						<div id="cp1_ColorMap"></div>
					</td>
					<td valign="top">
						<div id="cp1_ColorBar"></div>
					</td>
		
					<td valign="top">
		
						<table>
							<tr>
								<td colspan="3">
									<div id="cp1_Preview" style="background-color: #fff; width: 60px; height: 60px; padding: 0; margin: 0; border: solid 1px #000;">
										<br />
									</div>
								</td>
							</tr>
							<tr>
								<td>
									<input type="radio" id="cp1_HueRadio" name="cp1_Mode" value="0" />
								</td>
								<td>
									<label for="cp1_HueRadio">H:</label>
								</td>
								<td>
									<input type="text" id="cp1_Hue" value="0" style="width: 40px;" /> &deg;
								</td>
							</tr>
		
							<tr>
								<td>
									<input type="radio" id="cp1_SaturationRadio" name="cp1_Mode" value="1" />
								</td>
								<td>
									<label for="cp1_SaturationRadio">S:</label>
								</td>
								<td>
									<input type="text" id="cp1_Saturation" value="100" style="width: 40px;" /> %
								</td>
							</tr>
		
							<tr>
								<td>
									<input type="radio" id="cp1_BrightnessRadio" name="cp1_Mode" value="2" />
								</td>
								<td>
									<label for="cp1_BrightnessRadio">B:</label>
								</td>
								<td>
									<input type="text" id="cp1_Brightness" value="100" style="width: 40px;" /> %
								</td>
							</tr>
		
							<tr>
								<td colspan="3" height="5">
		
								</td>
							</tr>
		
							<tr>
								<td>
									<input type="radio" id="cp1_RedRadio" name="cp1_Mode" value="r" />
								</td>
								<td>
									<label for="cp1_RedRadio">R:</label>
								</td>
								<td>
									<input type="text" id="cp1_Red" value="255" style="width: 40px;" />
								</td>
							</tr>
		
							<tr>
								<td>
									<input type="radio" id="cp1_GreenRadio" name="cp1_Mode" value="g" />
								</td>
								<td>
									<label for="cp1_GreenRadio">G:</label>
								</td>
								<td>
									<input type="text" id="cp1_Green" value="0" style="width: 40px;" />
								</td>
							</tr>
		
							<tr>
								<td>
									<input type="radio" id="cp1_BlueRadio" name="cp1_Mode" value="b" />
								</td>
								<td>
									<label for="cp1_BlueRadio">B:</label>
								</td>
								<td>
									<input type="text" id="cp1_Blue" value="0" style="width: 40px;" />
								</td>
							</tr>
		
		
							<tr>
								<td>
									#:
								</td>
								<td colspan="2">
									<!--<input type="hidden" name="page" value="collapsable-blogroll" />-->
									<input type="text" id="cp1_Hex" name="color" value="FF0000" style="width: 60px;" />
								</td>
							</tr>
		
						</table>
					</td>
				</tr>
			</table>
			
		<input type="submit" name="menu-submit" class="button-primary" value="Save Changes" />
		</form>
			
		
			<div style="display:none;">
			
				<img src="<?php echo LINKSPAGE_URLPATH; ?>colorpicker/colorpicker/images/rangearrows.gif" />
				<img src="<?php echo LINKSPAGE_URLPATH; ?>colorpicker/colorpicker/images/mappoint.gif" />
				<img src="<?php echo LINKSPAGE_URLPATH; ?>colorpicker/colorpicker/images/bar-saturation.png" />
				<img src="<?php echo LINKSPAGE_URLPATH; ?>colorpicker/colorpicker/images/bar-brightness.png" />
				<img src="<?php echo LINKSPAGE_URLPATH; ?>colorpicker/colorpicker/images/bar-blue-tl.png" />
				<img src="<?php echo LINKSPAGE_URLPATH; ?>colorpicker/colorpicker/images/bar-blue-tr.png" />
				<img src="<?php echo LINKSPAGE_URLPATH; ?>colorpicker/colorpicker/images/bar-blue-bl.png" />
				<img src="<?php echo LINKSPAGE_URLPATH; ?>colorpicker/colorpicker/images/bar-blue-br.png" />
				<img src="<?php echo LINKSPAGE_URLPATH; ?>colorpicker/colorpicker/images/bar-red-tl.png" />
				<img src="<?php echo LINKSPAGE_URLPATH; ?>colorpicker/colorpicker/images/bar-red-tr.png" />
				<img src="<?php echo LINKSPAGE_URLPATH; ?>colorpicker/colorpicker/images/bar-red-bl.png" />
				<img src="<?php echo LINKSPAGE_URLPATH; ?>colorpicker/colorpicker/images/bar-red-br.png" />	
				<img src="<?php echo LINKSPAGE_URLPATH; ?>colorpicker/colorpicker/images/bar-green-tl.png" />
				<img src="<?php echo LINKSPAGE_URLPATH; ?>colorpicker/colorpicker/images/bar-green-tr.png" />
				<img src="<?php echo LINKSPAGE_URLPATH; ?>colorpicker/colorpicker/images/bar-green-bl.png" />
				<img src="<?php echo LINKSPAGE_URLPATH; ?>colorpicker/colorpicker/images/bar-green-br.png" />
				<img src="<?php echo LINKSPAGE_URLPATH; ?>colorpicker/colorpicker/images/map-red-max.png" />
				<img src="<?php echo LINKSPAGE_URLPATH; ?>colorpicker/colorpicker/images/map-red-min.png" />
				<img src="<?php echo LINKSPAGE_URLPATH; ?>colorpicker/colorpicker/images/map-green-max.png" />
				<img src="<?php echo LINKSPAGE_URLPATH; ?>colorpicker/colorpicker/images/map-green-min.png" />
				<img src="<?php echo LINKSPAGE_URLPATH; ?>colorpicker/colorpicker/images/map-blue-max.png" />
				<img src="<?php echo LINKSPAGE_URLPATH; ?>colorpicker/colorpicker/images/map-blue-min.png" />
				<img src="<?php echo LINKSPAGE_URLPATH; ?>colorpicker/colorpicker/images/map-saturation.png" />
				<img src="<?php echo LINKSPAGE_URLPATH; ?>colorpicker/colorpicker/images/map-saturation-overlay.png" />
				<img src="<?php echo LINKSPAGE_URLPATH; ?>colorpicker/colorpicker/images/map-brightness.png" />
				<img src="<?php echo LINKSPAGE_URLPATH; ?>colorpicker/colorpicker/images/map-hue.png" />
						
			</div>
			
			<script type="text/javascript">
			
			Event.observe(window,'load',function() {
				cp1 = new Refresh.Web.ColorPicker('cp1',{startHex: '<?php echo $color; ?>', startMode:'s'});
			});
			
			
			</script>
		</p>
	
	</div>
<?php
}


/*-----------------------------------------------------*/
add_action('admin_menu', 'collroll_menu');
/*-----------------------------------------------------*/


/****************************************************************
*** FRONTEND
****************************************************************/

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
	$options = get_option('collroll');
	$color = $options['color'];
	if (empty($color))
		$color = "eeeeee";
		
	echo "\n".'<style type="text/css" media="screen">@import "'.LINKSPAGE_URLPATH.'style.css";</style>';
	
	echo "\n".'
		<style tyle="text/css">
		  .clplinkcategory { background-color: #'. 	$color .'; }
		</style>
		';

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

/*-----------------------------------------------------*/

add_action('wp_head', 'catlinks_header', 1);
add_filter('the_content', 'catlinks_page');

/*-----------------------------------------------------*/

?>