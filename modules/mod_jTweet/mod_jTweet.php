<?php
/**
 * @package		jTweet
 * @subpackage	jTweet
 * @author		Joomla Bamboo - design@joomlabamboo.com
 * @copyright 	Copyright (c) 2012 Joomla Bamboo. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @version		2.0.4
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

$document =& JFactory::getDocument();

// Import the filesystem
jimport( 'joomla.filesystem.file' );

// Test to see if the default template is a zgf v2 template
$app = JFactory::getApplication();
$framework = JPATH_ROOT.'/templates/'.$app->getTemplate().'includes/config.php';
$moduleID = $module->id;
$modbase = JURI::base(true).'/modules/mod_jTweet/';

// Test to see if cache is enabled
if (version_compare(JVERSION, '1.6', '>='))
{

	// Test to see if cache is enabled
	if ($app->getCfg('caching')) {
		$cache = 1;
	}
	else {
		$cache = 0;
	}
}
else
{
	// Test to see if cache is enabled
	if ($mainframe->getCfg('caching')) {
		$cache = 1;
	}
	else {
		$cache = 0;
	}
}

// Include the syndicate functions only once
require_once (dirname(__FILE__).'/helper.php');

$scripts = $params->get( 'scripts', 1);

// Load css and script references into the head
if ($scripts) {
		if (!$cache) {
			$document->addStyleSheet($modbase.'css/jTweet.css');
			$document->addScript($modbase . "js/jquery.tweet.js");
		}
}

$style = '.tweetcol1 {width: '.($params->get( 'col1Width') - 3.8).'%}';
$style .= '.tweetcol2 {width: '.($params->get( 'col2Width') - 3.8).'%}';
$style .= '.tweetcol3 {width: '.($params->get( 'col3Width') - 3.8).'%}';
$style .= '#twittericon {background: url('.$modbase.'images/'.$params->get( "twitterBird").'.png) no-repeat}';

$document->addStyleDeclaration( $style );

require(JModuleHelper::getLayoutPath('mod_jTweet', 'default'));
?>
