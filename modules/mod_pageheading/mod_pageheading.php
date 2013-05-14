<?php
/**
 * @package    pageheading
 * @subpackage D:
 * @author     RevivalPixel {@link www.revivalpixel.com}
 * @author     Created on 08-Dec-2012
 * @license    GNU/GPL
 */

//-- No direct access
defined('_JEXEC') || die('=;)');


$title = $params->get('title', '');
$subtitle = $params->get('subtitle', '');

$somecss = '#rt-showcase {height:'.$height.'px;}
#container {top:'.$height.'px;}
#rt-showcase .page-title {top:'.($height-46).'px;}';
	$doc =& JFactory::getDocument();	
	$doc->addStyleDeclaration($somecss);

?><div class="rt-container">
<div class="bannergroup">
<?php if($title!='') :?>
  <h1 class="page-heading"><?php echo $title; ?></h1>
  <?php endif;?>
  <?php if($subtitle!=''):?>
  <span class="page-sub-heading"><?php echo $subtitle; ?></span>
  <?php endif;?>
</div></div>
