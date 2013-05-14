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


/**
 * Form Field class for the Joomla Framework.
 *
 * @package		Mod_zentools
 * @subpackage	Form
 * @since		1.6
 */

class JFormFieldDescription extends JFormField
{
	protected   $type = 'Header';

	protected function getInput()
	{
		$default = (string) $this->element['default'];
		$paneldesc = (string) $this->element['panel'];

		$panel = '<p class="description '.JText::_($paneldesc).'">';
		$panel .= ''.JText::_($default).'';
		$panel .= '</p>';

		return $panel;
	}
}
