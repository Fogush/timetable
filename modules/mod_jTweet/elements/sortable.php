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
 * @package RocketTheme
 * @subpackage roktabs.elements
 */
class JElementSortable extends JElement {

	function fetchElement($name, $value, &$node, $control_name)
	{

		global $mainframe;
		$display		= $node->attributes( 'display' );

		// Global Document
		$document 	=& JFactory::getDocument();

		// Params
		$document->addStyleSheet( ''.JURI::root(true).'/modules/mod_jTweet/css/admin/admin.css' );

		ob_start();	?>
		<div id="help"></div>


			<div id="zenmessage"><p></p></div>
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
