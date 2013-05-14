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
class JElementDescription extends JElement {
	var	$_name = 'header';
	function fetchElement($name, $value, &$node, $control_name){
		// Output
		return '
		<p class="description '.JText::_($node->attributes('panel')).'">
			'.JText::_($value).'
		</p>
		';
	}
	function fetchTooltip($label, $description, &$node, $control_name, $name){
		// Output
		return '&nbsp;';
	}
}
