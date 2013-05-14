<?php
/**
 * @package		jTweet
 * @subpackage	jTweet
 * @author		Joomla Bamboo - design@joomlabamboo.com
 * @copyright 	Copyright (c) 2012 Joomla Bamboo. All rights reserved.
 * @license		GNU General Public License version 2 or later
 * @version		2.0.5
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

class JElementScripts extends JElement {
	var	$_name = 'scripts';

	function fetchElement( $name, $value, &$node, $control_name )
	{

		ob_start();
			$document = JFactory::getDocument();
			$document->addScript(''.JURI::root(true).'/modules/mod_jTweet/js/admin/jquery-1.6.4.min.js');
			$document->addScript(''.JURI::root(true).'/modules/mod_jTweet/js/admin/jquery.noconflict.js');
			$document->addScript(''.JURI::root(true).'/modules/mod_jTweet/js/admin/sortables.js');
			$document->addScript(''.JURI::root(true).'/modules/mod_jTweet/js/admin/jquery-ui-1.8.16.custom.min.js');

			$document->addScript(''.JURI::root(true).'/modules/mod_jTweet/js/admin/scripts15.js');
			?>

			<div id="zentools">

		<?php return ob_get_clean();
	}

	function fetchTooltip($label, $description, &$node, $control_name, $name){
		// Output
		return '&nbsp;';
	}
}
