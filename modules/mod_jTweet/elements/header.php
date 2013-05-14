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
class JElementHeader extends JElement {
	var	$_name = 'header';
	function fetchElement($name, $value, &$node, $control_name){
		// Output
		return '
		<div class="moduleheader '.JText::_($value).'"><h2>
			'.JText::_($value).'
		</h2></div>
		';
	}
	function fetchTooltip($label, $description, &$node, $control_name, $name){
		// Output
		return '&nbsp;';
	}
}
