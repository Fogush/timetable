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

class JFormFieldScripts extends JFormField
{
   protected   $type = 'Scripts';

   protected function getInput()
   {
		$result = '';

		$document = JFactory::getDocument();

		if (version_compare(JVERSION, '3.0', '<'))
		{
			$document->addScript(''.JURI::root(true).'/modules/mod_jTweet/js/admin/jquery-1.6.4.min.js');
			$document->addScript(''.JURI::root(true).'/modules/mod_jTweet/js/admin/jquery.noconflict.js');
			$document->addScript(''.JURI::root(true).'/modules/mod_jTweet/js/admin/sortables.js');
			$document->addScript(''.JURI::root(true).'/modules/mod_jTweet/js/admin/jquery-ui-1.8.16.custom.min.js');
		}
		else
		{
			$document->addScript(''.JURI::root(true).'/modules/mod_jTweet/js/admin/sortables.js');
			$document->addScript(''.JURI::root(true).'/modules/mod_jTweet/js/admin/jquery-ui-1.8.24.custom.min.js');
		}

		if (version_compare(JVERSION, '3.0', '<'))
		{
			$document->addScript(''.JURI::root(true).'/modules/mod_jTweet/js/admin/scripts25.js');
			$result = '<div id="zentools">';
		}
		else
		{
			$document->addScript(''.JURI::root(true).'/modules/mod_jTweet/js/admin/scripts30.js');
		}

		return $result;
	}

	function fetchTooltip($label, $description, &$node, $control_name, $name){
		// Output
		return '&nbsp;';
	}
}
