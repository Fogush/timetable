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
defined( '_JEXEC' ) or die( 'Restricted access' );


// Check for single or multi-user option
$username = $params->get( 'username', 1);

if (strpos($username,',') == false)
{
	$singleuser = true;
}
else
{
	$singleuser = false;
}

$usernames = explode(",", $username);

if (is_array($usernames))
{
	$numItems = count($usernames);
	$i = 0;

	$username = '[';

	foreach ($usernames as $name)
	{
		$username .= '"';
		$username .= $name;

		if ($i+1 == $numItems)
		{
			$username .= '"';
		}
		else
		{
			$username .= '",';
		}

		$i++;
	}

	$username .= ']';
} ?>
