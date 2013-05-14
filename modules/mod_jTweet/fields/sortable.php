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

class JFormFieldSortable extends JFormField
{
   protected   $type = 'Sortable';

   protected function getInput()
   {
		// Global Document
		$document 	=& JFactory::getDocument();

		// Params
		$document->addStyleSheet( ''.JURI::root(true).'/modules/mod_jTweet/css/admin/admin.css' );



		ob_start();	?>

			<div id="items">

				<ul id="sortable" class="ui-sortable">
					<li class="disabled">Drag items here to use</li>
				</ul>
				<ul id="sortable2" class="ui-sortable">
					<li class="disabled">Available Items</li>
				</ul>
			</div>


		<?php
		return ob_get_clean();

	}
}
