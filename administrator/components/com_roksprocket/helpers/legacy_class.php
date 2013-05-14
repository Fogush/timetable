<?php
/**
 * @version   $Id: compatability.php 4051 2012-10-01 22:40:44Z btowles $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2013 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */
defined('_JEXEC') or die('Restricted access');

if (method_exists('JSession','checkToken')) {
	function roksprocket_checktoken($method = 'post')
	{
		if ($method == 'default')
		{
			$method = 'request';
		}
		return JSession::checkToken($method);
	}
} else {
	function roksprocket_checktoken($method = 'post')
	{
		return JRequest::checkToken($method);
	}
}

if (!class_exists('RokSprocketLegacyJView', false)) {
  $jversion = new JVersion();
  if (version_compare($jversion->getShortVersion(), '2.5.5', '>')) {
    class RokSprocketLegacyJView extends JViewLegacy
    {
    }

    class RokSprocketLegacyJController extends JControllerLegacy
    {
    }

    class RokSprocketLegacyJModel extends JModelLegacy
    {
    }
  } else {
    jimport('joomla.application.component.view');
    jimport('joomla.application.component.controller');
    jimport('joomla.application.component.model');
    class RokSprocketLegacyJView extends JView
    {
    }

    class RokSprocketLegacyJController extends JController
    {
    }

    class RokSprocketLegacyJModel extends JModel
    {
    }
  }
}
