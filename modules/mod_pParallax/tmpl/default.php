<?php 
/*------------------------------------------------------------------------
# Top Position Slider (module)
# ------------------------------------------------------------------------
# version		1.0.0
# author    	Top Position
# copyright 	Copyright (c) 2011 Top Position	 All rights reserved.
# @license 		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL

	based on  	PARALLAX CONTENT SLIDER WITH CSS3 AND JQUERY
				http://tympanus.net/codrops/2012/03/15/parallax-content-slider-with-css3-and-jquery/
				http://tympanus.net/codrops/licensing/

# Websites 	http://posicionamientoenbuscadoreswebseo.es/
-------------------------------------------------------------------------
*/

defined('_JEXEC') or die('Restricted access');

$document = JFactory::getDocument();
$readmore 	= $params->get('readmore');
$height = $params->get('height','500');
$imgpath 	= $params->get('imgpath','modules/mod_pParallax/assets/images/');
$back1 = $params->get('back1');
$autoplay = $params->get('autoplay');
$number = $params->get('number',3);

$document->addStyleSheet(JURI :: base().'modules/mod_pParallax/assets/css/style.css');
$document->addScript( JURI::base() .'modules/mod_pParallax/assets/js/modernizr.custom.28468.js' );
$document->addScript( JURI::base() .'modules/mod_pParallax/assets/js/jquery.cslider.js' );
$style = '.da-slider{
	height:'.$height.'px;
	background: transparent url('.JURI::base().$imgpath.$back1.') repeat 0% 0%;
}';
$document->addStyleDeclaration($style);
if($autoplay){
$document->addScriptDeclaration("

   jQuery(document).ready(function($){
      $('#da-slider').cslider({
			
					autoplay	: true,
					bgincrement	: 450,
					interval:5000
			});
			
    });
");

 }
 else
 {
 	$document->addScriptDeclaration("
	jQuery(document).ready(function($){
	$('#da-slider').cslider();
	});
	");
 }
 
?>
<div id="da-slider" class="da-slider">
<?php for($i=0;$i<$number;$i++) { 

		if($i==0) { $title= $params->get('title1',''); $text = $params->get('text1',''); $image = $params->get('image1',''); $link = $params->get('link1','');}
		if($i==1) { $title= $params->get('title2',''); $text = $params->get('text2',''); $image = $params->get('image2',''); $link = $params->get('link2','');}
		if($i==2) { $title= $params->get('title3',''); $text = $params->get('text3',''); $image = $params->get('image3',''); $link = $params->get('link3','');}
		if($i==3) { $title= $params->get('title4',''); $text = $params->get('text4',''); $image = $params->get('image4',''); $link = $params->get('link4','');}
		if($i==4) { $title= $params->get('title5',''); $text = $params->get('text5',''); $image = $params->get('image5',''); $link = $params->get('link5','');}
		if($i==5) { $title= $params->get('title6',''); $text = $params->get('text6',''); $image = $params->get('image6',''); $link = $params->get('link6','');}
		if($i==6) { $title= $params->get('title7',''); $text = $params->get('text7',''); $image = $params->get('image7',''); $link = $params->get('link7','');}
		if($i==7) { $title= $params->get('title8',''); $text = $params->get('text8',''); $image = $params->get('image8',''); $link = $params->get('link8','');}
		if($i==8) { $title= $params->get('title9',''); $text = $params->get('text9',''); $image = $params->get('image9',''); $link = $params->get('link9','');}
		if($i==9) { $title= $params->get('title10',''); $text = $params->get('text10',''); $image = $params->get('image10',''); $link = $params->get('link10','');}

?>     
<div class="da-slide">
<h2><?php echo $title; ?></h2>
<p><?php echo $text; ?></p>
<?php if($readmore!=''):?>
<a href="<?php echo $link; ?>" class="da-link"><?php echo $readmore; ?></a>
<?php endif;?>
<div class="da-img"><img src="<?php echo JURI::base().$imgpath.$image; ?>" alt="<?php echo $title; ?>" /></div>
</div>
<?php } ?>           		
<nav class="da-arrows">
<span class="da-arrows-prev"></span>
<span class="da-arrows-next"></span>
</nav>
</div>

</div>	