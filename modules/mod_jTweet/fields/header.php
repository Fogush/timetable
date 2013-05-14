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

class JFormFieldHeader extends JFormField
{
	protected   $type = 'Header';

	protected function getInput()
	{
		$default = (string) $this->element['default'];
		$panelTitle = (string) $this->element['title'];

		$panel = '<div class="moduleheader '.$panelTitle.'">';
		$panel .= '<h2>'.JText::_($default).'</h2>';
		$panel .= '</div>';

		return $panel;
	}
}
