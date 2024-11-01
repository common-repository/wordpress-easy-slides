<?php
/*
Plugin Name: WordPress Easy Slides
Version: 1.4
Plugin URI: http://www.doumiaoer.com/?p=1271
Description:Generate a section in wordpress page or post where sildes show automatically
Author: Jun
Author URI: http://www.doumiaoer.com/
*/



add_action('init', 'init_textdomain_easyslides');
function init_textdomain_easyslides(){
  load_plugin_textdomain('wp_easy_slides',PLUGINDIR . '/' . dirname(plugin_basename (__FILE__)) . '/lang');
}

register_activation_hook( __FILE__, 'display_easySlides_install');

register_deactivation_hook( __FILE__, 'display_easySlides_remove' );

function display_easySlides_install() {
	add_option("display_easySlides_title", " ", '', 'yes');
}

function display_easySlides_remove() {
	delete_option('display_easySlides_title');
}



if ( !defined('WP_PLUGIN_URL') )
  define( 'WP_PLUGIN_URL', WP_CONTENT_URL . '/plugins' );         //  plugin url

define('WP_EASYSLIDES_NAME', plugin_basename(__FILE__));
define('WP_EASYSLIDES_DIR', plugin_basename(dirname(__FILE__)));
define('WP_EASY_FILENAME', str_replace(WP_EASYSLIDES_DIR.'/', '', plugin_basename(__FILE__)));

if (is_admin()){
    add_action('admin_menu', 'wp_add_easy_slides_options_page');

    function wp_add_easy_slides_options_page() {
        add_options_page(__("Easy Slides",'wp_easy_slides'), __("Easy Slides",'wp_easy_slides'), 8, __FILE__, 'wp_easy_slides_options_subpanel');
    }


	function wp_easy_slides_options_subpanel() {

?>
    <div class="wrap">

		<h2><?php _e("Easy Slides Settings",'wp_easy_slides');?></h2>
		<p><?php _e("<a href=\"http://www.doumiaoer.com/\">WordPress Easy Slides </a>Plugin can generate generate a section in wordpress page or post where sildes show automatically.",'wp_easy_slides');?> </p>
		<?php _e("Any problem or need help, please contact",'wp_easy_slides');?><a href="mailto:junwangjw90@gmail.com">jun wang</a>.</p>
		<div>
		<span style="font-size:16px; height:30px; line-height:30px; padding:0 10px;">
		<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=K9U3MHJH3UMKS&lc=CA&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donate_SM%2egif%3aNonHosted">
		<?php _e("Do you like this Plugin? Consider to donate!",'wp_easy_slides');?></a>
		</span>
		<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=K9U3MHJH3UMKS&lc=CA&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donate_SM%2egif%3aNonHosted">
		<img src="https://www.paypal.com/en_GB/i/btn/btn_donate_LG.gif" align="left" />
		</a>
		</div>
 <div>
		<h2><?php _e("Basic setting",'wp_easy_slides');?></h2>
		<form method="post" action="options.php">
			<?php /* the code below is used to save form into db ??????????????????? */ ?>
			<?php wp_nonce_field('update-options'); ?>

			<tr valign="top">
			<td><?php _e("Easy Slides Title:",'wp_easy_slides'); ?></td>
            <td>
				<input name="display_easySlides_title" type="text" size="35"  id="display_easySlides_title"  value="<?php echo get_option('display_easySlides_title'); ?>"  />

            </td>
			</tr>


			<p>
				<input type="hidden" name="action" value="update" />
				<input type="hidden" name="page_options" value="display_easySlides_title" />

				<input type="submit" value=<?php _e("save setting",'wp_easy_slides');?> class="button-primary" />
			</p>
		</form>
	</div>

<?php }

	add_action('wp_enqueue_scripts', 'my_scripts_method');
	function my_scripts_method() {
    wp_register_script('jquery'); // replace the xxx with valid script name
    wp_enqueue_script('jquery-ui-core');
    wp_enqueue_style('jquery-ui-tabs'); // if you have any
	}

    add_action('admin_head', 'loadAdminSettingCss');
    function loadAdminSettingCss() {
        echo '<link type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" />';
	?>
		<script type='text/javascript'>
		jQuery(document).ready(function(){
			jQuery("#wrapper").tabs();
		});
		</script>
	<?php
    }
} else{
    add_action('wp_head', 'load_css');
    function load_css() {
        ?>
		<style type="text/css">
		body{
			font-family:arial
		}
		.clear {
			clear:both
		}
		#wpEasySllides {
			position:relative;
			height:360px
		}
		#wpEasySllides a {
			float:left;
			position:absolute;
		}
		#wpEasySllides a img {
			border:none;
		}
		#wpEasySllides a.show {
			z-index:500
		}
		#wpEasySllides .caption {
			z-index:600;
			background-color:#000;
			color:#ffffff;
			height:80px;
			width:100%;
			position:absolute;
			bottom:0;
		}
		#wpEasySllides .caption .content {
			margin:5px
		}
		#wpEasySllides .caption .content h3 {
			margin:0;
			padding:0;
			color:#1DCCEE;
		}
		</style>
		<?php
    }


  	add_action('wp_head', 'load_js');
    function load_js() {
	?>
		<script type='text/javascript'>

		jQuery(document).ready(function(){
				slideShow();
		});

		function slideShow() {
			jQuery('#wpEasySllides a').css({opacity: 0.0});
			jQuery('#wpEasySllides a:first').css({opacity: 1.0});
			//Set the caption background to semi-transparent
			jQuery('#wpEasySllides .caption').css({opacity: 0.6});
			jQuery('#wpEasySllides .caption').css({width: jQuery('#wpEasySllides a').find('img').css('width')});
			jQuery('#wpEasySllides .content').html(jQuery('#wpEasySllides a:first').find('img').attr('rel'))
			.animate({opacity: 0.6}, 400);
			setInterval('wpEasySllides()',5000); //change to next image after 5 seconds

		}

		function wpEasySllides() {
			var current = (jQuery('#wpEasySllides a.show')?  jQuery('#wpEasySllides a.show') : jQuery('#wpEasySllides a:first'));
			//Get next image, if it reached the end of the slideshow, rotate it back to the first image
			var next = ((current.next().length) ? ((current.next().hasClass('caption'))? jQuery('#wpEasySllides a:first') :current.next()) : jQuery('#wpEasySllides a:first'));
			//Get next image caption
			var caption = next.find('img').attr('rel');
			//Set the fade in effect for the next image, show class has higher z-index
			next.css({opacity: 0.0})
			.addClass('show')
			.animate({opacity: 1.0}, 1000);
			//Hide the current image
			current.animate({opacity: 0.0}, 1000)
			.removeClass('show');
			//Set the opacity to 0 and height to 1px
			jQuery('#wpEasySllides .caption').animate({opacity: 0.0}, { queue:false, duration:0 }).animate({height: '1px'}, { queue:true, duration:300 });
			//Animate the caption, opacity to 0.5 and heigth to 60px, a slide up effect
			jQuery('#wpEasySllides .caption').animate({opacity: 0.6},80 ).animate({height: '80px'},500 );
			//Display the content
			jQuery('#wpEasySllides .content').html(caption);
		}
		</script>
	<?php
    }

	function wp_sliders_show() {
		$wp_easyslides_title = get_option('display_easySlides_title');

		if($wp_easyslides_title != '') {
			$wp_slides_title_display = $wp_easyslides_title;
		}else{
			$wp_slides_title_display = 	'Wordpress Easy Slides';
		}

		$result .= '<h1>'.$wp_slides_title_display.'</h1>';
		$result .= '<div id="wpEasySllides">
            <a href="http://www.doumiaoer.com/?p=1271" class="show">
				<img src="wp-content/plugins/wordpress-easy-slides/images_easySlides/copenhagenDenmark.jpg" alt="copenhagenDenmark" width="580" height="360" title="" alt="" rel="<h3>Copenhagen</h3>Copenhagen Denmark" />
			</a>
			<a href="http://www.doumiaoer.com">
				<img src="wp-content/plugins/wordpress-easy-slides/images_easySlides/lundSweden.jpg" alt="lundSweden" width="580" height="360" title="" alt="" rel="<h3>Lund</h3>lund of Sweden"/>
			</a>
			<a href="#">
				<img src="wp-content/plugins/wordpress-easy-slides/images_easySlides/simonLakeCanada.jpg" alt="simonLakeCanada" width="580" height="360" title="" alt="" rel="<h3>simon lake</h3>simon Lake Canada "/>
			</a>

			<div class="caption"><div class="content"></div></div>
		</div>
		<div class="clear"></div>';

        echo $result;
	}
}

?>