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

//no direct access
defined('_JEXEC') or die('Restricted access');


$layout = $params->get('layout', 'default');

require(JModuleHelper::getLayoutPath('mod_pParallax', $layout));